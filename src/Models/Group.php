<?php

namespace ArchintelDev\SesCompanion\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $guarded = [];
    
    protected $fillable = [
        'name', 'slug', 'client_id'
    ];
}
