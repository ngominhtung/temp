<?php

namespace App\Models;

use App\Models\BaseModel;

class UserPhone extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'user_id', 'type', 'phone','created','created_id','modified','modified_id','deleted','deleted_id',
    ];

    public $incrementing = false;
    public $idFirstLetter = 'up';

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
