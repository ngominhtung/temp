<?php

namespace App\Models;

use App\Models\BaseModel;

class Contacts extends BaseModel
{
	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

	protected $fillable = [
		'attribute', 'name', 'yomi_name', 'company_id','group_id','birthday','memo',
		'share_unit', 'share_id', 'status','created','created_id','modified','modified_id','deleted','deleted_id',
	];


	public $incrementing = false;
	public $idFirstLetter = 'co';

	public function contact_mailaddress()
	{
		return $this->hasMany('App\Models\ContactMailAddresses','contact_id');
	}

	public function contact_phone()
	{
		return $this->hasMany('App\Models\ContactPhone','contact_id');
	}

	public function tags()
	{
		return $this->belongsToMany('App\Models\Tags','contact_tags','contact_id','tag_id');
	}

	public function company_groups()
	{
		return $this->belongsTo('App\Models\CompanyGroups','group_id');
	}

	public function companies()
	{
		return $this->belongsTo('App\Models\Companies','company_id');
	}

}

