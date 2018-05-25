<?php

namespace App\Helpers;
use App\Helpers\UserRegisterHelper;
use Illuminate\Pagination\LengthAwarePaginator;

class Helper
{
    protected $helper;
    function __construct()
    {
        $this->helper = new UserRegisterHelper();
    }

    public function getOptionAndPageForPagination($data, $request, $itemPerPage, $query = '')
    {
        $result = [];
        $options = [
            'pageName' => 'page',
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $query
        ];
        $page = $this->helper->getPage($data, $request, '', $itemPerPage);

        $result = [
            'option' => $options,
            'page' => $page
        ];

        return $result;
    }
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
}
