@extends('layouts.app')

@section('content')
<div class="container" id="lol">
    <h1>Search Results for {{$query}}</h1>


    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'All')">All</button>
        <button class="tablinks" onclick="openTab(event, 'Users')">Users</button>
        <button class="tablinks" onclick="openTab(event, 'News')">News</button>
        <button class="tablinks" onclick="openTab(event, 'Comments')">Comments</button>
        <button class="tablinks" onclick="openTab(event, 'Tags')">Tags</button>
    </div>

    <div class="sorting">
        <button class="tablinks2" onclick="sortResults('relevant')">Most Relevant</button>
        <button class="tablinks2" onclick="sortResults('newest')">Newest</button>
        <button class="tablinks2" onclick="sortResults('voted')">Most Voted</button>
    </div>


    <div id="FullResults">
        <div id="All" class="tabcontent">
            @include('partials.results_all')
        </div>
 
        <div id="Users" class="tabcontent" style="display:none;">
            @include('partials.results_users', ['accounts' => $accounts])
        </div>

        <div id="News" class="tabcontent" style="display:none;">
            @include('partials.results_news', ['news' => $news])
        </div>

        <div id="Comments" class="tabcontent" style="display:none;">
            @include('partials.results_comments', ['comments' => $comments])
        </div>

        <div id="Tags" class="tabcontent" style="display:none;">
            @include('partials.results_tags', ['tags' => $tags])
        </div>

        @if (count($accounts) == 0 && count($news) == 0 && count($comments) == 0 && count($tags) == 0)
        <p>No matches or bad search.</p>
        @endif
    </div>
</div>


<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function sortResults(sortType) {
        let searchQuery = @json($query);;
        fetchSortedResults(searchQuery, sortType);
    }

    function fetchSortedResults(query, sortType) {
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let formData = new FormData();
        formData.append('query', query);
        formData.append('sortType', sortType);

        fetch('/search', { 
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
            .then(response => response.text())
            .then(html => {
                document.getElementById('FullResults').innerHTML = html; 
            })
            .catch(error => console.error('Error:', error));
    }

    document.getElementById('defaultOpen').click();
</script>

@endsection