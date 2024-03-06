<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReport extends Model
{
    protected $table = 'articlereport'; 
    protected $primaryKey = 'articlereport_id';

    public $timestamps = false;

    protected $fillable = [
        'body',
        'date_time',
        'reporter_id',
        'reported_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function newsarticle()
    {
        return $this->belongsTo(NewsArticle::class, 'reported_id', 'newsarticle_id');
    }
}
