<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'start',
        'end',
        'subject_id',
        'target'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function students()
    {
        return $this->hasMany(StudentList::class);
    }

    public function sessions()
    {
        return $this->hasMany(SessionByPeriod::class);
    }


}
