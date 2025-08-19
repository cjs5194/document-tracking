<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Division extends Model
{
    public function users()
    {
         return $this->belongsToMany(User::class, 'division_user'); // pivot table
    }
}
