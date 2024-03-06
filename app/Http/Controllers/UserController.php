<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showProfile($user_id)
    {
        $user = User::withCount(['newsArticles', 'comments', 'followers', 'following', 'followingTags'])
            ->with(['newsArticles', 'comments', 'followers', 'following', 'followingTags'])
            ->where('user_id', $user_id)
            ->firstOrFail();

        return view('pages.profile', [
            'user' => $user,
        ]);
    }

    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $user = User::findOrFail($request->id);
        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_image', $filename, 'public');

        $user->profile_image = $path;
        $user->save();

        return response()->json(['success' => 'Profile picture updated successfully']);
    }

    public function updateBio(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->bio = $request->bio;
        $user->save();

        return redirect()->back()->with('success', 'Bio updated successfully');
    }

    public function updateUsername(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->username = $request->username;
        $user->save();

        return redirect()->back()->with('success', 'Username updated successfully');
    }

    public function followUser($username)
    {
        $userToFollow = User::where('username', $username)->firstOrFail();
        $currentUser = auth()->user();
        $this->authorize('follow', $userToFollow);

        if ($currentUser->following->contains($userToFollow)) {
            return response()->json(['info' => 'You are already following ' . $userToFollow->username]);
        }

        $currentUser->following()->attach($userToFollow);
        return response()->json(['success' => 'You are now following ' . $userToFollow->username]);
    }

    public function unfollowUser($username)
    {
        $userToUnfollow = User::where('username', $username)->firstOrFail();
        $currentUser = auth()->user();
        $this->authorize('unfollow', $userToUnfollow);

        if (!$currentUser->following->contains($userToUnfollow)) {
            return response()->json(['info' => 'You are not following ' . $userToUnfollow->username]);
        }

        $currentUser->following()->detach($userToUnfollow);
        return response()->json(['success' => 'You have unfollowed ' . $userToUnfollow->username]);
    }

    public function deleteSelf($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect('/news')->with('success', 'Account deleted successfully');
    }

}
