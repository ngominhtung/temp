<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;
use App\Models\UserPhone;
use App\Helpers\UserRegisterHelper;
use App\Helpers\ModelHelper;

use File;
use DB;
use Exception;
use Log;

class RegisterController extends Controller
{
    protected $helper;
    protected $itemPerPage;
    protected $helperPagination;

    public function __construct()
    {
        $this->helper = new UserRegisterHelper();
        $this->itemPerPage = config('constant.item_per_page');
        $this->helperPagination = new Helper();
    }

    public function view()
    {
        return view('user.register');
    }

    public function download()
    {
        if (!file_exists(public_path('temp/user_temp.csv')))
            return back()->withErrors(trans('register.error_download'));
        else
            return response()->download(public_path('temp/user_temp.csv'));
    }

    public function import(Request $request)
    {
        $import_file = $request->file('import_file');
        if (!$import_file || $import_file->getClientOriginalExtension() != 'csv')
            return back()->withErrors(trans('register.error_format_csv'));

        $import_file_content_array = $this->helper->csvToArray($import_file->getRealPath());
        if (count($import_file_content_array) == 0)
            return back()->withErrors(trans('register.error_file_empty'));

        $new_key = ['name', 'yomi_name', 'phone1', 'phone1_type', 'phone2', 'phone2_type', 'phone3', 'phone3_type', 'phone4', 'phone4_type', 'phone5', 'phone5_type', 'mail_address', 'company_name', 'group1', 'group2', 'group3', 'group4', 'group5', 'birthday', 'memo', 'role', 'password', 'id'];
        foreach ($import_file_content_array as &$item) {
            $i = 0;
            // Check group filled?    
            if (!$this->helper->checkGroupFilled([$item['グループ１'], $item['グループ２'], $item['グループ３'], $item['グループ４'], $item['グループ５']])) {
                return back()->withErrors(trans('register.error_validate_group_filled'));
            }

            foreach ($item as $key => $value) {
                // Check phone type
                if (in_array($i, [3, 5, 7, 9, 11])) {
                    switch (trim($value)) {
                        case '仕事':
                            $value = 2;
                            break;

                        case '自宅':
                            $value = 3;
                            break;

                        case 'FAX(仕事)':
                            $value = 4;
                            break;

                        case 'FAX（自宅)':
                            $value = 5;
                            break;

                        default: // Default is mobile
                            $value = 1;
                            break;
                    }
                }
                $item[$new_key[$i]] = $value;
                unset($item[$key]);
                if ($i == 22) break;
                $i++;
            }
            $item['id'] = ModelHelper::generateUniqueId('users', 'us');
        }
        $data = $this->helper->prepareDataUserRegister($import_file_content_array);
        $request->session()->put('register_data', $data);

        return redirect('user/regist/confirm');
    }

