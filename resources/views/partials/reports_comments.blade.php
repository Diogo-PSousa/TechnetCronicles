@foreach ($commentreports as $report)
    <div class="report-item">
        <div class="report-details">
            <p class="report-meta">Reported By: <a href="/user/{{ $report->reporter->user_id }}">{{ $report->reporter->username }}</a></p>
            <p class="report-reason">Reason: {{ $report->body }}</p>
            <p class="report-meta">Comment: <a href="/news/{{ $report->article_id }}">View Comment</a></p>
        </div>
        <p class="report-date">Date: {{ $report->date_time }}</p>
    </div>
    <div class="bothbuttons">
        <form action="{{ route('admin.deletecomment', ['comment_id' => $report->reported_id]) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="action-button">Delete Comment</button>
    </form>
        <form action="/admin/ignoreReportComment/{{$report->commentreport_id}}" method="post">
        @csrf
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="action-button">Ignore</button>
        </form>
    </div>
@endforeach
