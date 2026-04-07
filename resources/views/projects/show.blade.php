@extends('layouts.dashboard')

@section('title', $project->title . ' - Sentosa CMS')

@section('page-heading')
    @include('partials.page-heading', [
        'title' => $project->title,
        'subtitle' => 'Project details'
    ])
@endsection

@section('content')
<div class="page-content">
    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Projects
            </a>
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Project Information</h4>
                </div>
                <div class="card-body">
                    <img src="{{ asset($project->image) }}" alt="{{ $project->title }}" class="img-fluid mb-3">
                    
                    <table class="table">
                        <tr>
                            <th style="width: 30%;">Title</th>
                            <td>{{ $project->title }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td><code>{{ $project->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $project->category }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $project->status === 'Production' ? 'success' : ($project->status === 'Draft' ? 'secondary' : 'info') }}">
                                    {{ $project->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Display Order</th>
                            <td>{{ $project->display_order }}</td>
                        </tr>
                        <tr>
                            <th>Active</th>
                            <td>
                                <span class="badge bg-{{ $project->is_active ? 'success' : 'danger' }}">
                                    {{ $project->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $project->description }}</td>
                        </tr>
                        <tr>
                            <th>Project URL</th>
                            <td><a href="{{ $project->project_url }}" target="_blank">{{ $project->project_url }}</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Technologies</h4>
                </div>
                <div class="card-body">
                    @if(is_array($project->technologies) && count($project->technologies) > 0)
                        <ul class="list-group">
                            @foreach($project->technologies as $tech)
                                <li class="list-group-item">{{ $tech }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No technologies specified</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4>Features</h4>
                </div>
                <div class="card-body">
                    @if(is_array($project->features) && count($project->features) > 0)
                        <ul class="list-group">
                            @foreach($project->features as $feature)
                                <li class="list-group-item">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No features specified</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection