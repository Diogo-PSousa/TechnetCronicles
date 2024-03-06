@extends('layouts.app')

@section('content')
<div class="reset-password">
    <h2>Password Recovery</h2>
    <p>Enter your email address below, and we will send you instructions on how to reset your password.</p>

    <form method="POST" action="{{ route('forget.password.post') }}">
        {{ csrf_field() }}

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" required autofocus>
        @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
        @endif

        <button type="submit" class="button-primary">
            Submit
        </button>
        @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
        @endif
    </form>
</div>
@endsection
