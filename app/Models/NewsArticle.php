<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Auth;

class NewsArticle extends Model
{
    protected $primaryKey = 'newsarticle_id';
    protected $table = 'newsarticle'; 
    public $timestamps = false;

    protected $casts = [
        'date_time' => 'datetime',
    ];

    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'date_time',
        'reputation',
        'creator_id',
        'article_image'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'newstag', 'newsarticle_id', 'tag_id'); 
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id', 'newsarticle_id');
    }
    public function articleVote()
    {
        return $this->hasMany(ArticleVote::class, 'content_id', 'newsarticle_id');
    }

    public function getPostImage() {
        return FileController::get('post', $this->newsarticle_id);
    }

    public function favorites()
    {
        return $this->hasOne(Favorites::class, 'article_id')
            ->where('user_id', Auth::id());
    }
}
