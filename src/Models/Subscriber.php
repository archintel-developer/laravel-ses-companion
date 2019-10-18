<?php

namespace ArchintelDev\SesCompanion\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    protected $guarded = [];

    protected $fillable = [
        'firstname', 'lastname', 'email', 'client_id', 'group_id'
    ]; 
}
