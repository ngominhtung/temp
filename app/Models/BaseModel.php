<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUniqueId;

use Cookie;

class BaseModel extends Model
{
    use GenerateUniqueId;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model)
        {
            $model->generateUniqueId();
        });
    }

    protected function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        $gk = Cookie::get('gk');
        $this->setConnection('pgsql_'. $gk);
    }
}
