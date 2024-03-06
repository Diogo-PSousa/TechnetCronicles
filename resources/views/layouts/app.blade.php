<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/article.css') }}" rel="stylesheet">
    <link href="{{ url('css/articles.css') }}" rel="stylesheet">
    <link href="{{ url('css/search.css') }}" rel="stylesheet">
    <link href="{{ url('css/searchResults.css') }}" rel="stylesheet">
    <link href="{{ url('css/profile.css') }}" rel="stylesheet">
    <link href="{{ url('css/comment.css') }}" rel="stylesheet">
    <link href="{{ url('css/votes.css') }}" rel="stylesheet">
    <link href="{{ url('css/notifications.css') }}" rel="stylesheet">
    <link href="{{ url('css/statics.css') }}" rel="stylesheet">
    <link href="{{ url('css/auth.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <!-- Pusher -->
    <script>
      const pusherAppKey = "{{ env('PUSHER_APP_KEY') }}";
      const pusherCluster = "{{ env('PUSHER_APP_CLUSTER') }}";
    </script>
    <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
    <script type="text/javascript" src={{ url('js/notifications.js') }} defer></script>
    <!-- JS -->
    <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
    <script src="{{ asset('js/news.js') }}"></script>
    <script src="{{ asset('js/votes.js') }}"></script>
    <script src="{{ asset('js/statics.js') }}"></script>
    <script>
        var isAuthenticated = @json(Auth:: check());
    </script>

</head>

<body>
    @include('partials.header') <!-- Including the header -->
    @include('partials.notification')
    <main>
        <section id="content">
            @yield('content')
        </section>
    </main>
    @include('partials.footer')
</body>
<script>
    // Pass authenticated user's information to JavaScript variable
    const currentUser = {!! json_encode(auth()->user()) !!};
</script>

</html>