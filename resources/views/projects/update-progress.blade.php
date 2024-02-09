@extends('layouts.layout')

@section('content')
    <div class="container">
        <h2 class="my-4">Update Progress</h2>
        <form method="POST" action="{{ route('projects.update-progress', $project->id) }}">
            @csrf
            @method('PUT')

            <div class="c0ntainer">
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $project->end_date }}">
                </div>

                <div class="form-group">
                    <label for="development_methodology">Development Methodology:</label>
                    <select class="form-control" id="development_methodology" name="development_methodology" required>
                        <option value="Waterfall" {{ $project->development_methodology == 'Waterfall' ? 'selected' : '' }}>
                            Waterfall</option>
                        <option value="Agile" {{ $project->development_methodology == 'Agile' ? 'selected' : '' }}>Agile
                        </option>
                        <option value="Scrum" {{ $project->development_methodology == 'Scrum' ? 'selected' : '' }}>Scrum
                        </option>
                        <option value="Kanban" {{ $project->development_methodology == 'Kanban' ? 'selected' : '' }}>Kanban
                        </option>
                        <option value="DevOps" {{ $project->development_methodology == 'DevOps' ? 'selected' : '' }}>DevOps
                        </option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="system_platform">System Platform:</label>
                    <select class="form-control" id="system_platform" name="system_platform" required>
                        <option value="Web-based" {{ $project->system_platform == 'Web-based' ? 'selected' : '' }}>Web-based
                        </option>
                        <option value="Mobile" {{ $project->system_platform == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                        <option value="Stand-alone" {{ $project->system_platform == 'Stand-alone' ? 'selected' : '' }}>
                            Stand-alone</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deployment_type">Deployment Type:</label>
                    <select class="form-control" id="deployment_type" name="deployment_type" required>
                        <option value="Cloud" {{ $project->deployment_type == 'Cloud' ? 'selected' : '' }}>Cloud</option>
                        <option value="On-premises" {{ $project->deployment_type == 'On-premises' ? 'selected' : '' }}>
                            On-premises</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Ahead of Schedule" {{ $project->status == 'Ahead of Schedule' ? 'selected' : '' }}>
                            Ahead of Schedule</option>
                        <option value="On Schedule" {{ $project->status == 'On Schedule' ? 'selected' : '' }}>On Schedule
                        </option>
                        <option value="Delayed" {{ $project->status == 'Delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" style="resize: none; height: 124px;" id="description" name="description" rows="4"
                        required>{{ $project->description }}</textarea>
                </div>


                <div class="form-group">
                    <label for="last_report">Report Date:</label>
                    <input type="date" class="form-control" id="last_report" name="last_report"
                        value="{{ $project->last_report }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
