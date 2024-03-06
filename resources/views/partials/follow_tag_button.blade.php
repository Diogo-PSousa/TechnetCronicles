@auth
@php
$isFollowingTag = Auth::user()->followingTags->contains($tag);
@endphp
<form id="followTagForm" method="POST">
    @csrf
    @if ($isFollowingTag)
    @method('DELETE')
    @endif
    <button type="button" data-tagname="{{ $tag->name }}"
        class="{{ $isFollowingTag ? 'unfollow-text' : 'follow-text' }}" onclick="submitFollowTagForm(event)">
        {{ $isFollowingTag ? 'Following' : 'Follow' }}
    </button>
</form>
@endauth