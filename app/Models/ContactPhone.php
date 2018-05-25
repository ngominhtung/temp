<?php

namespace App\Models;

use App\Models\BaseModel;

class ContactPhone extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'contact_id', 'phone', 'type', 'created','created_id','modified','modified_id',
    ];

    public $incrementing = false;
	public $idFirstLetter = 'cp';

    public function contacts()
    {
        return $this->belongsTo('App\Models\Contacts','contact_id');
    }
}
