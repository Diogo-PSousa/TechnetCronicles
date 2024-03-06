<link href="{{ url('css/header.css') }}" rel="stylesheet">
<header>
    <div class="header-content">
        <section class="p-menu1">
            <nav id="navbar" class="navigation" role="navigation">
                <input id="toggle1" type="checkbox" />
                <label class="hamburger1" for="toggle1">
                    <div class="top"></div>
                    <div class="meat"></div>
                    <div class="bottom"></div>
                </label>
                <nav class="menu1">
                    @if (auth()->check() && (auth()->user()->role == 2 ))
                    <a class="sans-serif link1" href="/admin">Manage Site</a>
                    @endif
                    @if (Auth::check())
                    <a href="{{ url('/notifications') }}"><i class="fa-solid fa-bell"></i></a>
                    @endif
                    <a class="sans-serif link1" href="{{ url('/search') }}"> Search <i class="fas fa-search"></i></a>
                    @if (Auth::check())
                    <a class="sans-serif link1" href="/user/{{ Auth::check() ? Auth::user()->user_id : '' }}">
                        Profile</a>
                    <a class="button sans-serif link1" href="{{ url('/logout') }}">Logout</a>
                    @else
                    <a class="sans-serif link1" href="{{ url('/login') }}">Login</a>
                    @endif
                </nav>
            </nav>
        </section>
        @if (auth()->check() && (auth()->user()->role == 2 ))
        <div class="header-left">
            <a class="sans-serif" href="/admin"><i class="fas fa-user"></i> Manage Site</a>
        </div>
        @endif

        <div class="centered-content">
            <h1 class="logo sans-serif"><a href="{{ url('/news') }}">TechNet<br>Chronicles</a></h1>
        </div>
        <div class="header-right">
            @if (Auth::check())
            <a href="{{ url('/notifications') }}"><i class="fa-solid fa-bell"></i></a>
            |
            @endif
            @if (!Auth::check() || (Auth::check() && auth()->user()->role !== 4))
            <form id="search-form" method="post" action="{{ route('search') }}" class="header-search-form">
                @csrf
                <a class="sans-serif search-toggle" id="search-toggle" href="javascript:void(0);">Search <i class="fas fa-search"></i></a>
                <div id="ola" style="display: none;">
                    <input type="text" name="query" class="search-input" id="search-input"
                        placeholder="Type here to search..." data-tooltip='If you place "  " around your search term, you can get an exact match search'>
                    <div class="search-tooltip" id="search-tooltip" style="display: none;">If you place "" around your search term, you can get an exact match search</div>
                </div>
            </form>
            <div id="messageContainer"></div>
            @endif
            @if (Auth::check() && auth()->user()->role !== 4)
            |
            <a class="sans-serif" href="/user/{{ Auth::check() ? Auth::user()->user_id : '' }}"><i
                    class="fas fa-user"></i> Profile</a>
            <a class="button sans-serif" href="{{ url('/logout') }}">Logout</a>
            @elseif (Auth::check() && auth()->user()->role === 4)
            <a class="button sans-serif" href="{{ url('/logout') }}">Logout</a>
            @else
            |
            <a class="sans-serif" href="{{ url('/login') }}">Login</a>
            @endif
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchToggle = document.getElementById('search-toggle');
        var searchInput = document.getElementById('search-input');
        var bothDivs = document.getElementById('ola');
        var tooltip = searchInput.nextElementSibling;
        
        function showTooltip() {
        tooltipTimeout = setTimeout(function () {
            tooltip.style.display = 'block';
        }, 2000); 
        }

        function hideTooltip() {
            clearTimeout(tooltipTimeout);
            tooltip.style.display = 'none';
        }

        searchInput.addEventListener('mouseover', showTooltip);
        searchInput.addEventListener('mouseout', hideTooltip);

        function showSearch() {
            searchToggle.style.display = 'none';
            bothDivs.style.display = 'block';
            searchInput.focus();  
        }

        function hideSearch() {
            bothDivs.style.display = 'none';
            searchToggle.style.display = 'block'; 
        }

        searchToggle.addEventListener('click', function () {
            showSearch();
        });

        document.addEventListener('click', function (event) {
            var isClickInsideSearch = bothDivs.contains(event.target) || searchToggle.contains(event.target);

            if (!isClickInsideSearch) {
                hideSearch();
            }
        });

        bothDivs.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                searchForm.submit(); 
            }
        });
    });

    
</script>


@if(isset($error))
<script>
    document.getElementById('messageContainer').innerHTML = `<p class="error-message"> ${"Error: Empty search not allowed"}</p>`;
    setTimeout(() => {
        messageContainer.firstChild.classList.add('fade-out');
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 2000);
    }, 2000);
</script>
@endif