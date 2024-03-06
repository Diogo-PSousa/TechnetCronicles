<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\ReputationVote;
use App\Events\CommentAdded;
use App\Events\VoteOnContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function sortByDate($order)
    {
        $comments = Comment::orderBy('date_time', $order)->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please log in to comment'], 401);
        }

        $comment = Comment::create([
            'body' => $request->input('comment'),
            'date_time' => now(), 
            'reputation' => 0, 
            'article_id' => $request->input('article_id'),
            'commentcreator_id' => Auth::id()
        ]);

        $comments = Comment::all();

        event(new CommentAdded($request->input('author_id')));

        return response()->json($comments);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->delete();

        $comments = Comment::all();

        return response()->json($comments);
    }

    public function updateComment(Request $request, $id)
    {
        try {
            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json(['error' => 'Comment not found'], 404);
            }

            $newText = $request->input('newText');
            $comment->body = $newText;
            $comment->save();
            
            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getCommentPartial(Comment $comment)
    {
        $html = View::make('partials.comment')->with('comment', $comment)->render();

        return response()->json(['html' => $html]);
    }

    public function vote(Comment $comment, $voteType)
    {
        $id_voter = auth()->id();

        $score = $voteType === 'Upvote' ? 1 : -1;

        $existingVote = CommentVote::whereHas('reputationVote', function ($query) use ($id_voter) {
            $query->where('voter_id', $id_voter);
        })->where('content_id', $comment->comment_id)->first();

        if ($existingVote) {
            if ($existingVote->reputationVote->votetype === $voteType) {
                $existingVote->reputationVote->delete();

                $comment->refresh();
                $currentVoteStatus = 'None';

                return response()->json([
                    'success' => true,
                    'reputation' => $comment->reputation,
                    'currentVoteStatus' => $currentVoteStatus
                ]);
            } else {
                DB::transaction(function () use ($existingVote) {
                    $existingVote->reputationVote->delete();
                });
            }
        }else{
            if($voteType === 'Upvote'){
                event(new VoteOnContent("Upvoted","Comment",$comment->commentcreator_id));
            } else {
                event(new VoteOnContent("Downvoted","Comment",$comment->commentcreator_id));
            }
        }
        DB::beginTransaction();

        try {
            $reputationVote = ReputationVote::create([
                'score' => $score,
                'voter_id' => $id_voter,
                'votetype' => $voteType
            ]);

            $commentVote = new commentVote([
                'vote_id' => $reputationVote->reputationvote_id,
                'content_id' => $comment->comment_id
            ]);
            $commentVote->save();

            DB::commit();


            $comment->refresh();
            $currentVoteStatus = $voteType;

            return response()->json([
                'success' => true,
                'reputation' => $comment->reputation,
                'currentVoteStatus' => $currentVoteStatus
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Voting failed']);
        }
    }

}