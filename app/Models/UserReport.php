<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $table = 'userreport'; 
    protected $primaryKey = 'userreport_id';

    public $timestamps = false;

    protected $fillable = [
        'body',
        'date_time',
        'reporter_id',
        'reported_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function reported()
    {
        return $this->belongsTo(User::class, 'reported_id', 'user_id');
    }
}
