<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class NewsTag extends Model
{
    protected $table = 'newstag'; 

    protected $primaryKey = 'tag_id';  
    protected $fillable = ['tag_id', 'newsarticle_id'];  

    public $timestamps = false;  

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'tag_id');
    }

    public function newsArticle()
    {
        return $this->belongsTo(NewsArticle::class, 'newsarticle_id', 'newsarticle_id');  
    }
}
