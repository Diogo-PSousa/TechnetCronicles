<?php
namespace App\Models;

use App\Models\NewsArticle;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\FileController;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';

    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'reputation',
        'role',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function newsArticles()
    {
        return $this->hasMany(NewsArticle::class, 'creator_id');
    }

    public function articles()
    {
        return $this->hasMany(NewsArticle::class, 'creator_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(NewsArticle::class, 'favorites', 'user_id', 'article_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'commentcreator_id');
    }

    public function notifications()
    {
        return $this->hasMany(VoteNotification::class, 'notified_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'userfollows', 'followed_id', 'follower_id', 'user_id', 'user_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'userfollows', 'follower_id', 'followed_id', 'user_id', 'user_id');
    }

    public function followingTags()
    {
        return $this->belongsToMany(Tag::class, 'userfollowtag', 'user_id', 'tag_id');
    }

    public function getProfileImage() {
        return FileController::get('profile', $this->user_id);
    }

}

class AddRememberTokenToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
}