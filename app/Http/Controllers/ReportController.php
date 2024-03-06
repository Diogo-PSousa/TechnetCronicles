<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;
use App\Models\ArticleReport;
use App\Models\User;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function reportuser(Request $request, $user_id)
    {
        $report = new UserReport();
        $report->body = $request->input('body');
        $report->reporter_id = Auth::id();
        $report->reported_id = $user_id;
        $report->date_time = now();
        $report->save();

        return back()->with('success', 'User reported successfully.');
    }

    public function reportarticle(Request $request, $article_id)
    {
        $report = new ArticleReport();
        $report->body = $request->input('body');
        $report->reporter_id = Auth::id();
        $report->reported_id = $article_id;
        $report->date_time = now();
        $report->save();

        return back()->with('success', 'Article reported successfully.');
    }

    public function reportcomment(Request $request, $comment_id)
    {
        $report = new CommentReport();
        $report->body = $request->input('body');
        $report->reporter_id = Auth::id();
        $report->reported_id = $comment_id;
        $report->date_time = now();
        $report->save();

        return back()->with('success', 'Comment reported successfully.');
    }

    public function blockUser($userId) {
        $user = User::find($userId);
        if ($user) {
            $user->role = 4; 
            $user->save();

            $report = UserReport::where('reported_id', $userId)->first();
            if ($report) {
                $report->delete(); 
            }
    
            return back()->with('success', 'User blocked successfully.');
        } else {
            return back()->with('error', 'User not found.');
        }
    }
}
