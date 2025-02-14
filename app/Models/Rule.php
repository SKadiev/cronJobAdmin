<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{

    public $timestamps = false;

    protected $fillable = ['name', 'from', 'to'];
    
    use HasFactory;
}
