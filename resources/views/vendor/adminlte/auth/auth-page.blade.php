@extends('adminlte::master')

@php
    $authType = $authType ?? 'login';
    $dashboardUrl = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');

    if (config('adminlte.use_route_url', false)) {
        $dashboardUrl = $dashboardUrl ? route($dashboardUrl) : '';
    } else {
        $dashboardUrl = $dashboardUrl ? url($dashboardUrl) : '';
    }

    $bodyClasses = "{$authType}-page";

    if (! empty(config('adminlte.layout_dark_mode', null))) {
        $bodyClasses .= ' dark-mode';
    }
@endphp

@section('adminlte_css')
    <style>
        :root {
            --evos-green: #0b7a3c;
            --evos-green-strong: #075c2d;
        }
        .evos-auth-shell {
            min-height: 100vh;
        }
        .evos-auth-left {
            background: #e9ecef;
            position: relative;
            overflow: hidden;
        }
        .evos-auth-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(
                    -45deg,
                    rgba(11, 122, 60, 0.08) 0,
                    rgba(11, 122, 60, 0.08) 1px,
                    transparent 1px,
                    transparent 22px
                );
            opacity: 0.55;
            transform: translate3d(0, 0, 0);
            animation: evos-auth-stripes 18s linear infinite;
            pointer-events: none;
        }
        .evos-auth-left::after {
            content: '';
            position: absolute;
            inset: -40px;
            background-image: radial-gradient(rgba(17, 24, 39, 0.08) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: 0.35;
            transform: translate3d(0, 0, 0);
            animation: evos-auth-dots 26s linear infinite;
            pointer-events: none;
        }
        @keyframes evos-auth-stripes {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 340px 0;
            }
        }
        @keyframes evos-auth-dots {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 0 320px;
            }
        }
        .evos-auth-form-wrap {
            position: relative;
            z-index: 1;
        }
        .evos-auth-right {
            background-color: #0b3d2e;
            background-image:
                radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 18px 18px;
        }
        .evos-auth-right-inner {
            max-width: 680px;
        }
        .evos-auth-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            letter-spacing: 0.06em;
            font-size: 11px;
            text-transform: uppercase;
        }
        .evos-auth-title {
            color: #ffffff;
            font-weight: 800;
            line-height: 1.1;
        }
        .evos-auth-title-accent {
            color: #f59e0b;
        }
        .evos-auth-lead {
            color: rgba(255, 255, 255, 0.78);
            font-size: 14px;
            line-height: 1.6;
            margin-top: 14px;
        }
        .evos-auth-feature-title {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .evos-auth-feature-text {
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            line-height: 1.5;
        }
        .evos-auth-form-wrap {
            width: 100%;
            max-width: 420px;
        }
        .evos-auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
        }
        .evos-auth-logo img {
            display: inline-block;
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        .evos-auth-shell .card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        }
        .evos-auth-shell .input-group {
            background-color: #f3f4f6;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .evos-auth-shell .input-group:focus-within {
            background-color: #ffffff;
            border-color: var(--evos-green);
            box-shadow: 0 0 0 3px rgba(11, 122, 60, 0.1);
        }
        .evos-auth-shell .form-control {
            border-radius: 14px !important;
            background-color: transparent !important;
            border: none !important;
            padding: 12px 16px;
            height: auto;
            box-shadow: none !important;
        }
        .evos-auth-shell .input-group-text {
            background-color: transparent !important;
            border: none !important;
            color: #6b7280;
            min-width: 46px;
            justify-content: center;
            padding: 0;
        }
        .evos-eye-btn {
            background-color: transparent !important;
            border: none !important;
            padding: 0 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 46px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .evos-eye-btn:hover {
            color: #374151;
        }
        .evos-eye-btn:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        .evos-auth-shell select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }
        .evos-auth-shell .input-group-append {
            margin-left: 0;
            display: flex;
            align-items: center;
        }
        .evos-auth-shell .btn-primary {
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .alert-adminlte {
            border-radius: 14px;
            border: none;
            margin-bottom: 20px;
        }
        .evos-auth-shell .input-group > .form-control:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .evos-auth-shell .input-group-append .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ "hold-transition {$bodyClasses}" }}@stop

@section('body')
    <div class="container-fluid p-0 evos-auth-shell">
        <div class="row no-gutters evos-auth-shell">

            <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center py-5 evos-auth-left">
                <div class="evos-auth-form-wrap px-4">

                    <div class="evos-auth-logo">
                        <a href="{{ $dashboardUrl }}" class="text-decoration-none">

                            @hasSection('auth_brand')
                                @yield('auth_brand')
                            @else

                            @if (config('adminlte.auth_logo.enabled', false))
                                <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                                     alt="{{ config('adminlte.auth_logo.img.alt') }}"
                                     @if (config('adminlte.auth_logo.img.class', null))
                                        class="{{ config('adminlte.auth_logo.img.class') }}"
                                     @endif
                                     @if (config('adminlte.auth_logo.img.width', null))
                                        width="{{ config('adminlte.auth_logo.img.width') }}"
                                     @endif
                                     @if (config('adminlte.auth_logo.img.height', null))
                                        height="{{ config('adminlte.auth_logo.img.height') }}"
                                     @endif>
                            @else
                                <img src="{{ asset(config('adminlte.logo_img')) }}" alt="{{ config('adminlte.logo_img_alt') }}" height="44">
                            @endif

                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}

                            @endif
                        </a>
                    </div>

                    <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

                        @hasSection('auth_header')
                            <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                                <h3 class="card-title float-none text-center">
                                    @yield('auth_header')
                                </h3>
                            </div>
                        @endif

                        <div class="card-body {{ $authType }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                            @yield('auth_body')
                        </div>

                        @hasSection('auth_footer')
                            <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                                @yield('auth_footer')
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="d-none d-lg-flex col-lg-7 evos-auth-right align-items-center">
                <div class="p-5 evos-auth-right-inner">
                    <div class="mb-4">
                        <span class="evos-auth-badge">EVOS PLATFORM</span>
                    </div>

                    <h1 class="evos-auth-title mb-0">
                        Manage Learning,
                        <span class="evos-auth-title-accent">Generate Reports</span>,
                        Improve Performance.
                    </h1>

                    <p class="evos-auth-lead">
                        Education Virtual Operating System (Evos) helps institutions manage learning activities,
                        track progress, and export trusted reports with confidence.
                    </p>

                    <div class="row mt-4">
                        <div class="col-12 col-md-6">
                            <div class="evos-auth-feature-title">Learning Management</div>
                            <div class="evos-auth-feature-text">
                                Organize courses, assignments and assessments across classes in one place.
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                            <div class="evos-auth-feature-title">Exports & Sharing</div>
                            <div class="evos-auth-feature-text">
                                Download reports in PDF/Excel and share results with stakeholders securely.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
