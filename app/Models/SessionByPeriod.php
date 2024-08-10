<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionByPeriod extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'session_by_periods';

    protected $fillable = [
        'session_id',
        'period_id'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
