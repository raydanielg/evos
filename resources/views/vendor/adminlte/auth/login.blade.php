@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('css')
    <style>
        :root {
            --necta-green: #0b7a3c;
            --necta-green-strong: #075c2d;
        }
        .evos-login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .evos-login-brand img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.75);
            padding: 5px;
        }
        .evos-login-brand-title {
            margin: 0;
            font-weight: 800;
            font-size: 16px;
            line-height: 1.15;
            color: #111827;
        }
        .evos-login-brand-sub {
            margin: 3px 0 0;
            font-size: 12px;
            color: #6b7280;
        }
        .evos-login-card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .evos-login-card .card-header {
            background: #ffffff;
            border-bottom: 0;
            padding-top: 18px;
            padding-bottom: 0;
        }
        .evos-login-card .card-body {
            padding-top: 18px;
        }
        .evos-login-card .input-group .form-control {
            border-radius: 10px;
        }
        .evos-login-card .input-group-append .input-group-text {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .evos-login-btn {
            border-radius: 10px;
            font-weight: 700;
        }
        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--necta-green);
            border-color: var(--necta-green);
        }
        .btn-primary:active,
        .btn-primary.active {
            background-color: var(--necta-green-strong) !important;
            border-color: var(--necta-green-strong) !important;
        }
        .evos-btn-loading {
            pointer-events: none;
            opacity: 0.92;
        }
        .evos-btn-loading .evos-btn-spinner {
            display: inline-block;
        }
        .evos-btn-spinner {
            display: none;
            width: 14px;
            height: 14px;
            margin-right: 8px;
            border: 2px solid rgba(255, 255, 255, 0.55);
            border-top-color: rgba(255, 255, 255, 1);
            border-radius: 50%;
            animation: evos-spin 0.8s linear infinite;
            vertical-align: -2px;
        }
        @keyframes evos-spin {
            to {
                transform: rotate(360deg);
            }
        }
        .evos-login-muted-link {
            color: #374151;
        }
        .evos-login-muted-link:hover {
            color: #111827;
            text-decoration: underline;
        }
        .evos-eye-btn {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid #ced4da;
            border-left: 0;
            padding: 0 12px;
            color: rgba(17, 24, 39, 0.65);
        }
        .evos-eye-btn:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_brand')
    <div class="text-center w-100 mb-2">
        <img src="{{ asset('eco-e.png') }}" alt="Evos Logo" style="width: 64px; height: 64px; object-fit: contain;">
        <h4 class="mt-2" style="font-weight: 800; color: #111827;">Education Virtual Operating System (Evos)</h4>
    </div>
@stop

@section('auth_header')
    <span class="text-muted">Sign in to start your session</span>
@stop

@section('auth_body')
    @php
        $cardClasses = trim((string) config('adminlte.classes_auth_card', 'card-outline card-primary'));
        config(['adminlte.classes_auth_card' => str_replace(['card-outline', 'card-primary'], '', $cardClasses).' evos-login-card']);
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible alert-adminlte">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <ul class="mb-0 px-3" style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $loginUrl }}" method="post" id="evos-login-form">
        @csrf

        {{-- Email / Phone field --}}
        <div class="input-group mb-3">
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror @error('phone') is-invalid @enderror"
                value="{{ old('email') ?? old('phone') }}" placeholder="Email or phone" autofocus required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" id="evos-login-password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Password" required>

            <div class="input-group-append">
                <button type="button" class="btn evos-eye-btn" id="evos-login-password-toggle">
                    <span class="fas fa-eye"></span>
                </button>
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block evos-login-btn {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}" id="evos-login-submit">
                    <span class="evos-btn-spinner" aria-hidden="true"></span>
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        (function () {
            var form = document.getElementById('evos-login-form');
            var btn = document.getElementById('evos-login-submit');
            var pass = document.getElementById('evos-login-password');
            var toggle = document.getElementById('evos-login-password-toggle');
            if (!form || !btn) return;

            if (pass && toggle) {
                toggle.addEventListener('click', function () {
                    var isHidden = pass.getAttribute('type') === 'password';
                    pass.setAttribute('type', isHidden ? 'text' : 'password');
                    toggle.innerHTML = isHidden ? '<span class="fas fa-eye-slash"></span>' : '<span class="fas fa-eye"></span>';
                });
            }

            form.addEventListener('submit', function () {
                btn.classList.add('evos-btn-loading');
                btn.setAttribute('disabled', 'disabled');
            });
        })();
    </script>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a class="evos-login-muted-link" href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a class="evos-login-muted-link" href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
