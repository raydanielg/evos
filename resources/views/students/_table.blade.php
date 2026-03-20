<div class="card shadow-sm" style="border-radius: 4px; border: none;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-3" style="width: 40px;">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="students-select-all">
                                <label for="students-select-all" class="custom-control-label">&nbsp;</label>
                            </div>
                        </th>
                        <th class="px-4 evos-col evos-col-exam" style="font-size: 12px; font-weight: 700;">Exam Number</th>
                        <th class="px-4 evos-col evos-col-name" style="font-size: 12px; font-weight: 700;">Full Name</th>
                        <th class="evos-col evos-col-sex" style="font-size: 12px; font-weight: 700;">Sex</th>
                        <th class="evos-col evos-col-class" style="font-size: 12px; font-weight: 700;">Class</th>
                        <th class="evos-col evos-col-parent" style="font-size: 12px; font-weight: 700;">Parent Phone</th>
                        <th class="text-center evos-col evos-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input students-row-check" type="checkbox" id="st-{{ $student->id }}" value="{{ $student->id }}">
                                    <label for="st-{{ $student->id }}" class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td class="px-4 evos-col evos-col-exam">{{ $student->registration_number ?? '-' }}</td>
                            <td class="px-4 evos-col evos-col-name" style="font-weight: 600;">{{ $student->full_name }}</td>
                            <td class="evos-col evos-col-sex">{{ $student->sex === 'Male' ? 'M' : 'F' }}</td>
                            <td class="evos-col evos-col-class">{{ $student->schoolClass?->name ?? $student->class }}</td>
                            <td class="evos-col evos-col-parent">{{ $student->parent_phone }}</td>
                            <td class="text-center evos-col evos-col-actions">
                                <div class="d-inline-flex" style="gap: 8px;">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-info" title="View" style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="Edit" style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline-block students-delete-form" data-student-name="{{ $student->full_name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-warning quick-transfer-btn" title="Transfer" 
                                        data-student-id="{{ $student->id }}" 
                                        data-student-name="{{ $student->full_name }}" 
                                        data-current-class-id="{{ $student->class_id }}"
                                        style="border-radius: 4px; width: 36px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
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

@if($paginator)
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap" style="gap: 8px;">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing <strong>{{ $paginator->firstItem() ?? 0 }}</strong> to <strong>{{ $paginator->lastItem() ?? 0 }}</strong> of <strong>{{ $paginator->total() }}</strong> students
        </div>
        <div class="pagination-sm">
            {{ $paginator->onEachSide(1)->links() }}
        </div>
    </div>
@endif
