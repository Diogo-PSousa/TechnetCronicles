@extends('layouts.app')

@section('content')
<a class="adminbutton" href="/admin/topicProposals">Topic Proposals</a>
<a class="adminbutton2" href="/admin/unblockAppeals">Unblock Appeals</a>
<a class="adminbutton3" href="/admin/manageReports">Reports</a>
<div class="container-list">
    <h1>All Users</h1>
    <div class="user-list">
        @foreach($users as $user)
        <div class="user-card">
            <a href="/user/{{ $user->user_id }}" class="user-box">
                @if($user->profile_image)
                <img src="{{ $user->getProfileImage() }}">
                @else
                <img src="{{ asset('img/pessoa.png') }}" alt="User Image">
                @endif
                {{ $user->username }}
            </a>
            <form action="/admin/swapBlock/{{$user->user_id}}" method="post" onsubmit="return handleFormSubmitPatch(event, 'swapBlock', {{$user->user_id}})">
                @csrf
                @method('PATCH')
                @if ($user->user_id == Auth::user()->user_id)
                @elseif ($user->role == 4)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to unblock this user? This action can be undone. Confirm unblock?');">Unblock</button>
                @else
                <button type="submit"
                    onclick="return confirm('Are you sure you want to block this user? This action can be undone. Confirm block?');">Block</button>
                @endif
            </form>
            <form action="/admin/swapAdmin/{{$user->user_id}}" method="post" onsubmit="return handleFormSubmitPatch(event, 'swapAdmin', {{$user->user_id}})">
                @csrf
                @method('PATCH')
                @if ($user->user_id == Auth::user()->user_id && $user->role == 2)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to revoke your admin privileges? This action cannot be undone. Confirm admin rights relinquish?');">Relenquish Admin</button>
                @elseif ($user->role == 1)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to give this user admin rights? This action can be undone. Confirm admin atribution?');">Make Admin</button>
                @elseif ($user->role == 2)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to take this user admin rights? This action can be undone. Confirm admin rights revocation?');">Revoke Admin</button>
                @endif
            </form>
            <form action="/admin/delete/{{$user->user_id}}" method="post" onsubmit="return handleFormDelete(event, 'delete', {{$user->user_id}})">
                @csrf
                @method('DELETE')
                @if ($user->user_id == Auth::user()->user_id)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to delete yourself? This action cannot be undone. Confirm own deletion?');">Delete
                    myself</button>
                @elseif ($user->role == 2)
                <button type="submit"
                    onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone. Confirm admin deletion?');">Delete
                    admin</button>
                @else
                <button type="submit"
                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">Delete
                    User</button>
                @endif
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection