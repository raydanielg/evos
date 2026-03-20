@extends('adminlte::page')

@section('title', 'Grading System')

@section('content_header')
    <h1>Grading System Settings</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning card-outline shadow-sm" style="border-radius: 10px;">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title font-weight-bold">Current Grading Scheme</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light text-uppercase small font-weight-bold">
                                    <tr>
                                        <th class="px-4">Grade</th>
                                        <th class="text-center">Min Score</th>
                                        <th class="text-center">Max Score</th>
                                        <th class="text-center">Points</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4"><span class="badge badge-success px-3 py-2">A</span></td>
                                        <td class="text-center font-weight-bold">75</td>
                                        <td class="text-center font-weight-bold">100</td>
                                        <td class="text-center">1</td>
                                        <td>Excellent (Vizuri Sana)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4"><span class="badge badge-primary px-3 py-2">B</span></td>
                                        <td class="text-center font-weight-bold">65</td>
                                        <td class="text-center font-weight-bold">74</td>
                                        <td class="text-center">2</td>
                                        <td>Very Good (Vizuri)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4"><span class="badge badge-info px-3 py-2">C</span></td>
                                        <td class="text-center font-weight-bold">45</td>
                                        <td class="text-center font-weight-bold">64</td>
                                        <td class="text-center">3</td>
                                        <td>Good (Vizuri)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4"><span class="badge badge-warning px-3 py-2">D</span></td>
                                        <td class="text-center font-weight-bold">30</td>
                                        <td class="text-center font-weight-bold">44</td>
                                        <td class="text-center">4</td>
                                        <td>Satisfactory (Inaridhisha)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4"><span class="badge badge-danger px-3 py-2">F</span></td>
                                        <td class="text-center font-weight-bold">0</td>
                                        <td class="text-center font-weight-bold">29</td>
                                        <td class="text-center">5</td>
                                        <td>Fail (Feli)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button class="btn btn-warning shadow-sm disabled" style="border-radius: 6px;">
                            <i class="fas fa-lock mr-1"></i> Edit Scheme (Locked)
                        </button>
                        <small class="text-muted ml-2">Contact system administrator to change the global grading scheme.</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                    <div class="card-body">
                        <h5><i class="fas fa-calculator mr-2 text-warning"></i> Division Calculation</h5>
                        <hr>
                        <div class="mb-3">
                            <div class="small font-weight-bold text-muted text-uppercase mb-1">Division I</div>
                            <div class="font-weight-bold">7 - 17 Points</div>
                        </div>
                        <div class="mb-3">
                            <div class="small font-weight-bold text-muted text-uppercase mb-1">Division II</div>
                            <div class="font-weight-bold">18 - 21 Points</div>
                        </div>
                        <div class="mb-3">
                            <div class="small font-weight-bold text-muted text-uppercase mb-1">Division III</div>
                            <div class="font-weight-bold">22 - 25 Points</div>
                        </div>
                        <div class="mb-3">
                            <div class="small font-weight-bold text-muted text-uppercase mb-1">Division IV</div>
                            <div class="font-weight-bold">26 - 33 Points</div>
                        </div>
                        <div class="mb-0">
                            <div class="small font-weight-bold text-muted text-uppercase mb-1">Division 0</div>
                            <div class="font-weight-bold">34 - 35 Points</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
