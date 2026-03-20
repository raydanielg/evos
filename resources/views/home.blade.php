@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard Overview</h1>
                <p class="text-muted small mb-0">Karibu tena! Hapa kuna muhtasari wa kinachoendelea shuleni leo.</p>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm border-0" style="border-radius: 12px;">
                    <div class="inner p-4">
                        <h3>{{ $stats['total_schools'] }}</h3>
                        <p class="font-weight-bold">Shule Zilizosajiliwa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-school opacity-50"></i>
                    </div>
                    <a href="{{ route('schools.index') }}" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        Simamia Shule <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm border-0" style="border-radius: 12px;">
                    <div class="inner p-4">
                        <h3>{{ $stats['total_students'] }}</h3>
                        <p class="font-weight-bold">Wanafunzi Walipo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate opacity-50"></i>
                    </div>
                    <a href="{{ route('students.index') }}" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        Orodha ya Wanafunzi <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning shadow-sm border-0" style="border-radius: 12px;">
                    <div class="inner p-4 text-white">
                        <h3>{{ $stats['total_exams'] }}</h3>
                        <p class="font-weight-bold">Mitihani Iliyofanyika</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-signature opacity-50 text-white"></i>
                    </div>
                    <a href="{{ route('exams.index') }}" class="small-box-footer text-white" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        Historia ya Mitihani <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm border-0" style="border-radius: 12px;">
                    <div class="inner p-4">
                        <h3>{{ $stats['avg_performance'] }}<sup style="font-size: 20px">%</sup></h3>
                        <p class="font-weight-bold">Ufaulu wa Jumla</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line opacity-50"></i>
                    </div>
                    <a href="{{ route('results.analysis') }}" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        Angalia Uchambuzi <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            {{-- Quick Actions --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-bolt mr-2 text-primary"></i> Njia za Mkato</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('marks.entry') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon-circle bg-primary-soft mr-3">
                                    <i class="fas fa-keyboard text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold">Ingiza Alama za Mitihani</h6>
                                    <small class="text-muted">Ingiza alama za majaribio mapya</small>
                                </div>
                            </a>
                            <a href="{{ route('students.create') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon-circle bg-success-soft mr-3">
                                    <i class="fas fa-user-plus text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold">Sajili Mwanafunzi</h6>
                                    <small class="text-muted">Ongeza mwanafunzi kwenye darasa</small>
                                </div>
                            </a>
                            <a href="{{ route('results.index') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon-circle bg-info-soft mr-3">
                                    <i class="fas fa-print text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold">Print Report Cards</h6>
                                    <small class="text-muted">Tengeneza ripoti za wanafunzi</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity/Results --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-history mr-2 text-warning"></i> Matokeo ya Hivi Karibuni</h3>
                        <a href="{{ route('results.school') }}" class="btn btn-xs btn-outline-primary px-3 py-1" style="border-radius: 20px;">Angalia Yote</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light small text-uppercase font-weight-bold">
                                    <tr>
                                        <th class="px-4 border-0">Mwanafunzi</th>
                                        <th class="border-0">Mtihani</th>
                                        <th class="text-center border-0">Wastani</th>
                                        <th class="text-center border-0">Div</th>
                                        <th class="text-center border-0">Pos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['recent_results'] as $res)
                                        <tr>
                                            <td class="px-4 font-weight-bold text-dark">{{ $res->student->full_name }}</td>
                                            <td>{{ $res->exam->title }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-light border">{{ number_format($res->average, 1) }}%</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $res->division == 'I' ? 'success' : ($res->division == '0' ? 'danger' : 'info') }}">{{ $res->division ?? 'INC' }}</span>
                                            </td>
                                            <td class="text-center font-weight-bold">{{ $res->position ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted small">Hakuna matokeo yaliyochakatwa hivi karibuni.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .bg-primary-soft { background-color: rgba(0, 123, 255, 0.1); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1); }
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .small-box { transition: transform 0.2s; position: relative; display: block; }
    .small-box:hover { transform: scale(1.02); }
    .card-title { margin-bottom: 0; }
</style>
@stop
