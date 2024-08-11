<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VlOrderLine extends Model
{
    use HasFactory;

    public function createdby(){    return $this->hasOne(User::class,'id','created_by');}
    public function updatedby(){    return $this->hasOne(User::class,'id','updated_by');}
    
}
