@extends('layouts.dashboard')

@section('title', 'Create Testimonial - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Create New Testimonial',
        'subtitle' => 'Add a new client testimonial'
    ])
@endsection

@section('content')
<div class="page-content">
    @if ($errors->any())
        @push('scripts')
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please correct the errors below.',
                html: '<ul style="text-align: left;">' +
                    @foreach ($errors->all() as $error)
                        '<li style="margin-bottom: 5px;">{{ $error }}</li>' +
                    @endforeach
                    '</ul>'
            });
        </script>
        @endpush
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('testimonials.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                        @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Author Initials <span class="text-danger">*</span></label>
                        <input type="text" name="initials" class="form-control @error('initials') is-invalid @enderror" value="{{ old('initials') }}" placeholder="JD" required>
                        @error('initials') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author Title/Company <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="CEO, TechCorp Indonesia" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Testimonial Text <span class="text-danger">*</span></label>
                    <textarea name="text" class="form-control @error('text') is-invalid @enderror" rows="4" required>{{ old('text') }}</textarea>
                    @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rating (1-5) <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                            <option value="">Select Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                            @endfor
                        </select>
                        @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order <span class="text-danger">*</span></label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', 0) }}" min="0" required>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                        <label class="form-check-label" for="isActive">
                            Show this testimonial on the landing page
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Save Testimonial
                    </button>
                    <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
