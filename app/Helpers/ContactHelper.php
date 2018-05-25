<?php

namespace App\Helpers;

use App\Models\Companies;
use App\Models\CompanyGroups;
use App\Models\Contacts;
use App\Models\Group;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Support\Facades\DB;
use App\Helpers\UserRegisterHelper;

class ContactHelper
{
    protected $userHelper;
    function __construct()
    {
        $this->userHelper = new UserRegisterHelper();
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                // Check blank line
                if (array(null) !== $row) {
                    $new_data = array();
                    foreach ($row as $value) {
                        $new_data[] = mb_convert_encoding($value, "UTF-8", "cp932");
                    }
                    if (!$header)
                        $header = $new_data;
                    else
                        $data[] = array_combine($header, $new_data);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    function checkGroupFilled($groups) {
        $is_ok = true;
        if (trim($groups[0]) == '' && trim($groups[1] != '')) {
            $is_ok = false;
        }
        else {
            unset($groups[0]);
            $groups = array_values($groups);
            $is_ok = count($groups) < 2 ? $groups[0] == '' : $this->checkGroupFilled($groups);
        }
        
        return $is_ok;
    }

    public function prepareDataUserRegister($arrayDataFromSession)
    {
        $config = config('constant');
        $result = [];
        if (!empty($arrayDataFromSession)) {
            foreach ($arrayDataFromSession as $key => $item) {
                $result[] = [
                    'key' => $key,
                    'attribute' => $item['attribute'],
                    'name' => $item['name'],
                    'yomi_name' => $item['yomi_name'],
                    'phone' => [
                        'phone1' => [
                            'number' => $item['phone1'],
                            'type' => $item['phone1_type'],
                            'icon' => $config['icon_phone_type'][$item['phone1_type']] ?? ""
                        ],
                        'phone2' => [
                            'number' => $item['phone2'],
                            'type' => $item['phone2_type'],
                            'icon' => $config['icon_phone_type'][$item['phone2_type']] ?? ""
                        ],
                        'phone3' => [
                            'number' => $item['phone3'],
                            'type' => $item['phone3_type'],
                            'icon' => $config['icon_phone_type'][$item['phone3_type']] ?? ""
                        ],
                        'phone4' => [
                            'number' => $item['phone4'],
                            'type' => $item['phone4_type'],
                            'icon' => $config['icon_phone_type'][$item['phone4_type']] ?? ""
                        ],
                        'phone5' => [
                            'number' => $item['phone5'],
                            'type' => $item['phone5_type'],
                            'icon' => $config['icon_phone_type'][$item['phone5_type']] ?? ""
                        ],
                    ],
                    'mail_address' => [
                        'mail_address1' => [
                            'mail' => $item['mail_address1'],
                            'type' => $item['mail_address1_type'],
                            'icon' => $config['icon_mail_address_type'][$item['mail_address1_type']] ?? ""
                        ],
                        'mail_address2' => [
                            'mail' => $item['mail_address2'],
                            'type' => $item['mail_address2_type'],
                            'icon' => $config['icon_mail_address_type'][$item['mail_address2_type']] ?? ""
                        ],
                        'mail_address3' => [
                            'mail' => $item['mail_address3'],
                            'type' => $item['mail_address3_type'],
                            'icon' => $config['icon_mail_address_type'][$item['mail_address3_type']] ?? ""
                        ],
                        'mail_address4' => [
                            'mail' => $item['mail_address4'],
                            'type' => $item['mail_address4_type'],
                            'icon' => $config['icon_mail_address_type'][$item['mail_address4_type']] ?? ""
                        ],
                        'mail_address5' => [
                            'mail' => $item['mail_address5'],
                            'type' => $item['mail_address5_type'],
                            'icon' => $config['icon_mail_address_type'][$item['mail_address5_type']] ?? ""
                        ],
                    ],
                    'company_name' => $item['company_name'],
                    'group' => [
                        0 => $item['group1'],
                        1 => $item['group2'],
                        2 => $item['group3'],
                        3 => $item['group4'],
                        4 => $item['group5'],
                    ],
                    'birthday' => $item['birthday'],
                    'memo' => $item['memo'],
                    'setting_share' => $item['setting_share'],
                    'tag' => $item['tag'],
                ];
            }
        }

        return $result;
    }

    public function checkTypePhoneAndMail($i,$value){
        if (in_array($i, config('constant.array_key_phone_type'))) {
            //check type phone
            switch (trim($value)) {
                case '携帯番号':
                    $value = 1;
                    break;

                case '仕事':
                    $value = 2;
                    break;

                case '自宅':
                    $value = 3;
                    break;

                case 'FAX(仕事)':
                    $value = 4;
                    break;

                case 'FAX(自宅)':
                    $value = 5;
                    break;

                default: // Default is mobile
                    $value = 1;
                    break;
            }
        }

        if (in_array($i, config('constant.array_key_mail_address_type'))) {
            //check mail type
            switch (trim($value)) {
                case '会社':
                    $value = 1;
                    break;

                case '携帯':
                    $value = 2;
                    break;

                case '自宅':
                    $value = 3;
                    break;

                default: // Default is company
                    $value = 1;
                    break;
            }
        }
        return $value;
    }

    /*=====================================================Company==================================================*/
    /**todo find company
     * @param $id
     * @return mixed
     */
    public function findCompanyById($id)
    {
        return Companies::where('status', config('constant.status.available'))
            ->where('id', $id)->first();

    }

    /**
     * todo get all company and pagination
     * @param $itemPerPage
     * @return mixed
     */
    public function getAllCompany($itemPerPage)
    {
        $data = Companies::where('status', config('constant.status.available'))->paginate($itemPerPage);

        return $data;
    }

    /**
     * todo search company by name
     * @param $valueSearch
     * @param $itemPerPage
     * @return mixed
     */
    public function getCompanyBySearch($valueSearch, $itemPerPage)
    {
        $data = Companies::where('status', config('constant.status.available'))
            ->where('name','like',"%\\".$valueSearch.'%')
            ->paginate($itemPerPage);

        return $data;
    }

    /**
     * todo delete companies
     * @param $companyIds
     * @return mixed
     */
    public function deleteCompany($companyIds)
    {
        $isDeleted = Companies::whereIn('id', $companyIds)
            ->update([
                'status' => config('constant.status.deleted'),
//               'deleted_id' => 1, // id_user_logged in
                'deleted' => Carbon::now()
            ]);

        return $isDeleted;
    }
    /*=================================================Group===========================================================*/
    /**
     * todo recursive list group to echo
     * @param $companyName
     * @param $data
     * @param $group
     * @param int $parentId
     * @param string $text
     */
    public function recursiveListGroup($parents, $companyName, &$group)
    {
        $companyName .= ' > ';
        foreach ($parents as $keyParent => $itemParent){
            $group[$itemParent->id]['name'] = $companyName.$itemParent->name;
            $group[$itemParent->id]['id'] = $itemParent->id;
            $this->getSubGroups($companyName, $itemParent->name, $itemParent->id, $group);
        }

    }
    public function getSubGroups($companyName, $pName, $idP, &$group, $text = ' > ')
    {
        $children = CompanyGroups::where('parent_id', $idP)
            ->where('status', config('constant.status.available'))
            ->get();
        if(!$children->isEmpty()){
            foreach ($children as $keyChildren => $itemChildren){
                $newId = $itemChildren->id;
                $group[$itemChildren->id]['name'] = $companyName.$pName.$text.$itemChildren->name;
                $group[$itemChildren->id]['id'] = $itemChildren->id;
                $this->getSubGroups($companyName, $pName, $newId, $group, $text.$itemChildren->name.' > ');
            }
        }
    }

    /**
     * todo find all children group of a group
     * @param $data
     * @param $id
     * @param array $result
     */
    public function findAllChildGroup($data, $id, &$result = [])
    {
        foreach ($data as $key => $item){
            if($item->parent_id == $id){
                $nextId = $item->id;
                $result[$item->id] = $item->id;
                $data = $data->forget($key);
                $this->findAllChildGroup($data, $nextId, $result);
            }
        }

    }

    /**
     * todo find all parents group of a group
     * @param $data
     * @param $id
     * @param array $result
     */
    public function findAllParentsGroup($data, $id, &$parents = [])
    {
        $child = $this->findCompanyGroupById($id);
        $parent = $this->findCompanyGroupById($child->parent_id);
        if($parent){
            $parents[$parent->id]['name'] = $parent->name;
            $parents[$parent->id]['id'] = $parent->id;
            $this->findAllParentsGroup($data, $parent->id, $parents);
        }else{
            return;
        }
    }

    /**
     * todo get all company_groups by company_id
     * @param $idCompany
     * @return mixed
     */
    public function getAllCompanyGroup($idCompany)
    {
        $data = CompanyGroups::where('company_id', $idCompany)
            ->where('status', config('constant.status.available'))
            ->get();

        return $data;
    }

    /**
     * todo find one company group by id group
     * @param $id
     * @return mixed
     */
    public function findCompanyGroupById($id)
    {
        $data = CompanyGroups::where('status', config('constant.status.available'))
            ->where('id', $id)
            ->first();

        return $data;
    }

    public function deleteGroup($ids)
    {
        $deleted = CompanyGroups::whereIn('id', $ids)
            ->update([
                'status' => config('constant.status.deleted'),
                'deleted' => Carbon::now(),
                'deleted_id' => auth()->user()->id
            ]);

        return $deleted;
    }

    public function deleteGroupByCompany($ids)
    {
        $deleted = CompanyGroups::whereIn('company_id', $ids)
            ->update([
                'status' => config('constant.status.deleted'),
                'deleted' => Carbon::now(),
                'deleted_id' => auth()->user()->id
            ]);

        return $deleted;
    }

    public function searchGroup($valueSearch, $arrayGroup)
    {
        $groups = [];
        if(!isset($valueSearch)){
            $groups = $arrayGroup;
        }else{
            foreach ($arrayGroup as $key => $item){
                if(strpos(strtolower($item['name']), strtolower($valueSearch))){
                    $groups[] = $item;
                }
            }
        }

        return $groups;
    }

    public function getAllParentGroup($idCompany)
    {
        return CompanyGroups::where('parent_id', null)
            ->where('company_id', $idCompany)
            ->where('status',config('constant.status.available'))
            ->get();
    }
    /*==========================================================Contact=============================================*/
    /**
     * todo get all contact of a group and pagination
     * @param $ids
     * @param $itemPerPage
     * @return mixed
     */
    public function getContactByGroup($idCompany, $ids, $itemPerPage)
    {
        $data = Contacts::whereIn('group_id', $ids)
            ->where('company_id', $idCompany)
            ->where('status', config('constant.status.available'))
            ->paginate($itemPerPage);

        return $this->prepareDataContact($data);
    }


    /**
     * todo get contact by search in DB
     * @param $ids
     * @param $itemPerPage
     * @param $valueSearch
     * @return mixed
     */
    public function getContactBySearch($idCompany, $ids, $itemPerPage, $valueSearch)
    {
        $contacts = Contacts::where('status', config('constant.status.available'))
            ->whereIn('group_id', $ids)
            ->where('company_id', $idCompany)
            ->where(function ($query) use($valueSearch) {
                $query->whereHas('contact_phone', function ($querySearch) use ($valueSearch){
                    $querySearch->where('phone', 'like', '%\\'.$valueSearch.'%');
                })->orWhereHas('contact_mailaddress', function($query) use ($valueSearch){
                    $query->where('mailaddress', 'like', '%\\'.$valueSearch.'%');
                })->orWhereHas('tags', function($query) use ($valueSearch){
                    $query->where('name', 'like', '%\\'.$valueSearch.'%');
                })->orWhere('name', 'like', '%'.$valueSearch.'%')
                    ->orWhere('yomi_name', 'like', '%\\'.$valueSearch.'%');

            })->paginate($itemPerPage);

        return $this->prepareDataContact($contacts);
    }

    public function prepareDataContact($contact)
    {
        foreach ($contact as $key => $item){
            switch ($item->share_unit){
                case '社内':
                    $settingShare = '';
                    break;
                case 'グループ名':
                    $group = $this->findCompanyGroupById($item->share_id);
                    $settingShare = $group->name ?? "";
                    break;
                case 'メールアドレス':
                    $user = $this->userHelper->findUserById($item->share_id);
                    $settingShare = $user->name ?? "";
                    break;
                default:
                    $settingShare = '';
                    break;
            }
            $item->settingShare = $settingShare;

        }

        return $contact;
    }
    /**
     * todo delete contact by array group id
     * @param $arrayId
     * @return bool
     */
    public function deleteContactById($arrayId)
    {
        $isDelete = Contacts::where('status', config('constant.status.available'))
            ->whereIn('id', $arrayId)
            ->update([
                'status' => config('constant.status.deleted'),
                'deleted_id' => auth()->user()->id, // id_user_logged in
                'deleted' => Carbon::now() // id_user_logged in
            ]);

        if($isDelete){
            return true;
        }else{
            return false;
        }
    }

    public function deleteContactByGroup($ids)
    {
        $isDelete = Contacts::whereIn('group_id', $ids)
            ->update([
                'status' => config('constant.status.deleted'),
                'deleted_id' => auth()->user()->id, // id_user_logged in
                'deleted' => Carbon::now() // id_user_logged in
            ]);

        if($isDelete){
            return true;
        }else{
            return false;
        }
    }

    public function deleteContactByCompany($companyIds)
    {
        $isDeleted = Contacts::whereIn('company_id', $companyIds)
            ->update([
                'status' => config('constant.status.deleted'),
                'deleted_id' => auth()->user()->id, // id_user_logged in
                'deleted' => Carbon::now()
            ]);

        return $isDeleted;
    }
}
