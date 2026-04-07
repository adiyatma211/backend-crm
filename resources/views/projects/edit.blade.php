@extends('layouts.dashboard')

@section('title', 'Edit Project - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Edit Project',
        'subtitle' => 'Update project: ' . $project->title
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
            <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <h5 class="mb-3">Basic Information</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $project->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $project->slug) }}" required>
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="Platform Web Fintech" {{ old('category', $project->category) === 'Platform Web Fintech' ? 'selected' : '' }}>Platform Web Fintech</option>
                            <option value="Mobile App Development" {{ old('category', $project->category) === 'Mobile App Development' ? 'selected' : '' }}>Mobile App Development</option>
                            <option value="Enterprise Solutions" {{ old('category', $project->category) === 'Enterprise Solutions' ? 'selected' : '' }}>Enterprise Solutions</option>
                            <option value="E-commerce" {{ old('category', $project->category) === 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                            <option value="Other" {{ old('category', $project->category) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Draft" {{ old('status', $project->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Beta publik" {{ old('status', $project->status) === 'Beta publik' ? 'selected' : '' }}>Beta publik</option>
                            <option value="Production" {{ old('status', $project->status) === 'Production' ? 'selected' : '' }}>Production</option>
                            <option value="Archived" {{ old('status', $project->status) === 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $project->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h5 class="mb-3 mt-4">URLs</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Image</label>
                        
                        @if($project->image)
                            <div class="mb-2">
                                <small class="text-muted">Current Image:</small>
                                <div class="mt-1">
                                    <img src="{{ asset($project->image) }}" alt="Current Project Image" style="max-width: 100%; max-height: 150px; border-radius: 5px;">
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/png,image/jpeg,image/jpg,image/webp" id="projectImageInput">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">PNG, JPG, JPEG, WebP (Max: 2MB). Leave empty to keep current image.</small>
                        
                        <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                            <small class="text-muted">New Image Preview:</small>
                            <div class="mt-1">
                                <img id="imagePreview" src="" alt="New Image Preview" style="max-width: 100%; max-height: 150px; border-radius: 5px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project URL <span class="text-danger">*</span></label>
                        <input type="url" name="project_url" class="form-control @error('project_url') is-invalid @enderror" value="{{ old('project_url', $project->project_url) }}" required>
                        @error('project_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Technologies & Features</h5>

                <div class="mb-3">
                    <label class="form-label">Technologies (comma-separated)</label>
                    <input type="text" name="technologies" class="form-control" value="{{ old('technologies', is_array($project->technologies) ? implode(', ', $project->technologies) : $project->technologies) }}" placeholder="React, Node.js, MongoDB">
                    <small class="text-muted">e.g., React, Node.js, MongoDB</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Features (comma-separated)</label>
                    <input type="text" name="features" class="form-control" value="{{ old('features', is_array($project->features) ? implode(', ', $project->features) : $project->features) }}" placeholder="Multi-wallet payments, Real-time analytics">
                    <small class="text-muted">e.g., Multi-wallet payments, Real-time analytics</small>
                </div>

                <h5 class="mb-3 mt-4">Display Settings</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order <span class="text-danger">*</span></label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $project->display_order) }}" min="0" required>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Active</label>
                        <div class="form-check mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $project->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Show this project on the landing page
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Project
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('projectImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const container = document.getElementById('imagePreviewContainer');
                preview.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection