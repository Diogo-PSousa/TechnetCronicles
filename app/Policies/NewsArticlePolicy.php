<?php

use App\Models\User;
use App\Models\NewsArticle;

class NewsArticlePolicy
{
    public function view(User $user, NewsArticle $newsArticle)
    {
 
        return true;
    }
    public function delete(User $user,NewsArticle $article)
    {
        return $user->isAdmin() || $user->id === $article->user_id;
    }
    public function edit(User $user,NewsArticle $article)
    {
        return $user->isAdmin() || $user->id === $article->user_id;
    }
    public function create(User $user,NewsArticle $article)
    {
        return true;
    }
}
