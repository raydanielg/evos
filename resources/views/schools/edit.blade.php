@extends('adminlte::page')

@section('title', 'Edit School | Evos')

@section('content_header')
    <h1>Edit School: {{ $school->name }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                    <div class="card-body">
                        <form action="{{ route('schools.update', $school) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Registration Number</label>
                                    <input type="text" name="reg_number" class="form-control @error('reg_number') is-invalid @enderror" 
                                           value="{{ old('reg_number', $school->reg_number) }}" placeholder="e.g. S0101" required>
                                    @error('reg_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label>School Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $school->name) }}" placeholder="Enter school name" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $school->email) }}" placeholder="school@example.com" required>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label>Headmaster/Mistress Phone</label>
                                    <input type="text" name="head_phone" class="form-control @error('head_phone') is-invalid @enderror" 
                                           value="{{ old('head_phone', $school->head_phone) }}" placeholder="e.g. 07XXXXXXXX" required>
                                    @error('head_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label>School Category</label>
                                <div class="d-flex mt-2">
                                    <div class="custom-control custom-radio mr-4">
                                        <input class="custom-control-input" type="radio" id="gov" name="category" value="Government" {{ $school->category == 'Government' ? 'checked' : '' }}>
                                        <label for="gov" class="custom-control-label">Government</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="private" name="category" value="Private" {{ $school->category == 'Private' ? 'checked' : '' }}>
                                        <label for="private" class="custom-control-label">Private</label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <a href="{{ route('schools.index') }}" class="btn btn-light mr-2" style="border-radius: 10px;">Cancel</a>
                                <button type="submit" class="btn btn-info px-4" style="border-radius: 10px;">
                                    <i class="fas fa-save mr-1"></i> Update School
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        label { font-weight: 600; color: #4b5563; margin-bottom: 0.5rem; }
        .form-control { border-radius: 10px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 12px; height: auto; }
        .form-control:focus { background-color: #fff; border-color: #17a2b8; box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1); }
    </style>
@stop
