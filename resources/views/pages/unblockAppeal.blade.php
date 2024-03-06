@extends('layouts.app')

@section('content')
<a class="adminbutton" href="/admin">Users List</a>
<a class="adminbutton2" href="/admin/topicProposals">Topic Proposals</a>
<a class="adminbutton3" href="/admin/manageReports">Reports</a>
<div class="container-list">
    <h1>Unblock Appeals</h1>
    <div class="user-list">
    @foreach($unblockAppeal as $appeal)
    @if($appeal->is_resolved == false)
    <div class="unblock-appeal">
        <div class="user-card">
            <div class="user-box">
                {{ $usernames[$appeal->user_id] }}
            </div>
            Status: Unsolved
            <form action="/admin/unblockAppeals/accept/{{ $appeal->appeal_id }}" method="post" onsubmit="return confirm('Are you sure you want to accept this appeal?');">
                @csrf
                @method('PATCH')
                <button type="submit">Accept</button>
            </form>
            <form action="/admin/unblockAppeals/delete/{{ $appeal->appeal_id }}" method="post" onsubmit="return confirm('Are you sure you want to decline and delete this appeal? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit">Decline</button>
            </form>
        </div>
        <div class="appeal-text">
            {{ $appeal->appeal_text }}
        </div>
    </div>
    @else
    <div class="unblock-appeal">
        <div class="user-card">
            <div class="user-box">
                {{ $usernames[$appeal->user_id] }}
            </div>
            Status : Accepted
            <form action="/admin/unblockAppeals/delete/{{ $appeal->appeal_id }}" method="post" onsubmit="return confirm('Are you sure you want to delete this appeal? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit">Delete Appeal</button>
            </form>
        </div>
        <div class="appeal-text">
            {{ $appeal->appeal_text }}
        </div>
    </div>
    @endif
    @endforeach
    </div>
</div>
@endsection