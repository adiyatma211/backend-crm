@extends('layouts.dashboard')

@section('title', 'Dashboard - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Dashboard Overview',
        'subtitle' => 'Welcome to Sentosa Content Management System'
    ])
@endsection

@section('content')
<div class="page-content">
    <div class="row mb-4">
        <div class="col-12 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="stats-icon purple mb-2">
                        <i class="bi bi-folder"></i>
                    </div>
                    <h6 class="text-muted font-semibold">Total Projects</h6>
                    <h3 class="font-extrabold mb-0">{{ $projectCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="stats-icon blue mb-2">
                        <i class="bi bi-chat-quote"></i>
                    </div>
                    <h6 class="text-muted font-semibold">Testimonials</h6>
                    <h3 class="font-extrabold mb-0">{{ $testimonialCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="stats-icon green mb-2">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6 class="text-muted font-semibold">Clients</h6>
                    <h3 class="font-extrabold mb-0">{{ $clientCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="stats-icon orange mb-2">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h6 class="text-muted font-semibold">Statistics</h6>
                    <h3 class="font-extrabold mb-0">{{ $statsCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('projects.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i> Add Project
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('testimonials.create') }}" class="btn btn-success w-100">
                                <i class="bi bi-plus-circle me-2"></i> Add Testimonial
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('clients.create') }}" class="btn btn-info w-100">
                                <i class="bi bi-plus-circle me-2"></i> Add Client
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('stats.create') }}" class="btn btn-warning w-100">
                                <i class="bi bi-plus-circle me-2"></i> Add Stat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Getting Started</h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('projects.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Manage Projects</h5>
                                <small>Portfolio content</small>
                            </div>
                            <p class="mb-1">Add, edit, or delete your portfolio projects.</p>
                        </a>
                        <a href="{{ route('testimonials.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Manage Testimonials</h5>
                                <small>Client feedback</small>
                            </div>
                            <p class="mb-1">Manage client testimonials and reviews.</p>
                        </a>
                        <a href="{{ route('clients.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Manage Clients</h5>
                                <small>Client logos</small>
                            </div>
                            <p class="mb-1">Add or remove client information and logos.</p>
                        </a>
                        <a href="{{ route('stats.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Manage Statistics</h5>
                                <small>Key metrics</small>
                            </div>
                            <p class="mb-1">Update statistics displayed on landing page.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
