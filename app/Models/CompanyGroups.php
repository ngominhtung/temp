<?php

namespace App\Models;

use App\Models\BaseModel;

class CompanyGroups extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'company_id', 'name', 'status', 'parent_id', 'created','created_id','modified','modified_id','deleted','deleted_id',
    ];

    public $incrementing = false;
    public $idFirstLetter = 'cg';

    public function contacts()
    {
        return $this->hasMany('App\Models\Contacts','group_id');
    }

    public function companies()
    {
        return $this->belongsTo('App\Models\Companies','company_id');
    }
}
