@if (count($accounts) > 0)
<div class="results-section">
    <h2>Users</h2>
    <div class="user-list-search">
        @foreach ($accounts as $account)   
            <a href="/user/{{ $account->user_id }}" class="user-box">
                <img src="{{ asset('img/pessoa.png') }}" alt="User Image">
                {{ $account->username }}
            </a>
        @endforeach
    </div>
</div>
@else
<p>No user matches found.</p>
@endif
