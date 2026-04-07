@extends('layouts.dashboard')

@section('title', 'Edit Client - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Edit Client',
        'subtitle' => 'Update client: ' . $client->name
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
            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $client->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Initial <span class="text-danger">*</span></label>
                    <input type="text" name="initial" class="form-control @error('initial') is-invalid @enderror" value="{{ old('initial', $client->initial) }}" required>
                    @error('initial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo URL</label>
                    <input type="url" name="logo_url" class="form-control @error('logo_url') is-invalid @enderror" value="{{ old('logo_url', $client->logo_url) }}">
                    @error('logo_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Display Order <span class="text-danger">*</span></label>
                    <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $client->display_order) }}" min="0" required>
                    @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            Show this client on the landing page
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Client
                    </button>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
