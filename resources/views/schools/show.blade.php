@extends('adminlte::page')

@section('title', 'School Details | Evos')

@section('content_header')
    <h1>School Details: {{ $school->name }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-school mr-2 text-success"></i> Basic Information</h3>
                        <div class="card-tools">
                            <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm btn-info" style="border-radius: 8px;">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Registration Number</div>
                            <div class="col-sm-8 font-weight-bold">{{ $school->reg_number }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">School Name</div>
                            <div class="col-sm-8 font-weight-bold text-uppercase">{{ $school->name }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Email Address</div>
                            <div class="col-sm-8 font-weight-bold">{{ $school->email }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Headmaster Phone</div>
                            <div class="col-sm-8 font-weight-bold">{{ $school->head_phone }}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Category</div>
                            <div class="col-sm-8">
                                <span class="badge {{ $school->category == 'Government' ? 'badge-info' : 'badge-primary' }}" style="border-radius: 8px; padding: 5px 12px; font-size: 0.9rem;">
                                    {{ $school->category }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center mt-4">
                            <a href="{{ route('schools.index') }}" class="btn btn-light shadow-sm px-4" style="border-radius: 10px;">
                                <i class="fas fa-arrow-left mr-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
