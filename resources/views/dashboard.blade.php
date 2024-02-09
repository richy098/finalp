@extends('layouts.layout')

@section('content')
    <div class="container">
        <h2 class="mb-4">Welcome to Your Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-8">

                @if (auth()->check())
                    <div class="alert alert-info">
                        <p class="mb-0">Welcome back, <span class="font-weight-bold">{{ auth()->user()->name }}</span>!</p>
                    </div>
                @endif

                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @if (isset($projects))
                        @foreach ($projects as $project)
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $project->project_name }}</h5>
                                        <p class="card-text"><strong>Business Unit:</strong> {{ $project->bu_name }}</p>
                                        <p class="card-text"><strong>Dates:</strong><br>
                                            Start: {{ $project->start_date }}<br>
                                            Duration: {{ $project->duration }} days<br>
                                            End: {{ $project->end_date ?: 'Not set' }}
                                        </p>
                                        <p class="card-text"><strong>Team:</strong><br>
                                            Lead: {{ $project->leadDeveloper->name }}<br>
                                            Other:
                                            @forelse($project->developers as $developer)
                                                {{ $developer->name }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @empty
                                                None
                                            @endforelse
                                        </p>
                                        <p class="card-text"><strong>Details:</strong><br>
                                            Methodology: {{ $project->development_methodology ?: '-' }}<br>
                                            Platform: {{ $project->system_platform ?: '-' }}<br>
                                            Deployment: {{ $project->deployment_type ?: '-' }}
                                        </p>
                                        <p class="card-text"><strong>Progress:</strong><br>
                                            Status: {{ $project->status ?: '-' }}<br>
                                            Last Update: {{ $project->last_report ?: '-' }}<br>
                                            Description: {{ $project->description ?: '-' }}
                                        </p>
                                        <div class="card-footer text-right">
                                            @if (auth()->user()->isLeadDeveloper() && $project->lead_developer_id === auth()->user()->id)
                                                <a href="{{ route('projects.update-progress-form', $project->id) }}"
                                                    class="btn btn-primary">Update</a>
                                            @elseif(auth()->user()->isManager())
                                                <a href="{{ route('projects.edit', $project->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <form action="{{ route('projects.destroy', $project->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger my-2"
                                                        onclick="confirmDelete({{ $project->id }})">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Team Members</h5>

                        @if (isset($teamMembers) && count($teamMembers) > 0)
                            <ul class="list-group">
                                @foreach ($teamMembers as $member)
                                    <li class="list-group-item">
                                        <strong>{{ $member->name }}</strong><br>
                                        <span class="text-muted">Position: {{ $member->role }}</span><br>
                                        <span class="text-muted">Contact: +60 17 1234 567</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No team members found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(projectId) {
            var result = confirm("Are you sure you want to delete this project?");
            if (!result) {
                if (event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }
                return false;
            }
            window.location.href = "{{ url('projects/destroy') }}" + "/" + projectId;
            return true;
        }
    </script>
    
    
    
    

@endsection
