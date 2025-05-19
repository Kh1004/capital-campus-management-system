@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Course Enrollments</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Type</th>
                                    <th>Enrolled On</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $enrollment->student_name }}</td>
                                        <td>{{ $enrollment->student_email }}</td>
                                        <td>{{ $enrollment->course_name }}</td>
                                        <td>{{ $enrollment->course_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($enrollment->status === 'enrolled') bg-primary
                                                @elseif($enrollment->status === 'completed') bg-success
                                                @elseif($enrollment->status === 'dropped') bg-danger
                                                @endif">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                                    data-target="#editEnrollment{{ $enrollment->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Enrollment Modal -->
                                    <div class="modal fade" id="editEnrollment{{ $enrollment->id }}" tabindex="-1" role="dialog" 
                                         aria-labelledby="editEnrollmentLabel{{ $enrollment->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editEnrollmentLabel{{ $enrollment->id }}">
                                                        Update Enrollment Status
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.enrollments.update-status', $enrollment->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select name="status" id="status" class="form-control" required>
                                                                <option value="enrolled" {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                                                <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                                <option value="dropped" {{ $enrollment->status === 'dropped' ? 'selected' : '' }}>Dropped</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="notes">Notes</label>
                                                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $enrollment->notes ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No enrollments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
