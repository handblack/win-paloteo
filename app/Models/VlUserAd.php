<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VlUserAd extends Model
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
        'doctype_id',
        'doctype',
        'documentno',
        'nombre',
        'paterno',
        'materno',
        'email',
        'campaign',
        'accountname',
        'accountpass',
        'token',
    ];

    public function createdby(){    return $this->hasOne(User::class,'id','created_by');}
    public function updatedby(){    return $this->hasOne(User::class,'id','updated_by');}

}
