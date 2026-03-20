@extends('adminlte::page')

@section('title', 'Schools List | Evos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Schools List</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('schools.create') }}" class="btn btn-success shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-plus-circle mr-1"></i> Add New School
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none;">
                <i class="icon fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm" style="border-radius: 15px; border: none;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Reg Number</th>
                                <th>School Name</th>
                                <th>Email</th>
                                <th>Head Phone</th>
                                <th>Category</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                                <tr>
                                    <td class="px-4"><strong>{{ $school->reg_number }}</strong></td>
                                    <td>{{ $school->name }}</td>
                                    <td>{{ $school->email }}</td>
                                    <td>{{ $school->head_phone }}</td>
                                    <td>
                                        <span class="badge {{ $school->category == 'Government' ? 'badge-info' : 'badge-primary' }}" style="border-radius: 8px; padding: 5px 10px;">
                                            {{ $school->category }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('schools.show', $school) }}" class="btn btn-sm btn-outline-secondary mr-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm btn-outline-info mr-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('schools.destroy', $school) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-school fa-3x mb-3 d-block opacity-50"></i>
                                        No schools found. <a href="{{ route('schools.create') }}">Click here to add one.</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
