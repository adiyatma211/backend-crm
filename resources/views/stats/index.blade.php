@extends('layouts.dashboard')

@section('title', 'Stats - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Manage Stats',
        'subtitle' => 'View and manage all statistics'
    ])
@endsection

@section('content')
<div class="page-content">
    {{-- SweetAlert akan menampilkan toast otomatis --}}

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('stats.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search stats..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('stats.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Stat
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
                            <th>Value</th>
                            <th>Label</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats as $stat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="fs-5">{{ $stat->value }}</strong>
                                </td>
                                <td>{{ $stat->label }}</td>
                                <td>{{ $stat->display_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $stat->is_active ? 'success' : 'danger' }}">
                                        {{ $stat->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('stats.edit', $stat) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('stats.destroy', $stat) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmDelete(event, this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No stats found. <a href="{{ route('stats.create') }}">Add your first stat!</a></p>
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
