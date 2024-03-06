<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\UserReport;
use App\Models\ArticleReport;
use App\Models\NewsArticle;
use App\Models\CommentReport;
use App\Models\UnblockAppeal;


class AdminController extends Controller
{
    public function listUsers()
    {

        $user = Auth::user();

        if ($user->role === 2) {

            $users = User::orderBy('username', 'asc')->get();
            return view('pages.adminList', ['users' => $users]);
        } else {
            return abort(403, 'Unauthorized');
        }
    }
    
    public function index()
    {
        $this->authorize('view', User::class);
    }

    public function showTopicProposals()
    {
        $topicProposals = Tag::where('accepted', 0)->get();
        return view('pages.topicProposals', compact('topicProposals'));
    }

    public function deleteTag($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $tag->delete();

        return redirect()->back()->with('success', 'Tag deleted successfully');
    }

    public function acceptTag($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $tag->accepted = 1;
        $tag->save();

        return redirect()->back()->with('success', 'Tag accepted successfully');
    }

    public function showReports(){
        return view('pages.manageReports');
    }

    public function manageReports()
    {
        $userreports = UserReport::all();
        $newsreports = ArticleReport::all(); 
        $commentreports = CommentReport::all();

        foreach ($commentreports as $report) {
            $comment = Comment::find($report->reported_id);
            if ($comment) {
                $report->article_id = $comment->article_id; 
            }
        }
        return view('pages.manageReports', [
            'userreports' => $userreports, 
            'newsreports' => $newsreports, 
            'commentreports' => $commentreports
        ]);
    }
    public function ignoreReportUser($reportId) {
        $report = UserReport::find($reportId);
        if ($report) {
            $report->delete(); 
            return back()->with('success', 'Report ignored successfully.');
        } else {
            return back()->with('error', 'Report not found.');
        }
    }
    public function ignoreReportComment($reportId) {
        $report = CommentReport::find($reportId);
        if ($report) {
            $report->delete(); 
            return back()->with('success', 'Report ignored successfully.');
        } else {
            return back()->with('error', 'Report not found.');
        }
    }
    public function ignoreReportArticle($reportId) {
        $report = ArticleReport::find($reportId);
        if ($report) {
            $report->delete(); 
            return back()->with('success', 'Report ignored successfully.');
        } else {
            return back()->with('error', 'Report not found.');
        }
    }
    
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
    
        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
    
    public function deleteArticle($articleId)
    {
        $news = NewsArticle::findOrFail($articleId);
        $news->delete();
    
        return redirect()->back()->with('success', 'Article deleted successfully');
    }
    
    
    public function respondToBlock($userId) 
{
        $user = User::findOrFail($userId);
        $user->role = 4; 
        $user->save();
    
        $report = UserReport::where('reported_id', $userId)->get();
        if ($report) {         
            foreach($report as $rep){
                $rep->delete(); 
            }
            return back()->with('success', 'User blocked and report solved.');
        } else {
            return back()->with('error', 'Report not found.');
        }
}

    public function swapAdmin($userId)
    {
        $user = User::findOrFail($userId);
        if(Auth::user()->user_id == $user->user_id && $user->role === 2) {
            $user->role = 1;
            $user->save();
            return redirect('/news')->with('success', 'Admin relenquished successfully');
        } else if($user->role === 2){
            $user->role = 1;
        } else if($user->role === 1) {
            $user->role = 2;
        }
        $user->save();

        return redirect()->back()->with('success', 'Role updated successfully');
    }

    public function swapBlock($userId)
    {
        $user = User::findOrFail($userId);
        if($user->role === 4) {
            $user->role = 1;
            $user->save();
            return redirect()->back()->with('success', 'User unblocked successfully');
        } else {
            $user->role = 4;
        }
        $user->save();

        return redirect()->back()->with('success', 'User blocked successfully');
    }

    public function deleteAppeal($AppealId)
    {
        $appeal = UnblockAppeal::findOrFail($AppealId);
        $appeal->delete();

        return redirect()->back()->with('success', 'Tag deleted successfully');
    }

    public function acceptAppeal($AppealId)
    {
        $appeal = UnblockAppeal::findOrFail($AppealId);
        $user = User::findOrFail($appeal->user_id);
        $user->role = 1;
        $user->save();
        $userAppeals = UnblockAppeal::where('user_id', $user->user_id)->get();
        foreach ($userAppeals as $userAppeal) {
            $userAppeal->is_resolved = true;
            $userAppeal->save();
        }

        return redirect()->back()->with('success', 'Tag accepted successfully');
    }


}