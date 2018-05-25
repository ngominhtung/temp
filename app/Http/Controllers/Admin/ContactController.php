<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use App\Helpers\ContactHelper;
use App\Helpers\ModelHelper;
use App\Helpers\UserRegisterHelper;
use App\Models\Companies;
use App\Models\CompanyGroups;
use App\Models\Contacts;
use App\Models\ContactPhone;
use App\Models\ContactMailAddresses;
use App\Models\Tags;
use App\Models\User;

use DB;
use Validator;
use File;
use Exception;
use Log;


class ContactController extends Controller
{
    protected $group;
    protected $itemPerPage;
    protected $helper;
    protected $userRegisterHelper;
    protected $contactHelper;
    protected $helperPagination;


    function __construct()
    {
        $this->group = [];
        $this->itemPerPage = config('constant.contact.item_per_page');
        $this->userRegisterHelper = new UserRegisterHelper();
        $this->helper = new Helper();
        $this->contactHelper = new ContactHelper();
        $this->helperPagination = new Helper();
    }

    public function index()
    {
        return view('site.contact.contact_import');
    }

    public function download() {
        if (!file_exists(public_path('temp/contact_temp.csv')))
            return back()->withErrors(trans('contact.error_download.download_fail'));
        else
            return response()->download(public_path('temp/contact_temp.csv'));
    }

    public function import(Request $request) {
        $import_file = $request->file('import_file');
        if (!$import_file || $import_file->getClientOriginalExtension() != 'csv')
            return back()->withErrors(trans('contact.error_import.format_file'));

        $import_file_content_array = $this->contactHelper->csvToArray($import_file->getRealPath());
        if (count($import_file_content_array) == 0) 
            return back()->withErrors(trans('contact.error_import.empty_file'));

        $new_key = config('constant.array_contact_key');
        foreach ($import_file_content_array as &$item) {
            $i = 0;
            // Check group filled?    
            if (!$this->contactHelper->checkGroupFilled([$item['グループ１'], $item['グループ２'], $item['グループ３'], $item['グループ４'], $item['グループ５']])) {
                return back()->withErrors(trans('contact.error_import.group_error'));
            }

            foreach ($item as $key => $value) {
                // Check phone type
                $value = $this->contactHelper->checkTypePhoneAndMail($i,$value);
                $item[$new_key[$i]] = $value;
                unset($item[$key]);
                if ($i == 33) break;
                $i++;
            }
        }
        $data = $this->contactHelper->prepareDataUserRegister($import_file_content_array);
        $request->session()->put('contact_data', $data);
        return redirect('contact/regist/register');
    }

