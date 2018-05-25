<?php

namespace App\Helpers;

use App\Models\User;

class UserRegisterHelper
{
    public function prepareDataUserRegister($arrayDataFromSession)
    {
        $config = config('constant');
        $result = [];
        if (!empty($arrayDataFromSession)) {
            foreach ($arrayDataFromSession as $key => $item) {
                $result[] = [
                    "key" => $key,
                    "name" => $item['name'],
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
                    'mail_address' => $item['mail_address'],
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
                    'role_name' => !empty($item['role']) && (strtolower($item['role']) == 'main' || strtolower($item['role']) == 'sub') ? $config['role_text'][$config['role'][strtolower($item['role'])]] : $config['role_text'][$config['role']['other']],
                    'role' => !empty($item['role']) && (strtolower($item['role']) == 'main' || strtolower($item['role']) == 'sub') ? $config['role'][strtolower($item['role'])] : $config['role']['other'],
                    'password' => $item['password'],
                    'id' => $item['id'],
                ];
            }
        }

        return $result;
    }

    /*
     * Created: QuyBX - 07/05/2018
     * convert csv to array (with Japanese character)
     * @param  string  $filename
     * @param  string  $delimiter
     * @return array
     */
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

    /*
     * Created: QuyBX - 10/05/2018
     * validate group filled
     * @param  array  $groups
     * @return boolean
     */
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

    /**
     * manh.nguyen
     * todo search from array session
     * @param $data
     * @param $arrayKeySearch
     * @param $valueSearch
     * @return array
     */
    public function getDataBySearch($data, $arrayKeySearch, $valueSearch)
    {
        if(!$valueSearch){
            return $data;
        }
        $result = [];
        foreach ($data as $key => $item){
            foreach ($arrayKeySearch as $keySearch){
                if($keySearch == 'phone'){
                    foreach ($item[$keySearch] as $itemKeySearch){
                        if(strpos($itemKeySearch['number'], $valueSearch) !== false){
                            $result[] = $data[$key];
                            break;
                        }
                    }
                }elseif($keySearch == 'group'){
                    foreach ($item[$keySearch] as $itemKeySearch){
                        if(strpos($itemKeySearch, $valueSearch) !== false){
                            $result[] = $data[$key];
                            break;
                        }
                    }
                }else{
                    if(strpos($item[$keySearch], $valueSearch) !== false){
                        $result[] = $data[$key];
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * manh.nguyen
     * todo get current page to redirect
     * @param $data
     * @param $request
     * @param string $action
     * @param $itemPerPage
     * @return float|int
     */
    public function getPage($data, $request, $action = '', $itemPerPage)
    {
        if($action == 'delete'){
            $urlPrev = parse_url(url()->previous());
            if($urlPrev['path'] == parse_url(route('user.register.search'))['path']){
                parse_str($urlPrev['query'], $query);
                $data = $this->getDataBySearch($data, config('constant.array_key_search_register_confirm'), $query['value']);
            }
            $currentPage = $request->currentPage;
        }else{
            $currentPage = $request->page;
        }
        $maxPage = CEIL(count($data)/$itemPerPage);
        if(isset($currentPage)){
            $page = $currentPage <= $maxPage ? $currentPage : $maxPage;
        }else{
            $page = 1;
        }

        return $page;
    }

    public function findUserById($id)
    {
        $user = User::where('status', config('constant.status.available'))
            ->where('id', $id)
            ->first();

        return $user;
    }
}
