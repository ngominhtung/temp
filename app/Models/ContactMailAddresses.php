<?php

namespace App\Models;

use App\Models\BaseModel;

class ContactMailAddresses extends BaseModel
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'contact_id', 'mailaddress', 'type', 'created','created_id','modified','modified_id',
    ];

    public $incrementing = false;
	public $idFirstLetter = 'cm';

    public function contacts()
    {
        return $this->belongsTo('App\Models\Contacts','contact_id');
    }
}
