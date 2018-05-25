<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use App\Traits\GenerateUniqueId;

use Cookie;

class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $fillable = [
        'id', 'name', 'yomi_name', 'mail_address','company_name','birthday','memo',
        'authority','status','password','created','created_id','modified','modified_id','deleted','deleted_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $incrementing = false;
    public $idFirstLetter = 'us';

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model)
    //     {
    //         $model->generateUniqueId();
    //     });
    // }

    protected function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        $gk = Cookie::get('gk');
        $this->setConnection('pgsql_'. $gk);
    }

    public function phones()
    {
        return $this->hasMany('App\Models\UserPhone', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group','user_groups', 'user_id','group_id');
    }
}
