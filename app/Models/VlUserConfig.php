<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VlUserConfig extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->token = Str::uuid()->toString();
        });
    }

    protected $fillable = [
        'configname',
        'token',
    ];

    public function lines(){
        return $this->hasMany(VlUserConfigGroup::class,'user_config_id','id');
    }

}
