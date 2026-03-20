@extends('adminlte::master')

@php($dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home'))

@if (config('adminlte.use_route_url', false))
    @php($dashboard_url = $dashboard_url ? route($dashboard_url) : '')
@else
    @php($dashboard_url = $dashboard_url ? url($dashboard_url) : '')
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ $dashboard_url }}">
                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
            </a>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h3 class="card-title float-none">Welcome to Evos</h3>
            </div>

            <div class="card-body login-card-body">
                <div class="text-center mb-4">
                    <h4>Education Virtual Operating System</h4>
                    <p class="text-muted">Explore our learning platform</p>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('login') }}" class="btn btn-block btn-primary mb-2">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('register') }}" class="btn btn-block btn-outline-secondary">
                            <i class="fas fa-user-plus mr-2"></i> Register New Account
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <p class="my-0 text-center">
                    <a href="/">Back to Home</a>
                </p>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
