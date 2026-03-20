@extends('adminlte::page')

@section('title', 'Transfer Students')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Transfer Students</h1>
                @if($activeSchool)
                    <small class="text-muted">Active School: <strong>{{ $activeSchool->name }}</strong></small>
                @else
                    <small class="text-danger">No active school selected. Add a school first.</small>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(!$activeSchool)
            <div class="alert alert-warning shadow-sm" style="border-radius: 12px; border: none;">
                <i class="icon fas fa-exclamation-triangle"></i>
                You don't have an active school. Please add/select a school first.
                <a class="ml-2" href="{{ route('schools.create') }}">Add School</a>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning" style="border-radius: 10px;">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">Transfer Setup</h3>
                    </div>

                    <form action="{{ route('students.transfer.preview') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="from_class_id">From Class</label>
                                <select name="from_class_id" id="from_class_id" class="form-control @error('from_class_id') is-invalid @enderror" required {{ !$activeSchool ? 'disabled' : '' }}>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('from_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('from_class_id')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="to_class_id">To Class</label>
                                <select name="to_class_id" id="to_class_id" class="form-control @error('to_class_id') is-invalid @enderror" required {{ !$activeSchool ? 'disabled' : '' }}>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('to_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('to_class_id')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">You can transfer all students from the class, or paste selected IDs later from the Students list.</small>
                            </div>

                            <div class="form-group">
                                <label for="selected_ids">Selected Student IDs (Optional)</label>
                                <input type="text" name="selected_ids" id="selected_ids" class="form-control @error('selected_ids') is-invalid @enderror" value="{{ old('selected_ids') }}" placeholder="e.g. 10,12,15" {{ !$activeSchool ? 'disabled' : '' }}>
                                @error('selected_ids')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Leave empty to transfer all students in the From Class.</small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" style="border-radius: 6px;" {{ !$activeSchool ? 'disabled' : '' }}>Preview Transfer</button>
                            <a href="{{ route('students.index') }}" class="btn btn-default float-right" style="border-radius: 6px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
