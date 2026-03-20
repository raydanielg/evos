<div class="card shadow-sm" style="border-radius: 4px; border: none;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4" style="font-size: 12px; font-weight: 700;">Exam Number</th>
                        <th class="px-4" style="font-size: 12px; font-weight: 700;">Full Name</th>
                        <th style="font-size: 12px; font-weight: 700;">Sex</th>
                        <th style="font-size: 12px; font-weight: 700;">Class</th>
                        <th style="font-size: 12px; font-weight: 700;">Parent Phone</th>
                        <th class="text-center" style="font-size: 12px; font-weight: 700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-4">{{ $student->registration_number ?? '-' }}</td>
                            <td class="px-4" style="font-weight: 600;">{{ $student->full_name }}</td>
                            <td>{{ $student->sex === 'Male' ? 'M' : 'F' }}</td>
                            <td>{{ $student->schoolClass?->name ?? $student->class }}</td>
                            <td>{{ $student->parent_phone }}</td>
                            <td class="text-center">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-info" title="View" style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="Edit" style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning quick-transfer-btn" title="Transfer" 
                                    data-student-id="{{ $student->id }}" 
                                    data-student-name="{{ $student->full_name }}" 
                                    data-current-class-id="{{ $student->class_id }}"
                                    style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-user-graduate fa-3x mb-3 d-block opacity-50"></i>
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
