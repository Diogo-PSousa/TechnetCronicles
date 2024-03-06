<?php

namespace App\Http\Controllers;

use App\Models\CommentVote;
use App\Models\NewsArticle;
use App\Models\NewsTag;
use App\Events\VoteOnContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use App\Models\ArticleVote;
use App\Models\ReputationVote;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;




class NewsArticleController extends Controller
{

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'top');

        if (Auth::check()) {
            $user = Auth::user();

            if ($sort === 'top') {
                $articles = NewsArticle::orderBy('reputation', 'desc');
            } elseif ($sort === 'recent') {
                $articles = NewsArticle::orderBy('date_time', 'desc');
            } elseif ($sort === 'followed') {
                $followingUserIds = $user->following()->pluck('user_id');

                $followingTagIds = $user->followingTags()->pluck('tag.tag_id');

                $articles = NewsArticle::where(function ($query) use ($followingUserIds, $followingTagIds) {
                    $query->whereIn('creator_id', $followingUserIds)
                        ->orWhereExists(function ($subQuery) use ($followingTagIds) {
                            $subQuery->select(DB::raw(1))
                                ->from('newstag')
                                ->whereRaw('newstag.newsarticle_id = newsarticle.newsarticle_id')
                                ->whereIn('newstag.tag_id', $followingTagIds);
                        });
                })->orderBy('date_time', 'desc');

            } else {
                $articles = NewsArticle::orderBy('reputation', 'desc');
            }
        } else {
            if ($sort === 'top') {
                $articles = NewsArticle::orderBy('reputation', 'desc');
            } elseif ($sort === 'recent') {
                $articles = NewsArticle::orderBy('date_time', 'desc');
            } else {
                $articles = NewsArticle::orderBy('reputation', 'desc');
            }
        }

        $articles = $articles->paginate(10);

        if (Auth::check()) {

            $userVotes = ArticleVote::whereHas('reputationVote', function ($query) {
                $query->where('voter_id', Auth::id());
            })->get()->pluck('reputationVote.votetype', 'content_id');

            foreach ($articles as $article) {
                $article->userVoteType = $userVotes[$article->newsarticle_id] ?? null;
            }
        }

        if ($request->ajax()) {
            return view('partials.articles', compact('articles'));
        }

        $popularTags = Tag::withCount('newsArticles')
            ->orderBy('news_articles_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.articles', ['articles' => $articles, 'popularTags' => $popularTags]);
    }

    public function showByTag(Request $request, $tag)
    {
        $tag = Tag::where('name', $tag)->firstOrFail();

        $sort = $request->query('sort', 'top'); 

        $articles = $tag->newsArticles();

        if (Auth::check()) {
            $user = Auth::user();

            if ($sort === 'top') {
                $articles->orderBy('reputation', 'desc');
            } elseif ($sort === 'recent') {
                $articles->orderBy('date_time', 'desc');
            } elseif ($sort === 'followed') {
                $followingUserIds = $user->following()->pluck('user_id');
                $articles->whereIn('creator_id', $followingUserIds)->orderBy('date_time', 'desc');
            } else {
                $articles->orderBy('reputation', 'desc');
            }
        } else {
            if ($sort === 'top') {
                $articles->orderBy('reputation', 'desc');
            } elseif ($sort === 'recent') {
                $articles->orderBy('date_time', 'desc');
            } else {
                $articles->orderBy('reputation', 'desc');
            }
        }

        $articles = $articles->paginate(10);

        if (Auth::check()) {

            $userVotes = ArticleVote::whereHas('reputationVote', function ($query) {
                $query->where('voter_id', Auth::id());
            })->get()->pluck('reputationVote.votetype', 'content_id');

            foreach ($articles as $article) {
                $article->userVoteType = $userVotes[$article->newsarticle_id] ?? null;
            }
        }

        if ($request->ajax()) {
            return view('partials.articles', compact('articles'));
        }

        $popularTags = Tag::withCount('newsArticles')
            ->orderBy('news_articles_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.news_tag', compact('articles', 'tag', 'popularTags'));
    }



    public function show(NewsArticle $article)
    {
        if (Auth::check()) {
            $userVotes = ArticleVote::whereHas('reputationVote', function ($query) {
                $query->where('voter_id', Auth::id());
            })->get()->pluck('reputationVote.votetype', 'content_id');

            $article->userVoteType = $userVotes[$article->newsarticle_id] ?? null;
        }
        \Log::error("comments" . $article->comments);

        $comments = $article->comments;

        if (Auth::check()) {
            $userCommentVotes = CommentVote::whereHas('reputationVote', function ($query) {
                $query->where('voter_id', Auth::id());
            })->get()->pluck('reputationVote.votetype', 'content_id');

            foreach ($comments as $comment) {
                $comment->userVoteType = $userCommentVotes[$comment->comment_id] ?? null;
            }
        }

        return view('pages.article', ['article' => $article, 'comments' => $comments]);
    }

    public function create()
    {
        $tags = Tag::all();
        return view('pages.article_create',['tags' => $tags]);
    }

    public function store(Request $request)
    {

        try {

            DB::beginTransaction();

            $article = new NewsArticle();
            $article->title = $request->input('title');
            $article->body = $request->input('body');
            $article->date_time = now(); 
            $article->creator_id = Auth::id(); 
            if ($request->has('file')) {
                $file = $request->file('file');
                $type = "post";
                $fileName = $file->hashName();
                $article->article_image = $fileName;
                $file->storeAs($type, $fileName,'TechNetChronicles');
            }
            $article->save();

            if ($request->has('tags')) {
                $selectedTags = $request->input('tags');
                foreach ($selectedTags as $tagId) {
                    $newstag = new NewsTag();
                    $newstag->tag_id = $tagId;
                    $newstag->newsarticle_id = $article->newsarticle_id;
                    $newstag->save();
                }
            }

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while deleting the news article: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('news.post', $article);
    }

    public function edit(NewsArticle $article)
    {
        $tags = Tag::all();
        return view('pages.article_edit', ['article' => $article,'tags' => $tags]);
    }

    public function update(Request $request, NewsArticle $article)
    {

        try {

            DB::beginTransaction();

            $article->title = $request->input('title');
            $article->body = $request->input('body');

            if ($request->has('file')) {
                $file = $request->file('file');
                $type = "post";
                $fileName = $file->hashName();
                $file = $request->file('file');
                $type = "post";
                $NewFileName = $file->hashName();
                $existingFileName = NewsArticle::find($article->newsarticle_id)->article_image;
                Storage::disk('TechNetChronicles')->delete($type . '/' . $existingFileName);
                $article->article_image = $NewFileName;
                $file->storeAs($type, $fileName,'TechNetChronicles');
            }
            NewsTag::where('newsarticle_id', $article->newsarticle_id)->delete();
            
            if ($request->has('tags')) {
                $selectedTags = $request->input('tags');

                foreach ($selectedTags as $tagId) {
                    $newstag = new NewsTag();
                    $newstag->tag_id = $tagId;
                    $newstag->newsarticle_id = $article->newsarticle_id;
                    $newstag->save();
                }
            }
            
            $article->save();

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while deleting the news article: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('news.post', $article);
    }

    public function delete(NewsArticle $article)
    {
        try {

            DB::beginTransaction();

            $article->delete();

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while deleting the news article: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('news.index');
    }

    public function vote(NewsArticle $article, $voteType)
    {
        $id_voter = auth()->id();

        $score = $voteType === 'Upvote' ? 1 : -1;

        $existingVote = ArticleVote::whereHas('reputationVote', function ($query) use ($id_voter) {
            $query->where('voter_id', $id_voter);
        })->where('content_id', $article->newsarticle_id)->first();

        if ($existingVote) {
            if ($existingVote->reputationVote->votetype === $voteType) {
                $existingVote->reputationVote->delete();

                $article->refresh();
                $currentVoteStatus = 'None';

                return response()->json([
                    'success' => true,
                    'reputation' => $article->reputation,
                    'currentVoteStatus' => $currentVoteStatus
                ]);
            } else {
                DB::transaction(function () use ($existingVote) {
                    $existingVote->reputationVote->delete();
                });
            }
        }else{
            if($voteType === 'Upvote'){
                event(new VoteOnContent("Upvoted","Post",$article->creator_id));
            } else {
                event(new VoteOnContent("Downvoted","Post",$article->creator_id));
            }
        }

        DB::beginTransaction();

        try {
            $reputationVote = ReputationVote::create([
                'score' => $score,
                'voter_id' => $id_voter,
                'votetype' => $voteType
            ]);

            $articleVote = new ArticleVote([
                'vote_id' => $reputationVote->reputationvote_id,
                'content_id' => $article->newsarticle_id
            ]);
            $articleVote->save();

            DB::commit();

            $article->refresh();
            $currentVoteStatus = $voteType;

            return response()->json([
                'success' => true,
                'reputation' => $article->reputation,
                'currentVoteStatus' => $currentVoteStatus
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Voting failed']);
        }
    }

}
