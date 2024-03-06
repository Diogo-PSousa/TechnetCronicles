<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class UserFollowTag extends Model
{
    protected $table = 'UserFollowTag';
 
    protected $primaryKey = ['user_id', 'tag_id'];
 
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tag_id',
    ];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
