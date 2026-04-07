@extends('layouts.dashboard')

@section('title', 'Projects - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Manage Projects',
        'subtitle' => 'View and manage all portfolio projects'
    ])
@endsection

@section('content')
<div class="page-content">
    {{-- SweetAlert akan menampilkan toast otomatis --}}

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('projects.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search projects..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Project
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
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset($project->image) }}" alt="{{ $project->title }}" style="width: 80px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $project->title }}</strong><br>
                                    <small class="text-muted">{{ $project->slug }}</small>
                                </td>
                                <td>{{ $project->category }}</td>
                                <td>
                                    <span class="badge bg-{{ $project->status === 'Production' ? 'success' : ($project->status === 'Draft' ? 'secondary' : 'info') }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td>{{ $project->display_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $project->is_active ? 'success' : 'danger' }}">
                                        {{ $project->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmDelete(event, this)" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No projects found. <a href="{{ route('projects.create') }}">Create your first project!</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function confirmDelete(event, button) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endpush
@endsection