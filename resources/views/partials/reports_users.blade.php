@foreach ($userreports as $report)
    <div class="report-item">
        <div class="report-details">
            <p class="report-meta">Reported By: <a href="/user/{{ $report->reporter->user_id }}">{{ $report->reporter->username }}</a></p>
            <p class="report-reason">Reason: {{ $report->body }}</p>
            <p class="report-meta">Reported User: <a href="/user/{{ $report->reported->user_id}}">{{ $report->reported->username }}</a></p>
        </div>
            <p class="report-date">Date: {{ $report->date_time }}</p>
    </div>    
    <div class="bothbuttons">
        <form action="/admin/respondToBlock/{{$report->reported->user_id}}" method="post">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <button type="submit">Block User</button>
        </form>
        <form action="/admin/ignoreReportUser/{{$report->userreport_id}}" method="post">
        @csrf
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit">Ignore</button>
        </form>
    </div>
@endforeach
