@foreach ($newsreports as $report)
    <div class="report-item">
        <div class="report-details">
            <p class="report-meta">Reported By: <a href="/user/{{ $report->reporter->user_id }}">{{ $report->reporter->username }}</a></p>
            <p class="report-reason">Reason: {{ $report->body }}</p>
            <p class="report-meta">Article: <a href="/news/{{ $report->reported_id }}">View Article</a></p>
        </div>
        <p class="report-date">Date: {{ $report->date_time }}</p>
    </div>
    <div class="bothbuttons">
        <form action="{{ route('admin.deletearticle', ['article_id' => $report->reported_id]) }}" method="post">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="action-button">Delete Article</button>
        </form>
        <form action="/admin/ignoreReportArticle/{{$report->articlereport_id}}" method="post">
        @csrf
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="action-button">Ignore</button>
        </form>
    </div>
@endforeach
