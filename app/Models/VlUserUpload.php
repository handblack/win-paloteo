<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VlUserUpload extends Model
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
        'documentno',
        'created_by',
        'datetrx',
        'mode',
        'isactive',
        'filename',
        'size',
        'token',
    ];

    public function lines(){
        return $this->hasMany(VlUserUploadLine::class,'user_upload_id','id');
    }
    public function createdby(){    return $this->hasOne(User::class,'id','created_by');}
    public function updatedby(){    return $this->hasOne(User::class,'id','updated_by');}
    
}
