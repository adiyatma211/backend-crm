@extends('layouts.dashboard')

@section('title', 'Clients - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Manage Clients',
        'subtitle' => 'View and manage all clients'
    ])
@endsection

@section('content')
<div class="page-content">
    {{-- SweetAlert akan menampilkan toast otomatis --}}

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('clients.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search clients..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('clients.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Client
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
                            <th>Name</th>
                            <th>Initial</th>
                            <th>Logo URL</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $client->name }}</strong>
                                </td>
                                <td>{{ $client->initial }}</td>
                                <td>
                                    @if($client->logo_url)
                                        <a href="{{ $client->logo_url }}" target="_blank" class="text-primary">
                                            {{ \Illuminate\Support\Str::limit($client->logo_url, 50) }}
                                        </a>
                                    @else
                                        <span class="text-muted">No logo</span>
                                    @endif
                                </td>
                                <td>{{ $client->display_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }}">
                                        {{ $client->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
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
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No clients found. <a href="{{ route('clients.create') }}">Add your first client!</a></p>
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
