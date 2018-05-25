<?php

namespace App\Models;

use App\Models\BaseModel;

class Group extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'name', 'parent_id', 'status','company_name','birthday','memo','created','created_id','modified','modified_id','deleted','deleted_id',
    ];

    public $incrementing = false;
    public $idFirstLetter = 'gr';

    public function parent()
    {
        return $this->belongsTo('App\Models\Group','parent_id');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Group','parent_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','user_groups', 'group_id','user_id');
    }
}
