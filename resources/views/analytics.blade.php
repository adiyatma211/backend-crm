@extends('layouts.dashboard')

@section('title', 'Analytics - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Analytics',
        'subtitle' => 'View system analytics'
    ])
@endsection

@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bar-chart-line fs-1 text-muted mb-3"></i>
            <h4 class="mb-2">Analytics Dashboard</h4>
            <p class="text-muted">Advanced analytics features coming soon...</p>
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                This section will include page views, user engagement, and performance metrics.
            </div>
        </div>
    </div>
</div>
@endsection