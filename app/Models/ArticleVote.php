<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleVote extends Model
{
    protected $table = 'articlevote';

    protected $primaryKey = ['vote_id', 'content_id'];
    protected $fillable = ['vote_id', 'content_id'];


    public $incrementing = false;
    public $timestamps = false;

    public function reputationVote() {
        return $this->belongsTo(ReputationVote::class, 'vote_id', 'reputationvote_id');
    }

    public function newsArticle() {
        return $this->belongsTo(NewsArticle::class, 'content_id', 'newsArticle_id');
    }
}
