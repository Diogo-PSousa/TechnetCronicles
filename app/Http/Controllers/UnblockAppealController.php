<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnblockAppeal;
use App\Models\User;

class UnblockAppealController extends Controller
{
    public function unblockAppeal(Request $request)
    {
        \Log::error("ola");
        $request->validate([
            'user_id' => 'required|exists:users,user_id', 
            'appeal_text' => 'required|string',
        ]);
        $unblockAppeal = new UnblockAppeal([
            'user_id' => $request->user_id,
            'appeal_text' => $request->appeal_text,
            'appeal_date' => now(),
            'is_resolved' => false, 
        ]);

        $unblockAppeal->save();

        return redirect()->back()->with('success', 'Unblock appeal submitted successfully.');
    }

    public function showUnblockAppeals()
    {
        $unblockAppeal = UnblockAppeal::all();
        $usernames = User::whereIn('user_id', $unblockAppeal->pluck('user_id'))->pluck('username', 'user_id');
        return view('pages.unblockAppeal', compact('unblockAppeal' , 'usernames'));
    }
}
