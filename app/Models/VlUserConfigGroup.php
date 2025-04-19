<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VlUserConfigGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_config_id',
        'groupname',
        'token',
    ];

    public function header(){
        return $this->hasOne(VlUserConfig::class,'id','user_config_id');
    }

}
