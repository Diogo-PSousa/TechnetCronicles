<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReputationVote extends Model
{

    protected $table = 'reputationvote';

    protected $primaryKey = 'reputationvote_id';

    public $timestamps = false;

    protected $fillable = ['score', 'voter_id', 'votetype'];

    public function user() {
        return $this->belongsTo(User::class, 'voter_id', 'user_id');
    }

    public function articleVote() {
        return $this->hasOne(ArticleVote::class, 'vote_id', 'reputationVote_id');
    }

    public function commentVote() {
        return $this->hasOne(CommentVote::class, 'vote_id', 'reputationVote_id');
    }
}
