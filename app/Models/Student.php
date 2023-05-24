<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use HasApiTokens,  HasFactory;
    use Notifiable;
    use HasRoles;
    protected $guard = 'students';
    protected $fillable = [
        'name',
        'email',
        'password',
        'block',
        'branch',
        'course',
        'phoneNumber',
        'registrationNo',
        'roomNo'
    ];
}
