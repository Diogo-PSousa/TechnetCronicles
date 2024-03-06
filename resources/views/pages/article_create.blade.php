@extends('layouts.app')
@section('content')
    <div class="article-page">
        <h2>Create a Post</h2>
        <hr>
        <section>
            <form method="POST" action="{{ route('news.post.store') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <section id="title">
                    <label for="new-post-title">Title</label>
                    <input type="text" id="new-post-title" name="title" value="{{ old('title') }}"
                           required>
                    @foreach($errors->get('title') as $error)
                        <li class="error">{{$error}}</li>
                    @endforeach
                </section>
                <section id="tags">
                    <label>Select Tags (optional) :</label><br>
                    @foreach($tags as $tag)
                        @if($tag->accepted == 1)
                        <input type="checkbox" name="tags[]" value="{{ $tag->tag_id }}">{{ $tag->name }}<br>
                        @endif
                    @endforeach
                </section>
                <section id="image">
                    <label>Select Image (optional) :</label><br>
                    <input name="file" type="file">
                </section>
                <section id="body">
                    <label for="editor-body">Body</label>
                    <textarea id="editor-body" name="body">{{ old('body') }}</textarea>
                    @foreach($errors->get('body') as $error)
                        <li class="error">{{$error}}</li>
                    @endforeach
                </section>

                <section>
                    <div>
                        <button type="button" onclick="window.location.href=document.referrer">Cancel
                        </button>
                        <button type="submit">Post</button>
                    </div>
                </section>
            </form>
        </section>
    </div>
@endsection
