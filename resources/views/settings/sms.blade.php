@extends('adminlte::page')

@section('title', 'SMS Settings')

@section('content_header')
    <h1>SMS Gateway Settings</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="card card-success card-outline shadow-sm" style="border-radius: 10px;">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title font-weight-bold">Gateway Configuration</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info py-2 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-info-circle mr-2"></i> Current balance: <strong>0 SMS Units</strong>
                        </div>

                        <form action="#" method="POST" onsubmit="return false;">
                            <div class="form-group">
                                <label for="api_key">API Key</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="api_key" value="••••••••••••••••" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sender_id">Sender ID (Header)</label>
                                <input type="text" class="form-control" id="sender_id" value="ANGELINA" readonly>
                                <small class="text-muted">Requested Sender ID for your school.</small>
                            </div>

                            <div class="form-group">
                                <label>Automatic Notifications</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input" id="autoResults" disabled>
                                    <label class="custom-control-label" for="autoResults">Send results to parents immediately after processing</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input" id="autoAttendance" disabled>
                                    <label class="custom-control-label" for="autoAttendance">Send attendance alerts to parents</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white">
                        <button class="btn btn-success shadow-sm" disabled style="border-radius: 6px;">Save Settings</button>
                        <small class="text-muted ml-2">SMS module requires an active subscription.</small>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #e8f5e9;">
                    <div class="card-body">
                        <h5><i class="fas fa-paper-plane mr-2 text-success"></i> Integration Status</h5>
                        <hr>
                        <p class="text-muted small">Your SMS gateway is currently in <strong>Sandbox Mode</strong>. To start sending real messages to parents, please top up your account and activate the service.</p>
                        
                        <div class="mt-4">
                            <h6>Supported Providers:</h6>
                            <div class="d-flex flex-wrap mt-2" style="gap: 10px;">
                                <span class="badge badge-light border px-3 py-2">Beem SMS</span>
                                <span class="badge badge-light border px-3 py-2">NextSMS</span>
                                <span class="badge badge-light border px-3 py-2">Twilio</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <button class="btn btn-primary btn-sm px-4 shadow-sm" style="border-radius: 20px;">
                                <i class="fas fa-shopping-cart mr-1"></i> Buy SMS Units
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
