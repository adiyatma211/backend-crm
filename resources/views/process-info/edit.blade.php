@extends('layouts.dashboard')

@section('title', 'Edit Process Info - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Edit Process Info',
        'subtitle' => 'Update process metric: ' . $processInfo->label
    ])
@endsection

@section('content')
<div class="page-content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('process-info.update', $processInfo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $processInfo->label) }}" placeholder="e.g., Proyek Selesai" required>
                        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Label untuk metrik dashboard</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $processInfo->value) }}" placeholder="e.g., 150" required>
                        @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $processInfo->unit) }}" placeholder="e.g., produk, %" required>
                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order <span class="text-danger">*</span></label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $processInfo->display_order) }}" min="0" required>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Semakin rendah angkanya semakin di awal</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Active</label>
                        <div class="form-check mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $processInfo->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Show this process info on landing page
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Process Info
                    </button>
                    <a href="{{ route('process-info.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
