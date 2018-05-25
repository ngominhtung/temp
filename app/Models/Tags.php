<?php

namespace App\Models;

use App\Models\BaseModel;

class Tags extends BaseModel
{
    const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

	protected $fillable = [
		'name','created','created_id','modified','modified_id',
	];


	public $incrementing = false;
	public $idFirstLetter = 'tg';

	public function contacts()
	{
		return $this->belongsToMany('App\Models\Contact','contact_tags','tag_id','contact_id');
	}
}
