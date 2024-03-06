<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;

class SearchController extends Controller
{
    public function searchResults(Request $request)
    {
        $query = $request['query'];
        $sortType = $request['sortType'];

        $accounts = collect();
        $news = collect();
        $comments = collect();
        $tags = collect();

        

        if (trim($query) === '') {
            return view('pages.searchResults', ['error' => "Error: Empty Search", 'accounts' => $accounts, 'news' => $news, 'comments' => $comments, 'tags'=>$tags]);
        }


        if (starts_with($query, '"') && ends_with($query, '"')) {
            $exactQuery = trim($query, '"');
            
            $accounts = exactSearchUsers($exactQuery);
            $news = exactSearchNews($exactQuery);
            $comments = exactSearchComments($exactQuery);
            $tags = exactSearchTags($exactQuery);

        } 
        
        else {

            if (preg_match('/^\d{1,2}[\/\-]\d{1,2}(?:[\/\-]\d{2,4})?$/', $query)) {

                $searchDate = searchByDate($query);
                
                $news = NewsArticle::whereDate('date_time', $searchDate)->get();
                $comments = Comment::whereDate('date_time', $searchDate)->get();

            }

            else{
                    $accounts = searchUsers($query);
                    $news = searchNews($query);
                    $comments = searchComments($query);
                    $tags = searchTags($query);
            }
        }

        switch ($sortType) {
            case 'newest':
                $accounts = $accounts->sortByDesc('email_verified_at');
                $news = $news->sortByDesc('date_time');
                $comments = $comments->sortByDesc('date_time');
                break;
            case 'voted':
                $accounts = $accounts->sortByDesc('reputation');
                $news = $news->sortByDesc('reputation');
                $comments = $comments->sortByDesc('reputation');
                break;

        }
        
        if ($request->ajax()) {
            \Log::error("lol");
            return view('partials.searchResults', ['accounts' => $accounts, 'news' => $news, 'comments' => $comments, 'tags'=>$tags, 'query' => $query]);
        }
        return view('pages.searchResults', ['accounts' => $accounts, 'news' => $news, 'comments' => $comments, 'tags' => $tags, 'query' => $query]);
    }
}



function starts_with($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function ends_with($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

function searchUsers($query){
    $accounts = User::where(function($innerQuery) use ($query) {
        $innerQuery->where('username', 'ILIKE', '%'.$query.'%')
                ->orWhere('email', 'ILIKE', '%'.$query.'%')
                ->orWhere('bio', 'ILIKE', '%'.$query.'%');
    })->get();

    return $accounts;

}

function searchNews($query){
    $news = NewsArticle::where(function($innerQuery) use ($query) {
        $innerQuery->where('title', 'ILIKE', '%'.$query.'%')
                ->orWhere('body', 'ILIKE', '%'.$query.'%');
    })->get();

    return $news;

}

function searchComments($query){
    $comments = strlen($query) ? Comment::where('body', 'ILIKE', '%'.$query.'%')->get() : [];
    return $comments;

}

function searchTags($query){
    $tags= Tag::where(function($innerQuery) use ($query) {
        $innerQuery->where('name', 'ILIKE', '%'.$query.'%')
            ->where('accepted', 1);
    })->get();
    return $tags;
}

function exactSearchUsers($query){
    $accounts = User::where('username', '=', $query)
    ->orWhere('email', '=', $query)
    ->orWhere('bio', '=', $query)
    ->get();

    return $accounts;

}

function exactSearchNews($query){
    $news = NewsArticle::where('title', '=', $query)
    ->orWhere('body', '=', $query)
    ->get();

    return $news;

}

function exactSearchComments($query){
    $comments = Comment::where('body', '=', $query)->get();
    
    return $comments;

}


function exactSearchTags($query){
    $tags = Tag::where('name', '=', $query)->get();
    
    return $tags;

}


function searchByDate($query){
    $dateParts = preg_split('/[\/\-]/', $query); 
    $day = $dateParts[0];
    $month = $dateParts[1];
    $year = isset($dateParts[2]) ? $dateParts[2] : date('Y');
    
    if(strlen($year) === 2) {
        $year = '20' . $year;
    }

    $searchDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

    return $searchDate;

}
