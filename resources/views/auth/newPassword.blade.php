@extends('layouts.app')

@section('content')
<div class="reset-password">
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('reset.password.post') }}">
        {{ csrf_field() }}

        <input type="text" name="token" hidden value="{{$token}}">

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" required autofocus>
        @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
        @endif

        <label for="password">Enter New Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
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