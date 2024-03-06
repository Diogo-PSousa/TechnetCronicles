<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    protected $table = 'commentvote';

    protected $primaryKey = ['vote_id', 'content_id'];
    protected $fillable = ['vote_id', 'content_id'];

    public $incrementing = false;
    public $timestamps = false;

    public function reputationVote() {
        return $this->belongsTo(ReputationVote::class, 'vote_id', 'reputationvote_id');
    }

    public function comment() {
        return $this->belongsTo(Comment::class, 'content_id', 'comment_id');
    }
}
