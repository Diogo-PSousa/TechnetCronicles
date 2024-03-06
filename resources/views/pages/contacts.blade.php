@extends('layouts.app')

@section('content')
<div class="team-container">
    <h2>Contact Us</h2>
    <div class="contact-form">
        <p> Fill out this form with your questions and/or needs, and we'll be sure to reach out to you ASAP!</p>
        <form id="contactForm" action="{{ route('send.email') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">Your E-mail:</label>
                @if(Auth::check())
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                @else
                <input type="email" id="email" name="email" required>
                @endif
            </div>

            <div class="form-group">
                <label for="title">Subject:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <button type="submit">Send Message</button>
        </form>
        <p class="success" style="display:none;"></p>
        <p class="error-message" style="display:none; color: red;"></p>
    </div>
    <div class="contacts-help-text">
        <p> If you need any more assistance, consider reading the <a href="{{ route('faq') }}">FAQs</a>, or check out
            our other e-mails in the <a href="{{ route('about-us') }}">About Us</a> section. </p>
    </div>
</div>
<script src="{{ asset('js/contacts.js') }}"></script>
@endsection