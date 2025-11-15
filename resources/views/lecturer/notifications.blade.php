@extends('lecturer.layout')

@section('title', 'Thông báo')

@section('content')
<h3 class="mb-4">
    <i class="fas fa-bell me-2" style="color: #1976d2;"></i>
    Thông báo hệ thống
</h3>

@if($announcements->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có thông báo mới</h5>
        <p class="text-muted">Các thông báo từ hệ thống sẽ hiển thị tại đây</p>
    </div>
</div>
@else
<div class="row">
    @foreach($announcements as $announcement)
    <div class="col-12 mb-3">
        <div class="card announcement-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bullhorn me-2 text-primary"></i>
                        {{ $announcement->title }}
                    </h5>
                    <span class="badge bg-primary">
                        @php
                        $label = 'Tất cả';
                        $aud = $announcement->audience ?? null;
                        if (is_array($aud)) {
                        if (!empty($aud['faculties'])) {
                        $label = 'Khoa';
                        } elseif (!empty($aud['roles']) && in_array('lecturers', $aud['roles'])) {
                        $label = 'Giảng viên';
                        } elseif (!empty($aud['roles']) && in_array('all', $aud['roles'])) {
                        $label = 'Tất cả';
                        }
                        }
                        @endphp
                        {{ $label }}
                    </span>
                </div>

                <p class="card-text text-muted mb-3">
                    {!! nl2br(e($announcement->content)) !!}
                </p>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $announcement->created_at->format('d/m/Y H:i') }}
                    </small>

                    @if($announcement->created_at->diffInDays(now()) < 7)
                        <span class="badge bg-success">
                        <i class="fas fa-star me-1"></i>Mới
                        </span>
                        @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($announcements->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $announcements->links() }}
</div>
@endif
@endif
@endsection

@section('styles')
<style>
    .announcement-card {
        transition: all 0.3s;
        border-left: 4px solid #1976d2;
    }

    .announcement-card:hover {
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.15);
        transform: translateX(5px);
    }

    .announcement-card .card-title {
        color: #1976d2;
    }
</style>
@endsection