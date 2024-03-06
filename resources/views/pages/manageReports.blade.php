@extends('layouts.app')

@section('content')
<a class="adminbutton" href="/admin/">Users List</a>
<a class="adminbutton2" href="/admin/topicProposals">Topic Proposals</a>
<a class="adminbutton3" href="/admin/unblockAppeals">Unblock Appeals</a>
<div class="container-list">
    <h1>Manage Reports</h1>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'UserReports')">User Reports</button>
        <button class="tablinks" onclick="openTab(event, 'NewsReports')">News Reports</button>
        <button class="tablinks" onclick="openTab(event, 'CommentReports')">Comment Reports</button>
    </div>

    <div id="UserReports" class="tabcontent" style="display:none;">
        @include('partials.reports_users', ['userreports' => $userreports])
    </div>

    <div id="NewsReports" class="tabcontent" style="display:none;">
        @include('partials.reports_news', ['newsreports' => $newsreports])
    </div>

    <div id="CommentReports" class="tabcontent" style="display:none;">
        @include('partials.reports_comments', ['commentreports' => $commentreports])
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.openTab = function(evt, tabName) {
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

    if(document.getElementsByClassName("tablinks").length > 0) {
        document.getElementsByClassName("tablinks")[0].click();
    }
});
</script>

