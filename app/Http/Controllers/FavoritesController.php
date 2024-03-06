<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Http\Request;
use App\Models\NewsArticle;

class FavoritesController extends Controller
{
    public function toggleFavorite(Request $request, NewsArticle $article)
    {
        $userId = auth()->id(); 

        $favorite = Favorites::where('user_id', $userId)
                            ->where('article_id', $article->newsarticle_id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Bookmark deleted');
        } else {
            Favorites::create([
                'user_id' => $userId,
                'article_id' => $article->newsarticle_id,
            ]);
            return redirect()->back()->with('success', 'Bookmarked post');
        }
    }
}
