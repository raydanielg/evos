<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function print(Request $request)
    {
        $activeSchool = $this->getActiveSchool();
        abort_unless($activeSchool, 403);

        $classes = SchoolClass::orderBy('sort_order')->get();
        $classId = $request->integer('class_id') ?: null;

        $q = Student::where('school_id', $activeSchool->id)->orderBy('class_id')->orderBy('reg_seq');
        if ($classId) {
            $q->where('class_id', $classId);
        }
        $students = $q->get();

        return view('students.print', compact('students', 'activeSchool', 'classes', 'classId'));
    }

    public function importForm()
    {
        $activeSchoolId = $this->activeSchoolId();
        $activeSchool = $activeSchoolId ? School::where('id', $activeSchoolId)->first() : null;

        $schools = School::where('user_id', auth()->id())->orderBy('name')->get();
        $classes = SchoolClass::orderBy('sort_order')->get();

        return view('students.import_form', compact('activeSchool', 'activeSchoolId', 'schools', 'classes'));
    }

    public function downloadImportTemplate(Request $request)
    {
        $filename = 'students_import_template.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['first_name', 'middle_name', 'last_name', 'sex', 'parent_phone']);
            fputcsv($out, ['John', 'A.', 'Doe', 'Male', '0753123456']);
            fputcsv($out, ['Jane', '', 'Doe', 'Female', '0753987654']);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importPreview(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|integer|exists:schools,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'file' => 'required|file',
        ]);

        $this->ensureSchoolBelongsToUser((int) $validated['school_id']);

        $school = School::where('id', (int) $validated['school_id'])->firstOrFail();
        $schoolClass = SchoolClass::findOrFail((int) $validated['class_id']);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        abort_unless($handle !== false, 422);

        $header = fgetcsv($handle);
        $rows = [];
        $errors = [];

        $headerMap = [];
        if (is_array($header)) {
            foreach ($header as $idx => $col) {
                $key = strtolower(trim((string) $col));
                $headerMap[$key] = $idx;
            }
        }

        $rowNo = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $rowNo++;
            if ($data === [null] || (count($data) === 1 && trim((string) $data[0]) === '')) {
                continue;
            }

            $r = [
                'first_name' => $this->csvValue($data, $headerMap, 'first_name'),
                'middle_name' => $this->csvValue($data, $headerMap, 'middle_name'),
                'last_name' => $this->csvValue($data, $headerMap, 'last_name'),
                'sex' => $this->csvValue($data, $headerMap, 'sex'),
                'parent_phone' => $this->csvValue($data, $headerMap, 'parent_phone'),
            ];

            if ($r['sex'] !== null) {
                $r['sex'] = ucfirst(strtolower(trim((string) $r['sex'])));
            }
            if ($r['sex'] === 'M') {
                $r['sex'] = 'Male';
            }
            if ($r['sex'] === 'F') {
                $r['sex'] = 'Female';
            }

            $v = Validator::make($r, [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'required|string|max:100',
                'sex' => 'required|in:Male,Female',
                'parent_phone' => 'required|string|max:30',
            ]);

            if ($v->fails()) {
                $msg = implode(' | ', $v->errors()->all());
                $errors[] = "Row {$rowNo}: {$msg}";
            }

            $rows[] = [
                'first_name' => (string) $r['first_name'],
                'middle_name' => $r['middle_name'] !== null ? (string) $r['middle_name'] : null,
                'last_name' => (string) $r['last_name'],
                'sex' => (string) $r['sex'],
                'parent_phone' => (string) $r['parent_phone'],
                '_error' => $v->fails() ? $msg : null,
            ];
        }

        fclose($handle);

        $payload = [
            'school_id' => (int) $school->id,
            'class_id' => (int) $schoolClass->id,
        ];

        session([
            'students_import_payload' => $payload,
        ]);

        return view('students.import_preview', [
            'school' => $school,
            'schoolClass' => $schoolClass,
            'rows' => $rows,
            'errors' => $errors,
        ]);
    }

    public function importConfirm(Request $request)
    {
        $payload = session('students_import_payload');
        abort_unless(is_array($payload), 419);

        $this->ensureSchoolBelongsToUser((int) ($payload['school_id'] ?? 0));

        $school = School::where('id', (int) $payload['school_id'])->firstOrFail();
        $schoolClass = SchoolClass::findOrFail((int) $payload['class_id']);

        $rows = $request->input('rows', []);
        if (!is_array($rows)) {
            $rows = [];
        }

        $errors = [];
        $normalizedRows = [];
        foreach (array_values($rows) as $idx => $r) {
            if (!is_array($r)) {
                $errors[] = 'Row ' . ($idx + 1) . ': Invalid format';
                $normalizedRows[] = [
                    'first_name' => null,
                    'middle_name' => null,
                    'last_name' => null,
                    'sex' => null,
                    'parent_phone' => null,
                    '_error' => 'Invalid format',
                ];
                continue;
            }

            $normalized = [
                'first_name' => isset($r['first_name']) ? trim((string) $r['first_name']) : null,
                'middle_name' => isset($r['middle_name']) ? trim((string) $r['middle_name']) : null,
                'last_name' => isset($r['last_name']) ? trim((string) $r['last_name']) : null,
                'sex' => isset($r['sex']) ? trim((string) $r['sex']) : null,
                'parent_phone' => isset($r['parent_phone']) ? trim((string) $r['parent_phone']) : null,
            ];

            $v = Validator::make($normalized, [
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'required|string|max:100',
                'sex' => 'required|in:Male,Female',
                'parent_phone' => 'required|string|max:30',
            ]);

            if ($v->fails()) {
                $msg = implode(' | ', $v->errors()->all());
                $errors[] = 'Row ' . ($idx + 1) . ': ' . $msg;
                $normalizedRows[] = [
                    'first_name' => $normalized['first_name'],
                    'middle_name' => $normalized['middle_name'] !== '' ? (string) $normalized['middle_name'] : null,
                    'last_name' => $normalized['last_name'],
                    'sex' => $normalized['sex'],
                    'parent_phone' => $normalized['parent_phone'],
                    '_error' => $msg,
                ];
                continue;
            }

            $normalizedRows[] = [
                'first_name' => (string) $normalized['first_name'],
                'middle_name' => $normalized['middle_name'] !== '' ? (string) $normalized['middle_name'] : null,
                'last_name' => (string) $normalized['last_name'],
                'sex' => (string) $normalized['sex'],
                'parent_phone' => (string) $normalized['parent_phone'],
                '_error' => null,
            ];
        }

        if (!empty($errors)) {
            return view('students.import_preview', [
                'school' => $school,
                'schoolClass' => $schoolClass,
                'rows' => $normalizedRows,
                'errors' => $errors,
            ]);
        }

        DB::transaction(function () use ($school, $schoolClass, $normalizedRows) {
            foreach ($normalizedRows as $r) {
                Student::create([
                    'school_id' => (int) $school->id,
                    'class_id' => (int) $schoolClass->id,
                    'reg_seq' => null,
                    'registration_number' => null,
                    'first_name' => $r['first_name'],
                    'middle_name' => $r['middle_name'] !== '' ? $r['middle_name'] : null,
                    'last_name' => $r['last_name'],
                    'sex' => $r['sex'],
                    'class' => $schoolClass->name,
                    'parent_phone' => $r['parent_phone'],
                    'photo_path' => null,
                ]);
            }
        });

        session()->forget('students_import_payload');

        return redirect()->route('students.index')->with('success', 'Students imported successfully.');
    }

    public function assignNumbers(Request $request)
    {
        $activeSchool = $this->getActiveSchool();
        abort_unless($activeSchool, 403);

        $classId = $request->integer('class_id');
        abort_unless($classId, 422, 'Please select a class first.');

        $selectedIds = $this->normalizeSelectedIds($request->input('selected_ids', []));

        DB::transaction(function () use ($activeSchool, $selectedIds, $classId) {
            $students = Student::where('school_id', $activeSchool->id)
                ->where('class_id', $classId)
                ->when($selectedIds->isNotEmpty(), function ($q) use ($selectedIds) {
                    $q->whereIn('id', $selectedIds);
                })
                ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $nextSeq = 1;
            
            foreach ($students as $student) {
                $student->update([
                    'reg_seq' => $nextSeq,
                    'registration_number' => $this->makeRegistrationNumber($activeSchool, $nextSeq),
                ]);
                $nextSeq++;
            }
        });

        return back()->with('success', 'Numbers assigned successfully for the selected class.');
    }

    public function reassignNumbers(Request $request)
    {
        $activeSchool = $this->getActiveSchool();
        abort_unless($activeSchool, 403);

        $classId = $request->integer('class_id');
        abort_unless($classId, 422, 'Please select a class first.');

        DB::transaction(function () use ($activeSchool, $classId) {
            // First, clear all registration numbers for this class to avoid unique constraint violations
            Student::where('school_id', $activeSchool->id)
                ->where('class_id', $classId)
                ->update([
                    'reg_seq' => null,
                    'registration_number' => null
                ]);

            $students = Student::where('school_id', $activeSchool->id)
                ->where('class_id', $classId)
                ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $nextSeq = 1;
            foreach ($students as $student) {
                $student->update([
                    'reg_seq' => $nextSeq,
                    'registration_number' => $this->makeRegistrationNumber($activeSchool, $nextSeq),
                ]);
                $nextSeq++;
            }
        });

        return back()->with('success', 'Numbers re-assigned alphabetically for all students in the selected class.');
    }

    private function activeSchoolId(): ?int
    {
        $schoolId = session('active_school_id');
        if ($schoolId) {
            $exists = School::where('id', $schoolId)
                ->where('user_id', auth()->id())
                ->exists();
            if ($exists) {
                return (int) $schoolId;
            }
        }

        $first = School::where('user_id', auth()->id())->orderBy('id')->value('id');
        if ($first) {
            session(['active_school_id' => (int) $first]);
            return (int) $first;
        }

        return null;
    }

    private function normalizeSelectedIds(mixed $raw): \Illuminate\Support\Collection
    {
        if (is_string($raw)) {
            $raw = array_filter(array_map('trim', explode(',', $raw)));
        }

        if (!is_array($raw)) {
            $raw = [];
        }

        return collect($raw)
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values();
    }

    private function ensureSchoolBelongsToUser(int $schoolId): void
    {
        $ok = School::where('id', $schoolId)->where('user_id', auth()->id())->exists();
        abort_unless($ok, 403);
    }

    private function ensureStudentBelongsToUser(Student $student): void
    {
        $ok = School::where('id', $student->school_id)->where('user_id', auth()->id())->exists();
        abort_unless($ok, 403);
    }

    private function getActiveSchool(): ?School
    {
        $activeSchoolId = $this->activeSchoolId();
        if (!$activeSchoolId) {
            return null;
        }

        $school = School::where('id', $activeSchoolId)->first();
        if (!$school) {
            return null;
        }

        $this->ensureSchoolBelongsToUser($school->id);

        return $school;
    }

    private function makeRegistrationNumber(School $school, int $seq): string
    {
        return $school->reg_number . '-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    private function csvValue(array $row, array $headerMap, string $key): ?string
    {
        if (!array_key_exists($key, $headerMap)) {
            return null;
        }

        $idx = $headerMap[$key];
        if (!array_key_exists($idx, $row)) {
            return null;
        }

        $v = trim((string) $row[$idx]);
        return $v === '' ? null : $v;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeSchoolId = $this->activeSchoolId();
        $activeSchool = $activeSchoolId ? School::where('id', $activeSchoolId)->first() : null;

        $classes = SchoolClass::orderBy('sort_order')->get();
        $classId = request()->integer('class_id') ?: null;

        $qText = trim((string) request('q', ''));
        $perPageRaw = (string) request('per_page', '10');
        $perPage = in_array($perPageRaw, ['10', '50', '100'], true) ? (int) $perPageRaw : 10;
        $all = $perPageRaw === 'all';
        if ($all) {
            $perPage = 1000000;
        }

        $students = collect();
        $paginator = null;
        if ($activeSchoolId && $classId) {
            $query = Student::where('school_id', $activeSchoolId)
                ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name');
            
            $query->where('class_id', $classId);

            if ($qText !== '') {
                $query->where(function ($sub) use ($qText) {
                    $sub->where('registration_number', 'like', "%{$qText}%")
                        ->orWhere('first_name', 'like', "%{$qText}%")
                        ->orWhere('middle_name', 'like', "%{$qText}%")
                        ->orWhere('last_name', 'like', "%{$qText}%")
                        ->orWhere('parent_phone', 'like', "%{$qText}%");
                });
            }

            $paginator = $query->paginate($perPage)->appends(request()->query());
            $students = $paginator->getCollection();
        }

        if (request()->ajax()) {
            $html = view('students._table', compact('students', 'paginator'))->render();
            return response()->json(['html' => $html]);
        }

        return view('students.index', compact('students', 'activeSchool', 'classes', 'classId', 'paginator', 'qText', 'perPageRaw'));
    }

    public function profile(Request $request)
    {
        $activeSchoolId = $this->activeSchoolId();
        $activeSchool = $activeSchoolId ? School::where('id', $activeSchoolId)->first() : null;

        $classes = SchoolClass::orderBy('sort_order')->get();
        $classId = $request->integer('class_id') ?: null;

        $qText = trim((string) $request->input('q', ''));

        $students = collect();
        if ($activeSchoolId) {
            $query = Student::where('school_id', $activeSchoolId)
                ->orderBy('class_id')
                ->orderBy('reg_seq')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('id');

            if ($classId) {
                $query->where('class_id', $classId);
            }

            if ($qText !== '') {
                $query->where(function ($sub) use ($qText) {
                    $sub->where('registration_number', 'like', "%{$qText}%")
                        ->orWhere('first_name', 'like', "%{$qText}%")
                        ->orWhere('middle_name', 'like', "%{$qText}%")
                        ->orWhere('last_name', 'like', "%{$qText}%")
                        ->orWhere('parent_phone', 'like', "%{$qText}%");
                });
            }

            $students = $query->get();
        }

        if ($request->ajax()) {
            $html = view('students._profile_table', compact('students'))->render();
            return response()->json(['html' => $html]);
        }

        return view('students.profile', compact('students', 'activeSchool', 'classes', 'classId', 'qText'));
    }

    public function transferForm(Request $request)
    {
        $activeSchoolId = $this->activeSchoolId();
        $activeSchool = $activeSchoolId ? School::where('id', $activeSchoolId)->first() : null;

        $classes = SchoolClass::orderBy('sort_order')->get();

        return view('students.transfer_form', compact('activeSchool', 'classes'));
    }

    public function transferPreview(Request $request)
    {
        $activeSchool = $this->getActiveSchool();
        abort_unless($activeSchool, 403);

        $validated = $request->validate([
            'from_class_id' => 'required|integer|exists:school_classes,id',
            'to_class_id' => 'required|integer|exists:school_classes,id|different:from_class_id',
            'selected_ids' => 'nullable',
        ]);

        $fromClass = SchoolClass::findOrFail((int) $validated['from_class_id']);
        $toClass = SchoolClass::findOrFail((int) $validated['to_class_id']);

        $selectedIds = $this->normalizeSelectedIds($request->input('selected_ids', []));

        $query = Student::where('school_id', $activeSchool->id)
            ->where('class_id', $fromClass->id)
            ->orderByRaw("CASE WHEN sex = 'Male' THEN 0 ELSE 1 END")
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('id');

        if ($selectedIds->isNotEmpty()) {
            $query->whereIn('id', $selectedIds);
        }

        $students = $query->get();
        $ids = $students->pluck('id')->values()->all();

        session([
            'students_transfer_payload' => [
                'school_id' => (int) $activeSchool->id,
                'from_class_id' => (int) $fromClass->id,
                'to_class_id' => (int) $toClass->id,
                'ids' => $ids,
            ],
            'students_transfer_progress' => [
                'total' => count($ids),
                'done' => 0,
                'status' => 'ready',
            ],
        ]);

        return view('students.transfer_preview', compact('activeSchool', 'fromClass', 'toClass', 'students'));
    }

    public function quickTransfer(Request $request, Student $student)
    {
        $this->ensureStudentBelongsToUser($student);

        $validated = $request->validate([
            'class_id' => 'required|integer|exists:school_classes,id',
        ]);

        $toClass = SchoolClass::findOrFail((int) $validated['class_id']);

        if ((int) $student->class_id === (int) $toClass->id) {
            return response()->json(['success' => false, 'message' => 'Student is already in this class.'], 422);
        }

        $student->update([
            'class_id' => (int) $toClass->id,
            'class' => $toClass->name,
            'reg_seq' => null,
            'registration_number' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$student->full_name} transferred to {$toClass->name} successfully.",
        ]);
    }

    public function transferProgress(Request $request)
    {
        $activeSchool = $this->getActiveSchool();
        abort_unless($activeSchool, 403);

        $payload = session('students_transfer_payload');
        abort_unless(is_array($payload), 419);

        abort_unless((int) ($payload['school_id'] ?? 0) === (int) $activeSchool->id, 403);

        $ids = $payload['ids'] ?? [];
        if (!is_array($ids)) {
            $ids = [];
        }

        $progress = session('students_transfer_progress');
        if (!is_array($progress)) {
            $progress = ['total' => count($ids), 'done' => 0, 'status' => 'ready'];
        }

        $total = (int) ($progress['total'] ?? count($ids));
        $done = (int) ($progress['done'] ?? 0);

        if ($total === 0) {
            session()->forget(['students_transfer_payload', 'students_transfer_progress']);
            return response()->json([
                'status' => 'done',
                'total' => 0,
                'done' => 0,
                'percent' => 100,
                'message' => 'No students to transfer.',
            ]);
        }

        $chunkSize = (int) ($request->input('chunk', 25));
        if ($chunkSize < 1) {
            $chunkSize = 25;
        }
        if ($chunkSize > 200) {
            $chunkSize = 200;
        }

        $slice = array_slice($ids, $done, $chunkSize);

        if (empty($slice)) {
            session()->forget(['students_transfer_payload', 'students_transfer_progress']);
            return response()->json([
                'status' => 'done',
                'total' => $total,
                'done' => $total,
                'percent' => 100,
                'message' => 'Transfer completed.',
            ]);
        }

        $toClass = SchoolClass::findOrFail((int) $payload['to_class_id']);

        DB::transaction(function () use ($activeSchool, $slice, $toClass) {
            Student::where('school_id', $activeSchool->id)
                ->whereIn('id', $slice)
                ->update([
                    'class_id' => (int) $toClass->id,
                    'class' => $toClass->name,
                    'reg_seq' => null,
                    'registration_number' => null,
                    'updated_at' => now(),
                ]);
        });

        $done = $done + count($slice);
        $percent = (int) floor(($done / max($total, 1)) * 100);

        session([
            'students_transfer_progress' => [
                'total' => $total,
                'done' => $done,
                'status' => $done >= $total ? 'done' : 'running',
            ],
        ]);

        if ($done >= $total) {
            session()->forget(['students_transfer_payload', 'students_transfer_progress']);
        }

        return response()->json([
            'status' => $done >= $total ? 'done' : 'running',
            'total' => $total,
            'done' => $done,
            'percent' => $done >= $total ? 100 : $percent,
            'message' => $done >= $total ? 'Transfer completed.' : 'Transferring...',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeSchoolId = $this->activeSchoolId();
        $activeSchool = $activeSchoolId ? School::where('id', $activeSchoolId)->first() : null;

        $schools = School::where('user_id', auth()->id())->orderBy('name')->get();

        $classes = SchoolClass::orderBy('sort_order')->get();

        return view('students.create', compact('activeSchool', 'activeSchoolId', 'schools', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|integer|exists:schools,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'sex' => 'required|in:Male,Female',
            'parent_phone' => 'required|string|max:30',
            'photo' => 'nullable|image|max:2048',
        ]);

        $this->ensureSchoolBelongsToUser((int) $validated['school_id']);

        $schoolClass = SchoolClass::findOrFail((int) $validated['class_id']);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students', 'public');
        }

        DB::transaction(function () use ($validated, $schoolClass, $photoPath) {
            Student::create([
                'school_id' => (int) $validated['school_id'],
                'class_id' => (int) $validated['class_id'],
                'reg_seq' => null,
                'registration_number' => null,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'sex' => $validated['sex'],
                'class' => $schoolClass->name,
                'parent_phone' => $validated['parent_phone'],
                'photo_path' => $photoPath,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $this->ensureStudentBelongsToUser($student);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $this->ensureStudentBelongsToUser($student);
        $schools = School::where('user_id', auth()->id())->orderBy('name')->get();
        $classes = SchoolClass::orderBy('sort_order')->get();
        return view('students.edit', compact('student', 'schools', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $this->ensureStudentBelongsToUser($student);

        $validated = $request->validate([
            'school_id' => 'required|integer',
            'class_id' => 'required|integer',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
        ]);

        $this->ensureSchoolBelongsToUser((int) $validated['school_id']);

        $schoolClass = SchoolClass::findOrFail((int) $validated['class_id']);

        $student->update([
            'school_id' => (int) $validated['school_id'],
            'class_id' => (int) $validated['class_id'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'sex' => $validated['gender'],
            'class' => $schoolClass->name,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->ensureStudentBelongsToUser($student);

        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}
