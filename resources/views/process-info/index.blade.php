@extends('layouts.dashboard')

@section('title', 'Process Info - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Manage Process Info',
        'subtitle' => 'View and manage dashboard metrics'
    ])
@endsection

@section('content')
<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('process-info.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search process info..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('process-info.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Process Info
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Label</th>
                            <th>Value</th>
                            <th>Unit</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($processInfos as $processInfo)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $processInfo->label }}</td>
                                <td><strong>{{ number_format($processInfo->value, 0) }}</strong></td>
                                <td>{{ $processInfo->unit }}</td>
                                <td>{{ $processInfo->display_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $processInfo->is_active ? 'success' : 'danger' }}">
                                        {{ $processInfo->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('process-info.destroy', $processInfo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No process info found. <a href="{{ route('process-info.create') }}">Create your first process info!</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
