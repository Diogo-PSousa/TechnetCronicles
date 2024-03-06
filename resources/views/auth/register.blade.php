@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}" class="register_form">
    {{ csrf_field() }}

    <label for="username">Username</label> <!-- Updated to 'username' -->
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus> <!-- Updated to 'username' -->
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password_confirmation">Confirm Password</label> <!-- Updated to 'password_confirmation' -->
    <input id="password_confirmation" type="password" name="password_confirmation" required> <!-- Updated to 'password_confirmation' -->

    <button type="submit">
      Register
    </button>
    <a class="button button-outline sans-serif" href="{{ route('login') }}">Login</a>
</form>
@endsection