    public function confirmed (Request $request) {
        $data = $request->session()->get('contact_data') ?? [];

        $message = 'Upload DB success';
        $contact_fields = ['attribute', 'name', 'yomi_name', 'company_id', 'group_id', 'birthday', 'memo', 'share_unit', 'share_id', 'status', 'created_id', 'modified_id'];
        $phone_fields = ['contact_id', 'phone', 'type', 'created_id', 'modified_id'];
        $mail_address_fields = ['contact_id', 'mailaddress', 'type', 'created_id', 'modified_id'];
        $company_fields = ['name','status','created_id', 'modified_id'];
        $company_groups_fields = ['company_id', 'name', 'status', 'parent_id', 'created_id', 'modified_id'];
        $tag_fields = ['name','created_id', 'modified_id'];
        $fake_user_id = 'usN2DVSl';

        DB::beginTransaction();
        try {
            // TODO: check input status, created_id and modified_id
            $groups = collect();
            $company = collect();
            $user_mail = collect();
            $tags = collect();
            foreach ($data as $contact) {
                $validator = Validator::make($contact,
                    [
                        'name' => 'required',
                        'phone.phone1.number' => 'required',
                        'mail_address.mail_address1.mail' => 'required',
                        'birthday' => 'date_format:"Y/n/j"',
                    ],
                    [
                        'name.required' => trans('contact.error_import.name_error'),
                        'birthday.date_format' => trans('contact.error_import.birthday_error'),
                        'phone.phone1.number.required' => trans('contact.error_import.phone_error'),
                        'mail_address.mail_address1.mail.required' => trans('contact.error_import.mail_error'),
                    ]
                );

                if($validator->fails())
                    return back()->withErrors($validator)->withInput();

                // Insert company
                $keys = $company->keys();
                $company_current = null;
                if (in_array($contact['company_name'], $keys->all())) {
                    $company_current = $company[$contact['company_name']];
                }else{
                    $insert_company = [];
                    foreach ($company_fields as $fields) {
                        if ($fields == 'status')
                            $insert_company[$fields] = 1;
                        elseif($fields == 'created_id' || $fields == 'modified_id')
                            $insert_company[$fields] = $fake_user_id;
                        else
                            $insert_company[$fields] = $contact['company_name'];
                    }

                    // Check group exists by name => create or update
                    $company_current = Companies::where('name', $contact['company_name'])->first();
                    if ($company_current) {
                        unset($insert_company['name']);
                        $company_current->update($insert_company);
                    }
                    else
                        $company_current = Companies::create($insert_company);

                    $company->put($contact['company_name'], $company_current);
                }

                // Insert group
                $parent_id = null;
                $last_group = null;
                foreach ($contact['group'] as $key => $group) {
                    if (empty($group)) break;

                    // Check group exists on csv file
                    $keys = isset($groups[$company_current->id]) ? $groups[$company_current->id]->keys() : collect([]);
                    if (!in_array($group, $keys->all())) {
                        $arr_group = [];

                        foreach ($company_groups_fields as $field) {
                            if ($field == 'company_id')
                                $arr_group[$field] = $company_current->id;
                            elseif ($field == 'parent_id')
                                $arr_group[$field] = $parent_id;
                            elseif ($field == 'status')
                                $arr_group[$field] = 1;
                            elseif ($field == 'created_id' || $field == 'modified_id')
                                $arr_group[$field] = $fake_user_id;
                            else
                                $arr_group[$field] = $group;
                        }

                        // Check group exists by name => create or update
                        $group_created = CompanyGroups::where('name', $group)->where('company_id', $company_current->id)->first();
                        if ($group_created) {
                            unset($arr_group['name']);
                            $group_created->update($arr_group);
                        } else
                            $group_created = CompanyGroups::create($arr_group);

                        // Add group created to collection
                        if (!isset($groups[$company_current->id])) $groups[$company_current->id] = collect([]);
                        $groups[$company_current->id]->put($group, $group_created);
                        $parent_id = $group_created->id;

                        $last_group = $group_created;

                    }else{
                        $last_group = $groups[$group];
                    }
                }

                //Insert contact
                $arr_contact = [];
                foreach ($contact_fields as $field) {
                    switch ($field) {
                        case 'attribute':
                            if(empty($contact['attribute']) || $contact['attribute'] !== '個人' || $contact['attribute'] !== '会社')
                                $arr_contact[$field] = 1;
                            else
                                $arr_contact[$field] = config('constant.attribute_save.' . $contact[$field]);
                            break;
                        case 'company_id':
                            $arr_contact[$field] = $company_current->id;
                            break;
                        case 'group_id':
                            $arr_contact[$field] = $last_group ? $last_group->id : null;
                            break;
                        case 'share_unit':
                            break;
                        case 'share_id':
                            if( $contact['setting_share'] == '')  $contact['setting_share'] = '社内';
                            if($contact['setting_share'] == '社内') {
                                $arr_contact[$field] = '';
                                $arr_contact['share_unit'] = config('constant.share_setting_type.company');
                            }else{
                                if (filter_var($contact['setting_share'], FILTER_VALIDATE_EMAIL)) {
                                    // mail share
                                    $arr_contact['share_unit'] = config('constant.share_setting_type.user');
                                    $user_share = User::where('mail_address', $contact['setting_share'])->first();
                                    if (!$user_share) {
                                        return back()->withErrors(trans('contact.error_import.share_setting_error'));
                                    }

                                    $arr_contact[$field] = $user_share->id;
                                }
                                else {
                                    // group share
                                    $arr_contact['share_unit'] = config('constant.share_setting_type.group');
                                    $group_share_name = $contact['setting_share'];
                                    if (strpos('::', $contact['setting_share']) !== 0) {
                                        $arr_split_setting_share = explode('::', $contact['setting_share']);
                                        $group_share_name = $arr_split_setting_share[count($arr_split_setting_share) - 1];
                                    }

                                    $keys = $groups->keys();
                                    $group_share = null;
                                    if (!in_array($group_share_name, $keys->all())) {
                                        $group_share = CompanyGroups::where('name', $group_share_name)->first();
                                        if (!$group_share) {
                                            return back()->withErrors(trans('contact.error_import.share_setting_error'));
                                        }
                                    }
                                    else {
                                        $group_share = $groups[$contact['setting_share']];
                                    }
                                    
                                    $arr_contact[$field] = $group_share->id;
                                }
                            }
                            break;
                        case 'status':
                            $arr_contact[$field] = 1;
                            break;
                        case 'birthday':
                            $arr_contact[$field] = $contact['birthday'] && !empty($contact['birthday']) ? $contact['birthday'] : null;
                            break;
                        case 'created_id':
                            $arr_contact[$field] = $fake_user_id;
                            break;
                        case 'modified_id':
                            $arr_contact[$field] = $fake_user_id;
                            break;
                        default:
                            $arr_contact[$field] = isset($contact[$field]) ? $contact[$field] : '';
                            break;
                    }
                }
                $contact_created = Contacts::create($arr_contact);

                // Insert contact phone
                foreach ($contact['phone'] as $phone) {
                    if (empty($phone['number'])) continue;
                    $arr_phone = [];

                    foreach ($phone_fields as $field) {
                        if ($field == 'contact_id')
                            $arr_phone[$field] = $contact_created->id;
                        elseif($field == 'created_id' || $field == 'modified_id')
                            $arr_phone[$field] = $fake_user_id;
                        elseif ($field == 'phone')
                            $arr_phone[$field] = isset($phone['number']) ? $phone['number'] : '';
                        else
                            $arr_phone[$field] = isset($phone[$field]) ? $phone[$field] : '';
                    }

                    $contact_phone_created = ContactPhone::create($arr_phone);
                }

                // Insert contact mail
                foreach ($contact['mail_address'] as $mail) {

                    if (empty($mail['mail'])) continue;
                    $arr_mail = [];

                    foreach ($mail_address_fields as $field) {
                        if ($field == 'contact_id')
                            $arr_mail[$field] = $contact_created->id;
                        elseif($field == 'created_id' || $field == 'modified_id')
                            $arr_mail[$field] = $fake_user_id;
                        elseif ($field == 'mailaddress')
                            $arr_mail[$field] = isset($mail['mail']) ? $mail['mail'] : '';
                        else
                            $arr_mail[$field] = isset($mail[$field]) ? $mail[$field] : '';
                    }

                    $contact_mail_address_created = ContactMailAddresses::create($arr_mail);
                }

                //Insert Tags
                $tags_all = explode('::', $contact['tag']);
                foreach ($tags_all as $tag) {
                    if (empty($tag)) continue;

                    // Check group exists on csv file
                    $keys = $tags->keys();
                    if (in_array($tag, $keys->all())) {
                        $contact_created->tags()->attach($tags[$tag]->id, [
                            'id' => ModelHelper::generateUniqueId('contact_tags', 'cs'),
                            'created_id' => $fake_user_id,
                        ]);
                        continue;
                    }

                    $arr_tag = [];
                    foreach ($tag_fields as $field) {
                        if ($field == 'created_id' || $field == 'modified_id')
                            $arr_tag[$field] = $fake_user_id;
                        else
                            $arr_tag[$field] = $tag;
                    }
                    
                    // Check tags exists by name => create or update
                    $tag_created = Tags::where('name', $tag)->first();
                    if ($tag_created) {
                        unset($arr_tag['name']);
                        $tag_created->update($arr_tag);
                    }
                    else
                        $tag_created = Tags::create($arr_tag);
                    
                    // Add tag created to collection
                    $tags->put($tag, $tag_created);
                    
                    $contact_created->tags()->attach($tag_created->id, [
                        'id' => ModelHelper::generateUniqueId('contact_tags', 'cs'),
                        'created_id' => $fake_user_id,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            throw $e;
            $message =trans('contact.error_import.message_save_error');
        }

        return redirect('company');

    }

    public function group(Request $request, $idCompany)
    {
        $groups = [];
        $companyName = $this->contactHelper->findCompanyById($idCompany);
        if(!$companyName){
            //if company does not exist
            return redirect(route('contact.register.company'));
        }else{
            $parents = $this->contactHelper->getAllParentGroup($idCompany);
            $this->contactHelper->recursiveListGroup($parents, $companyName->name, $this->group);
            if(Route::currentRouteName() == 'contact.register.group.search'){
                $groups = $this->contactHelper->searchGroup($request->value, $this->group);
            }else{
                $groups = $this->group;
            }
            parse_str(request()->getQueryString(), $query);
            $options = [
                'pageName' => 'page',
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query
            ];
            $page = $request->page ?? 1;
            $collection = collect($groups);
            $forPage = $collection->forPage($page, $this->itemPerPage);
            $datas = new LengthAwarePaginator($forPage, count($collection), $this->itemPerPage, $page, $options);
        }

        return view('company.group', compact('datas', 'idCompany'));
    }

    public function deleteGroup(Request $request, $idCompany)
    {
        $idGroups = $request->idGroup;
        $resultChilds = [];
        $allGroups = $this->contactHelper->getAllCompanyGroup($idCompany);
        $companyName = $this->contactHelper->findCompanyById($idCompany);

        //delete group
        foreach ($idGroups as $keyGroup => $itemGroup){
            $resultChilds[$itemGroup] = $itemGroup;
            $this->contactHelper->findAllChildGroup($allGroups, $itemGroup, $resultChilds);
        }
        $deletedGroup = $this->contactHelper->deleteGroup($resultChilds);

        //delete contact
        $deletedContact = $this->contactHelper->deleteContactByGroup($resultChilds);

        $urlPrev = parse_url(url()->previous());
        parse_str($urlPrev['query'] ?? '', $query);
        if($urlPrev['path'] == parse_url(route('contact.register.group.search'))['path']){
            //search group after delete
            $parents = CompanyGroups::where('parent_id', null)->where('company_id', $idCompany)->get();
            $this->contactHelper->recursiveListGroup($parents, $companyName->name, $this->group);
            $groups = $this->contactHelper->searchGroup($request->value, $this->group);
            $maxPage = CEIL(count($groups)/$this->itemPerPage);
            if(isset($currentPage)){
                $page = $currentPage <= $maxPage ? $currentPage : $maxPage;
            }else{
                $page = 1;
            }
            $query['page'] = $page ? $page : 1;
            $route = 'contact.register.company.search';
        }else{
            $data = $this->contactHelper->getAllCompany($this->itemPerPage);
            $query['page'] = $data->lastPage() ? $data->lastPage() : 1;

            $route = 'contact.register.company';
        }
        $query['idCompany'] = $idCompany;
        return redirect()->route($route, $query);
    }

    public function contact(Request $request, $idCompany,  $idGroup)
    {
        $resultChilds = [];
        $contacts = [];

        $data = $this->contactHelper->getAllCompanyGroup($idCompany);
        if(!$data->isEmpty()){
            $group = $this->contactHelper->findCompanyGroupById($idGroup);
            if($group){
                $resultChilds[$idGroup] = $group->id;
                $this->contactHelper->findAllChildGroup($data, $idGroup, $resultChilds);
                if(Route::currentRouteName() == 'contact.register.search'){
                    parse_str(request()->getQueryString(), $query);
                    $contacts = $this->contactHelper->getContactBySearch($idCompany, $resultChilds, $this->itemPerPage, $request->value);
                    $contacts->appends($query);
                }else{
                    $contacts = $this->contactHelper->getContactByGroup($idCompany, $resultChilds, $this->itemPerPage);
                }
            }else{
                //if group does not exist
                return redirect(route('contact.register.group', ['idCompany' => $idCompany]));
            }
        }

        return view('site.contact.contact', compact('contacts', 'idCompany', 'idGroup'));
    }

    public function deleteContact(Request $request, $idCompany,  $idGroup)
    {
        $data = $this->contactHelper->getAllCompanyGroup($idCompany);
        $urlPrev = parse_url(url()->previous());
        $deletedContact = $this->contactHelper->deleteContactById($request->idContactChecked);
        /*get query to redirect after delete*/
        parse_str($urlPrev['query'] ?? '', $query);
        $resultChilds[$idGroup] = $idGroup;
        $this->contactHelper->findAllChildGroup($data, $idGroup, $resultChilds);
        if($urlPrev['path'] == parse_url(route('contact.register.search', ['idCompany' => $idCompany, 'idGroup' => $idGroup]))['path']){
            $contacts = $this->contactHelper->getContactBySearch($idCompany, $resultChilds, $this->itemPerPage, $query['value']);
            $route = 'contact.register.search';
        }else{
            $contacts = $this->contactHelper->getContactByGroup($idCompany, $resultChilds, $this->itemPerPage);
            $route = 'contact.register.list';
        }

        $query['idCompany'] = $idCompany;
        $query['idGroup'] = $idGroup;
        $query['page'] = $contacts->lastPage() ? $contacts->lastPage() : 1;
        return redirect()->route($route, $query);
    }


    public function company(Request $request)
    {
        if(Route::currentRouteName() == 'contact.register.company.search'){
            parse_str(request()->getQueryString(), $query);
            $data = $this->contactHelper->getCompanyBySearch($request->value, $this->itemPerPage);
            $data->appends($query);
        }else{
            $data =$this->contactHelper->getAllCompany($this->itemPerPage);
        }

        return view('company.company', ['data' => $data]);
    }

    public function deleteContactByCompany(Request $request)
    {
        //delete company
        $deletedCompany = $this->contactHelper->deleteCompany($request->idCompany);

        //delete group
        $deleteGroup = $this->contactHelper->deleteGroupByCompany($request->idCompany);

        //delete contact
        $deletedContact = $this->contactHelper->deleteContactByCompany($request->idCompany);

        $urlPrev = parse_url(url()->previous());
        parse_str($urlPrev['query'] ?? '', $query);
        if($urlPrev['path'] == parse_url(route('contact.register.company.search'))['path']){
            $data = $this->contactHelper->getCompanyBySearch($query['value'], $this->itemPerPage);
            $route = 'contact.register.company.search';
        }else{
            $data = $this->contactHelper->getAllCompany($this->itemPerPage);
            $route = 'contact.register.company';
        }


        $query['page'] = $data->lastPage() ? $data->lastPage() : 1;

        return redirect()->route($route, $query);
    }

}
