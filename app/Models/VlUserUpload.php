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
        'token',
    ];
    
}
