@extends('layouts.dashboard')

@section('title', 'Settings - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Settings',
        'subtitle' => 'Manage your site configuration'
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
        <div class="card-header">
            <h4>Logo Management</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">Current Logo</h5>
                    <div id="currentLogoPreview" class="text-center py-4 bg-light rounded border">
                        @if($settings->logo_url)
                            <img id="logoPreview" src="{{ $settings->logo_url ? asset($settings->logo_url) : '' }}" alt="Logo Preview" style="max-height: 200px;">
                        @else
                            <div class="py-5 text-muted">No logo uploaded yet</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Site Name <span class="text-danger">*</span></label>
                                <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings->site_name) }}" required>
                                @error('site_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo Alt Text</label>
                                <input type="text" name="logo_alt_text" class="form-control" value="{{ old('logo_alt_text', $settings->logo_alt_text) }}">
                                <small class="text-muted">Text untuk aksesibilitas</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Logo (Single untuk light/dark mode)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">General Logo (Both Light & Dark)</label>
                                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="logoInput" accept="image/png,image/jpeg,image/jpg,image/svg">
                                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted">PNG, JPG, SVG (Max: 2MB)</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary" onclick="uploadLogo('general')" id="uploadGeneralBtn">
                                            <i class="bi bi-upload"></i> Upload Logo
                                        </button>
                                        <button type="button" class="btn btn-success" id="uploadGeneralSpinner" style="display: none;">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-warning w-100" onclick="resetLogo('general')" title="Reset to default">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Separate Light/Dark Mode Logos</label>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Light Mode Logo</label>
                                    <input type="file" name="logo_light" class="form-control" id="logoLightInput" accept="image/png,image/jpeg,image/jpg,image/svg">
                                    <small class="text-muted">PNG, JPG, SVG (Max: 2MB)</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="button" class="btn btn-primary" onclick="uploadLogo('light')" id="uploadLightBtn">
                                            <i class="bi bi-upload"></i> Upload Light Logo
                                        </button>
                                        <button type="button" class="btn btn-success" id="uploadLightSpinner" style="display: none;">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-warning w-100 mt-4" onclick="resetLogo('light')" title="Reset light mode logo">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Dark Mode Logo</label>
                                    <input type="file" name="logo_dark" class="form-control" id="logoDarkInput" accept="image/png,image/jpeg,image/jpg,image/svg">
                                    <small class="text-muted">PNG, JPG, SVG (Max: 2MB)</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="button" class="btn btn-primary" onclick="uploadLogo('dark')" id="uploadDarkBtn">
                                            <i class="bi bi-upload"></i> Upload Dark Logo
                                        </button>
                                        <button type="button" class="btn btn-success" id="uploadDarkSpinner" style="display: none;">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-warning w-100 mt-4" onclick="resetLogo('dark')" title="Reset dark mode logo">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Save Settings
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadLogo(type) {
            event.preventDefault();
            
            const form = event.target.closest('form');
            const formData = new FormData(form);
            
            let fileInput;
            if (type === 'general') {
                fileInput = document.getElementById('logoInput');
                formData.append('logo', fileInput.files[0]);
                formData.append('type', 'general');
            } else if (type === 'light') {
                fileInput = document.getElementById('logoLightInput');
                formData.append('logo', fileInput.files[0]);
                formData.append('type', 'light');
            } else if (type === 'dark') {
                fileInput = document.getElementById('logoDarkInput');
                formData.append('logo', fileInput.files[0]);
                formData.append('type', 'dark');
            }
            
            if (!fileInput.files[0]) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please select a file to upload',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }
            
            const uploadBtn = document.getElementById('upload' + type.charAt(0).toUpperCase() + type.slice(1) + 'Btn');
            const spinnerBtn = document.getElementById('upload' + type.charAt(0).toUpperCase() + type.slice(1) + 'Spinner');
            
            uploadBtn.style.display = 'none';
            spinnerBtn.style.display = 'block';
            
            fetch('{{ route('settings.upload-logo') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text || 'Server returned ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                uploadBtn.style.display = 'block';
                spinnerBtn.style.display = 'none';
                
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    if (data.data && data.data.preview_url) {
                        const img = document.getElementById('logoPreview');
                        if (img) {
                            img.src = data.data.preview_url;
                            img.style.display = 'block';
                        } else {
                            const previewContainer = document.getElementById('currentLogoPreview');
                            if (previewContainer) {
                                previewContainer.innerHTML = '<img id="logoPreview" src="' + data.data.preview_url + '" alt="Logo Preview" style="max-height: 200px;">';
                            }
                        }
                    }
                    
                    fileInput.value = '';
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message || 'Upload failed',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                console.error('Error details:', error.message);
                
                uploadBtn.style.display = 'block';
                spinnerBtn.style.display = 'none';
                
                let errorMessage = 'Upload failed';
                if (error.message) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(error.message, 'text/html');
                    const errorElement = doc.querySelector('.error, .invalid-feedback');
                    if (errorElement) {
                        errorMessage = errorElement.textContent.trim();
                    } else {
                        const titleElement = doc.querySelector('title, h1');
                        if (titleElement && titleElement.textContent.includes('500')) {
                            errorMessage = 'Server error occurred';
                        } else if (error.message.length < 200) {
                            errorMessage = error.message;
                        }
                    }
                }
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: errorMessage,
                    text: error.message.length > 200 ? 'Please check console for details' : '',
                    showConfirmButton: false,
                    timer: 5000
                });
            });
        }

        function resetLogo(type) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Resetting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch('{{ route('settings.reset-logo') }}?type=' + type, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        
                        if (data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                            
                            // Hide the img element instead of removing it
                            const img = document.getElementById('logoPreview');
                            const previewContainer = document.getElementById('currentLogoPreview');
                            
                            if (img) {
                                img.src = '';
                                img.style.display = 'none';
                            }
                            
                            if (previewContainer && !img) {
                                // Only show "No logo" if img doesn't exist
                                previewContainer.innerHTML = '<div class="py-5 text-muted">No logo uploaded yet</div>';
                            }
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: data.message || 'Reset failed',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Reset error:', error);
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Reset failed',
                            text: error.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
                }
            });
        }
    </script>
</div>
@endsection
