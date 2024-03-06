@extends('layouts.app')

@section('content')
    <div class="container-list">
        <h1>
            Appeal for Unblock
        </h1>
        <form action="/blocked/appeal" method="post" onsubmit ="return confirm('Are you sure you want to submit this as your appeal?');">
            @csrf
            @method('POST')
            <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
            <label for="appeal_text">Appeal Text</label>
            <textarea class="form-control" id="appeal_text" name="appeal_text" rows="4" placeholder="Enter your appeal text"></textarea>
            <button type="submit">Submit Appeal</button>
        </form>
        <form action="/user/{{ Auth::user()->user_id}}/delete" method="post">
            @csrf
            @method('DELETE')
            <button type="submit"
            onclick="return confirm('Are you sure you want to delete yourself? This action cannot be undone. Confirm own deletion?');">Delete
                Account</button>
        </form>
    </div>
@endsection


