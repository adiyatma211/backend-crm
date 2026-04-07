<div class="page-content d-flex align-items-center">
    <div class="row mx-0 w-100">
        <div class="col-md-6">
            <p><nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ?? 'Current Page' }}</li>
                </ol>
            </nav></p>
        </div>
        @isset($subtitle)
        <div class="col-md-6">
            <div class="text-md-end dataTables_filter">
                @yield('page-actions')
            </div>
        </div>
        @endisset
    </div>
</div>