    public function confirmed(Request $request)
    {
        $data = $request->session()->get('register_data') ?? [];
        $message = trans('register.message_save_succses');
        // dd($data);
        $user_fields = ['id', 'name', 'yomi_name', 'mail_address', 'company_name', 'birthday', 'memo', 'authority', 'status', 'password', 'created_id', 'modified_id'];
        $phone_fields = ['user_id', 'type', 'phone', 'created_id', 'modified_id'];
        $group_fields = ['name', 'parent_id', 'status', 'created_id', 'modified_id'];

        DB::beginTransaction();
        try {
            // TODO: check input created_id and modified_id
            $groups = collect();
            foreach ($data as $user) {
                //Insert user
                $arr_user = [];
                foreach ($user_fields as $field) {
                    if ($field == 'status')
                        $arr_user[$field] = 1;
                    elseif ($field == 'created_id' || $field == 'modified_id')
                        $arr_user[$field] = $user['id'];
                    elseif ($field == 'authority') {
                        $arr_user[$field] = $user['role'];
                    }
                    elseif ($field == 'password')
                        $arr_user[$field] = Hash::make($user[$field]);
                    else
                        $arr_user[$field] = isset($user[$field]) ? $user[$field] : '';
                }

                // Check user exists by email -> create or update
                $user_created = User::where('mail_address', $user['mail_address'])->first();
                if ($user_created) {
                    unset($arr_user['id']);
                    unset($arr_user['created_id']);
                    unset($arr_user['modified_id']);
                    unset($arr_user['mail_address']);
                    $user_created->update($arr_user);

                    // If user exists => remove all phone and insert new
                    UserPhone::where('user_id', $user_created->id)->delete();

                    // If user exists => remove all relationship with group and insert new
                    $user_created->groups()->detach();
                } else
                    $user_created = User::create($arr_user);

                // Insert phone
                foreach ($user['phone'] as $phone) {
                    if (empty($phone['number'])) continue;
                    $arr_phone = [];

                    foreach ($phone_fields as $field) {
                        if ($field == 'user_id' || $field == 'created_id' || $field == 'modified_id')
                            $arr_phone[$field] = $user_created->id;
                        elseif ($field == 'phone')
                            $arr_phone[$field] = isset($phone['number']) ? $phone['number'] : '';
                        else
                            $arr_phone[$field] = isset($phone[$field]) ? $phone[$field] : '';
                    }

                    $phone_created = UserPhone::create($arr_phone);
                }

                // Insert group
                $parent_id = null;

                foreach ($user['group'] as $group) {
                    if (empty($group)) continue;

                    // Check group exists on csv file
                    $keys = $groups->keys();
                    if (in_array($group, $keys->all())) {
                        $user_created->groups()->attach($groups[$group]->id, [
                            'id' => ModelHelper::generateUniqueId('user_groups', 'ug'),
                            'created_id' => $user_created->id,
                        ]);
                        continue;
                    }

                    $arr_group = [];

                    foreach ($group_fields as $field) {
                        if ($field == 'status')
                            $arr_group[$field] = 1;
                        elseif ($field == 'parent_id')
                            $arr_group[$field] = $parent_id;
                        elseif ($field == 'created_id' || $field == 'modified_id')
                            $arr_group[$field] = $user_created->id;
                        else
                            $arr_group[$field] = $group;
                    }

                    // Check group exists by name => create or update
                    $group_created = Group::where('name', $group)->first();
                    if ($group_created) {
                        unset($arr_group['name']);
                        $group_created->update($arr_group);
                    } else
                        $group_created = Group::create($arr_group);

                    // Add group created to collection
                    $groups->put($group, $group_created);

                    $user_created->groups()->attach($group_created->id, [
                        'id' => ModelHelper::generateUniqueId('user_groups', 'ug'),
                        'created_id' => $user_created->id,
                    ]);
                    $parent_id = $group_created->id;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            throw $e;
            $message = trans('register.message_save_error');
        }

        return redirect('user/regist');
    }

    public function registerConfirm(Request $request)
    {
        $data = $request->session()->get('register_data') ? $request->session()->get('register_data') : [];
        if (empty($data)) {
            return redirect()->route('user.register.view');
        }
        if (Route::currentRouteName() == 'user.register.search') {
            $data = $this->helper->getDataBySearch($data, config('constant.array_key_search_register_confirm'), $request->value);
        }
        parse_str(request()->getQueryString(), $query);
        $collection = collect($data);
        $optionAndPageForPagination = $this->helperPagination->getOptionAndPageForPagination($data, $request, $this->itemPerPage, $query);
        $maxPage = CEIL(count($data) / $this->itemPerPage);

        $datas = new LengthAwarePaginator($collection->forPage($optionAndPageForPagination['page'], $this->itemPerPage),
            count($collection), $this->itemPerPage, $optionAndPageForPagination['page'], $optionAndPageForPagination['option']);

        return view('user.register_confirm', ['datas' => $datas]);
    }

    public function deleteRegister(Request $request)
    {
        $urlPrev = parse_url(url()->previous());
        $data = $request->session()->get('register_data') ? $request->session()->get('register_data') : [];
        if (isset($request->id) && !empty($request->id)) {
            foreach ($data as $key => $item) {
                if (in_array($item['id'], $request->id)) {
                    unset($data[$key]);
                }
            }
        }
        $request->session()->put('register_data', $data);

        $page = $this->helper->getPage($data, $request, 'delete', $this->itemPerPage);
        parse_str($urlPrev['query'] ?? '', $query);
        $query['page'] = $page ? $page : null;
        if ($urlPrev['path'] == parse_url(route('user.register.search'))['path']) {
            return redirect()->route('user.register.search', $query);
        } else {
            return redirect()->route('user.register.list', $query);
        }
    }

}
