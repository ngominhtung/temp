<?php

namespace App\Models;

use App\Models\BaseModel;

class Companies extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public $incrementing = false;
    public $idFirstLetter = 'cy';

    protected $fillable = [
        'name', 'status', 'created','created_id','modified','modified_id','deleted','deleted_id',
    ];

    public function contacts()
    {
        return $this->hasMany('App\Models\Contacts','company_id');
    }

    public function company_groups()
    {
        return $this->hasMany('App\Models\CompanyGroups	','company_id');
    }
}
