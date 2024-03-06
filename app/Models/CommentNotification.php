<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Model
{
    protected $primaryKey = 'commentnotification_id';
    protected $table = 'commentnotification'; 
    public $timestamps = false;

    protected $casts = [
        'date_time' => 'datetime',
    ];

    use HasFactory;

    protected $fillable = [
        'body',
        'date_time',
        'notified_id',
        'comment_id',
    ];

    public function notified()
    {
        return $this->belongsTo(User::class, 'notified_id');
    }

    public function comment()
    {
        return $this->belongsTo(User::class, 'comment_id');
    }
}