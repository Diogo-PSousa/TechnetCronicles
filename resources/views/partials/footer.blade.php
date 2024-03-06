<link href="{{ url('css/footer.css') }}" rel="stylesheet">
<footer>
    <div class="footer-content">
        @if ((Auth::check() && auth()->user()->role !== 4) || !Auth::check())
        <div class="centered-content">
            <ul class="footer-menu">
                <li><a href="{{ route('about-us') }}">About Us</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
                <li><a href="{{ route('contacts') }}">Contacts</a></li>
            </ul>
        </div>
        <div class="back-to-top">
            <a href="#top"><i class="fas fa-arrow-up"></i></a>
        </div>
        @endif
    </div>
</footer>