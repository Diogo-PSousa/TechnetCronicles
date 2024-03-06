@extends('layouts.app')

@section('content')
<div class="notifications-page">
    <h1> Personal Notifications</h1>
    @foreach($notifications as $notification)
        <div class="notification-item">
            <p>{{ $notification->body }}</p>
            @if($notification->date_time->diffInHours(now()) < 24) 
                <p>{{ $notification->date_time->format('H:i') }}</p> 
            @else
                <p>{{ $notification->date_time->format('d/m/Y H:i') }}</p>  
            @endif
        </div>
    @endforeach
</div>
@endsection