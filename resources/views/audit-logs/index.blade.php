@extends('layouts.dashboard')

@section('title', 'Audit Logs - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => 'Audit Logs',
        'subtitle' => 'View system activity logs'
    ])
@endsection

@section('content')
<div class="page-content">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('audit-logs.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
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
                            <th>ID</th>
                            <th>Table</th>
                            <th>Action</th>
                            <th>Changed By</th>
                            <th>Changed At</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs ?? [] as $log)
                            <tr>
                                <td><code>{{ substr($log->id, 0, 8) }}...</code></td>
                                <td><code>{{ $log->table_name }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $log->action === 'INSERT' ? 'success' : ($log->action === 'UPDATE' ? 'warning' : 'danger') }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->changed_by }}</td>
                                <td>{{ $log->changed_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="alert('{{ json_encode($log->new_values) }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No audit logs found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection