<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Outpass extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'outpass_date',
        'outpass_from',
        'outpass_to',
        "student_id",
        "status"
    ];

    public function students()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public static function getOutpassessWithStudents()
    {
        return self::with("students")->get();
    }
}
