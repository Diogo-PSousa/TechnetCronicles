@extends('layouts.app')
@section('content')
<div class="article-page">
    <h2>Edit article</h2>
    <hr>
    <section>
        <form method="POST" action="{{ route('news.post.update', ['article' => $article->newsarticle_id]) }}"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PATCH')
            <section id="title">
                <label for="new-article-title">Title</label>
                <input type="text" id="new-article-title" name="title" value="{{ old('title', $article->title) }}"
                    required>
                @foreach($errors->get('title') as $error)
                <li class="error">{{$error}}</li>
                @endforeach
            </section>
            <section id="tags">
                <label>Select Tags:</label><br>
                @foreach($tags as $tag)
                @if($tag->accepted == 1)
                @if ($article->tags->pluck('tag_id')->contains($tag->tag_id))
                <input type="checkbox" name="tags[]" value="{{ $tag->tag_id }}" checked>{{ $tag->name }}<br>
                @else
                <input type="checkbox" name="tags[]" value="{{ $tag->tag_id }}">{{ $tag->name }}<br>
                @endif
                @endif
                @endforeach
            </section>
            <section id="image">
                <input name="file" type="file">
            </section>
            <section id="body">
                <label for="editor-body">Body</label>
                <textarea id="editor-body" name="body">{{ old('body', $article->body) }}</textarea>
                @foreach($errors->get('body') as $error)
                <li class="error">{{$error}}</li>
                @endforeach
            </section>
            <section id="buttons">
                <div>
                    <button type="button" onclick="window.location.href=document.referrer">Cancel
                    </button>
                    <button type="submit">Submit</button>
                </div>
            </section>
        </form>
    </section>
</div>
@endsection