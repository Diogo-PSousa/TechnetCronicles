<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteNotification extends Model
{
    protected $primaryKey = 'votenotification_id';
    protected $table = 'votenotification'; 
    public $timestamps = false;

    protected $casts = [
        'date_time' => 'datetime',
    ];

    use HasFactory;

    protected $fillable = [
        'body',
        'date_time',
        'notified_id',
        'vote_id',
    ];

    public function notified()
    {
        return $this->belongsTo(User::class, 'notified_id');
    }

    public function vote()
    {
        return $this->belongsTo(User::class, 'vote_id');
    }
}