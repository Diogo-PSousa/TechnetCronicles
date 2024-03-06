@extends('layouts.app')

@section('content')
<a class="adminbutton" href="/admin">Users List</a>
<a class="adminbutton2" href="/admin/unblockAppeals">Unblock Appeals</a>
<a class="adminbutton3" href="/admin/manageReports">Reports</a>
<div class="container-list">
    <h1>Topic Proposals</h1>
    <div class="user-list">
        @foreach($topicProposals as $proposal)
            <div class="user-card">
                <div class="user-box">
                    {{ $proposal->name }}
                </div>
                <form action="/admin/topicProposals/accept/{{ $proposal->tag_id }}" method="post" onsubmit="return confirm('Are you sure you want to accept this tag? This action cannot be undone.');">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Accept</button>
                </form>
                <form action="/admin/topicProposals/delete/{{ $proposal->tag_id }}" method="post" onsubmit="return confirm('Are you sure you want to delete this tag? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Decline</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection