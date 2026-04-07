@extends('layouts.dashboard')

@section('title', 'Edit Stat - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Edit Stat',
        'subtitle' => 'Update stat: ' . $stat->label
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
            <form action="{{ route('stats.update', $stat) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $stat->value) }}" required>
                    @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $stat->label) }}" required>
                    @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Display Order <span class="text-danger">*</span></label>
                    <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $stat->display_order) }}" min="0" required>
                    @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $stat->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            Show this stat on the landing page
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Stat
                    </button>
                    <a href="{{ route('stats.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
