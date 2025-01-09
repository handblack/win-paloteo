<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuzonVicidialInboundGroups extends Model
{
    use HasFactory;
    protected $connection = 'buzon';
    protected $table = 'vicidial_inbound_groups';
}
