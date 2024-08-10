<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'start',
        'end',
        'target',
        'session_id',
        'is_recurring',
        'duration',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    protected $casts = [
        'target' => 'integer',
        'is_recurring' => 'boolean',
        'duration' => 'integer',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
