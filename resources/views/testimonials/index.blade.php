@extends('layouts.dashboard')

@section('title', 'Testimonials - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Manage Testimonials',
        'subtitle' => 'View and manage all client testimonials'
    ])
@endsection

@section('content')
<div class="page-content">
    {{-- SweetAlert akan menampilkan toast otomatis --}}

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('testimonials.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search testimonials..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Testimonial
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
                            <th>Author</th>
                            <th>Text</th>
                            <th>Title</th>
                            <th>Rating</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $testimonial->author }}</strong><br>
                                    <small>{{ $testimonial->initials }}</small>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($testimonial->text, 100) }}</td>
                                <td>{{ $testimonial->title }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ $testimonial->rating }} ★</span>
                                </td>
                                <td>{{ $testimonial->display_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $testimonial->is_active ? 'success' : 'danger' }}">
                                        {{ $testimonial->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('testimonials.edit', $testimonial) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
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
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No testimonials found. <a href="{{ route('testimonials.create') }}">Add your first testimonial!</a></p>
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
