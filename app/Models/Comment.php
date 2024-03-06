<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment'; 
    protected $primaryKey = 'comment_id';

    public $timestamps = false;

    protected $fillable = [
        'body',
        'date_time',
        'reputation',
        'article_id',
        'commentcreator_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',  
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'commentcreator_id', 'user_id');
    }

    public function newsArticle()
    {
        return $this->belongsTo(NewsArticle::class, 'article_id', 'newsarticle_id');
    }

    public function votes()
    {
        return $this->belongsTo(CommentVote::class, 'comment_id', 'content_id');
    }
    
}
