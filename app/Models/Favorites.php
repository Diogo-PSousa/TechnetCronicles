<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = 'favorites';  

    protected $primaryKey = 'article_id'; 
    protected $fillable = ['user_id', 'article_id'];  

    public $timestamps = false; 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function newsArticle()
    {
        return $this->belongsToMany(NewsArticle::class, 'favorites', 'user_id', 'article_id');
    }
}
