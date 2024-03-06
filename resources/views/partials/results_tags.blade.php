@if (count($tags) > 0)
<div class="results-section">
    <h2>Tags</h2>
    <div class="user-list-search">
        @foreach ($tags as $tag)
        <a href="{{ route('news.tag', $tag->name) }}" class="user-box">
                {{ $tag->name }}
        </a>
        @endforeach
    </div>
</div>
@else
<p>No tag matches found.</p>
@endif