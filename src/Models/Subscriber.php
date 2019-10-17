<?php

namespace ArchintelDev\SesCompanion\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'firstname', 'lastname', 'email', 'client_uuid'
    ]; 
}
