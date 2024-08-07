<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDisponibility extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'table_students_disponibility';

    protected $fillable = [
        'student_id',
        'day',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $casts = [
        'day' => 'string',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


}
