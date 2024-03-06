<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('tags.index', compact('tags'));
    }

    public function show(Tag $tag)
    {
        return view('tags.show', compact('tag'));
    }

    public function popularTopics()
    {

        $popularTopics = Tag::withCount('newsArticles')
            ->where('accepted', 1)
            ->orderBy('news_articles_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.articles', ['popularTopics' => $popularTopics]);
    }

    public function createTag(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|unique:tag|max:255',
    ]);
    $tag = new Tag;
    $tag->name = $validatedData['name'];
    $tag->accepted = 0;
    $tag->save();

    return redirect()->back()->with('message', 'Topic proposed successfully.');
}


    public function followTag($tagName)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to follow tags.'], 401);
        }

        $user = Auth::user();

        try {
            $tag = Tag::where('name', $tagName)->first();

            if ($user->followingTags()->where('tag.tag_id', $tag->id)->exists()) {
                return response()->json(['info' => 'You are already following this tag.']);
            }

            $user->followingTags()->attach($tag);

            return response()->json(['success' => 'You are now following the tag: ' . $tag->name]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 404);
        }
    }

    public function unfollowTag($tagName)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to unfollow tags.'], 401);
        }

        $user = Auth::user();

        try {
            $tag = Tag::where('name', $tagName)->firstOrFail();

            if ($user->followingTags()->where('tag.tag_id', $tag->id)->exists()) {
                return response()->json(['info' => 'You are not following this tag.']);
            }

            $user->followingTags()->detach($tag);

            return response()->json(['success' => 'You have unfollowed the tag: ' . $tag->name]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tag not found.'], 404);
        }
    }

}