@extends('student.layout')

@section('title','Thông báo hệ thống')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <h3 style="margin:0 0 8px 0;">📣 Thông báo</h3>
        <ul style="margin:0;padding:0;list-style:none;">
            @forelse($announcements as $a)
            <li style="padding:10px 0;border-bottom:1px solid rgba(148,163,184,.12)">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <strong>{{ $a->title }}</strong>
                    <span class="muted">{{ optional($a->published_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div style="margin-top:6px;">{!! nl2br(e($a->content)) !!}</div>
            </li>
            @empty
            <li class="muted">Chưa có thông báo.</li>
            @endforelse
        </ul>
        <div style="margin-top:10px;">{{ $announcements->links() }}</div>
    </div>
    <div class="card">
        <h3 style="margin:0 0 8px 0;">⏰ Các đợt đăng ký</h3>
        <ul style="margin:0;padding:0;list-style:none;">
            @foreach($waves as $w)
            <li style="padding:10px 0;border-bottom:1px solid rgba(148,163,184,.12)">
                <strong>{{ $w->name }}</strong>
                <div class="muted">{{ $w->academic_year }} - {{ $w->term }}</div>
                <div class="muted">{{ \Carbon\Carbon::parse($w->starts_at)->format('d/m H:i') }} → {{ \Carbon\Carbon::parse($w->ends_at)->format('d/m H:i') }}</div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection