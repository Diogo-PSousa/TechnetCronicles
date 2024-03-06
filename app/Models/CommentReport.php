<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    protected $table = 'commentreport'; 
    protected $primaryKey = 'commentreport_id';

    public $timestamps = false;

    protected $fillable = [
        'body',
        'date_time',
        'reporter_id',
        'reported_id',
        'article_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'reported_id', 'comment_id');
    }
    

    public function newsArticle()
    {
        return $this->belongsTo(NewsArticle::class, 'article_id', 'newsarticle_id');
    }
}
