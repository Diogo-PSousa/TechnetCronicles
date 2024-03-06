<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $primaryKey = 'tag_id';  

    protected $table = 'tag';  

    public $timestamps = false;
    
    protected $fillable = [
        'name', 'accepted'
    ];

    public function newsArticles()
    {
        return $this->belongsToMany(NewsArticle::class, 'newstag', 'tag_id', 'newsarticle_id');  
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'userfollowtag', 'tag_id', 'user_id');
    }
}
