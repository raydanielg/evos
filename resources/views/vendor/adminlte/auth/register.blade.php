@extends('adminlte::auth.auth-page', ['authType' => 'register'])

@section('css')
    <style>
        :root {
            --necta-green: #0b7a3c;
            --necta-green-strong: #075c2d;
        }
        .evos-register-card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .evos-register-card .input-group .form-control {
            border-radius: 10px;
        }
        .evos-register-card .input-group-append .input-group-text {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
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
        .evos-register-btn {
            border-radius: 10px;
            font-weight: 700;
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
        .evos-auth-link {
            color: #374151;
        }
        .evos-auth-link:hover {
            color: #111827;
            text-decoration: underline;
        }
        .evos-brand-title {
            font-weight: 800;
            font-size: 16px;
            line-height: 1.15;
            color: #111827;
        }
        .evos-auth-logo img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.75);
            padding: 5px;
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
        .evos-hint {
            font-size: 12px;
            margin-top: -4px;
        }
    </style>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
    }
@endphp

@section('auth_brand')
    <img src="{{ asset('eco-e.png') }}" alt="Evos Logo">
    <span class="evos-brand-title">Education Virtual Operating System (Evos)</span>
@stop

@section('auth_header')
    <span class="text-muted">Create a new account</span>
@stop

@section('auth_body')
    @php
        $cardClasses = trim((string) config('adminlte.classes_auth_card', 'card-outline card-primary'));
        config(['adminlte.classes_auth_card' => str_replace('card-outline card-primary', '', $cardClasses).' evos-register-card']);
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible alert-adminlte">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <ul class="mb-0 px-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $registerUrl }}" method="post" id="evos-register-form">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Full Name" autofocus required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Email Address" required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        {{-- Phone field --}}
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}" placeholder="Phone number (e.g. 07xxxxxxxx)" required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
        </div>

        {{-- Region & District --}}
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <select name="region_id" id="region_id" class="form-control @error('region_id') is-invalid @enderror" required>
                        <option value="">Select Region</option>
                        @foreach(\App\Models\Region::all() as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-map-marker-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <select name="district_id" id="district_id" class="form-control @error('district_id') is-invalid @enderror" required disabled>
                        <option value="">Select District</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-map-marker-alt"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" id="evos-register-password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Password" required>

            <div class="input-group-append">
                <button type="button" class="btn evos-eye-btn" id="evos-register-password-toggle">
                    <span class="fas fa-eye"></span>
                </button>
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="evos-hint text-muted mb-3" id="evos-register-password-strength"></div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" id="evos-register-password-confirm"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Retype password" required>

            <div class="input-group-append">
                <button type="button" class="btn evos-eye-btn" id="evos-register-password-confirm-toggle">
                    <span class="fas fa-eye"></span>
                </button>
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="evos-hint mb-3" id="evos-register-password-match"></div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block evos-register-btn btn-primary" id="evos-register-submit">
            <span class="evos-btn-spinner" aria-hidden="true"></span>
            Register
        </button>
    </form>
@stop

@section('js')
    <script>
        (function () {
            var form = document.getElementById('evos-register-form');
            var btn = document.getElementById('evos-register-submit');
            var regionSelect = document.getElementById('region_id');
            var districtSelect = document.getElementById('district_id');

            // Handle Region change to load Districts
            if (regionSelect && districtSelect) {
                regionSelect.addEventListener('change', function() {
                    var regionId = this.value;
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    districtSelect.disabled = true;

                    if (regionId) {
                        fetch('/api/districts/' + regionId)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(district => {
                                    var option = document.createElement('option');
                                    option.value = district.id;
                                    option.text = district.name;
                                    districtSelect.add(option);
                                });
                                districtSelect.disabled = false;
                            });
                    }
                });
            }

            var pass = document.getElementById('evos-register-password');
            var passToggle = document.getElementById('evos-register-password-toggle');
            var pass2 = document.getElementById('evos-register-password-confirm');
            var pass2Toggle = document.getElementById('evos-register-password-confirm-toggle');
            var strengthEl = document.getElementById('evos-register-password-strength');
            var matchEl = document.getElementById('evos-register-password-match');
            if (!form || !btn) return;

            function toggleVisibility(input, toggleBtn) {
                if (!input || !toggleBtn) return;
                toggleBtn.addEventListener('click', function () {
                    var isHidden = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isHidden ? 'text' : 'password');
                    toggleBtn.innerHTML = isHidden ? '<span class="fas fa-eye-slash"></span>' : '<span class="fas fa-eye"></span>';
                });
            }

            function updateStrength() {
                if (!pass || !strengthEl) return;
                var v = pass.value || '';
                if (!v) {
                    strengthEl.textContent = '';
                    return;
                }
                var score = 0;
                if (v.length >= 8) score++;
                if (/[A-Za-z]/.test(v)) score++;
                if (/[0-9]/.test(v)) score++;
                if (/[^A-Za-z0-9]/.test(v)) score++;

                var label = 'Weak';
                var cls = 'text-danger';
                if (score >= 3) {
                    label = 'Good';
                    cls = 'text-success';
                } else if (score === 2) {
                    label = 'Medium';
                    cls = 'text-warning';
                }

                strengthEl.className = 'evos-hint ' + cls;
                strengthEl.textContent = 'Password strength: ' + label;
            }

            function updateMatch() {
                if (!pass || !pass2 || !matchEl) return;
                var v1 = pass.value || '';
                var v2 = pass2.value || '';
                if (!v1 && !v2) {
                    matchEl.textContent = '';
                    matchEl.className = 'evos-hint';
                    return;
                }
                if (v2.length === 0) {
                    matchEl.textContent = '';
                    matchEl.className = 'evos-hint';
                    return;
                }
                var ok = v1 === v2;
                matchEl.className = 'evos-hint ' + (ok ? 'text-success' : 'text-danger');
                matchEl.textContent = ok ? 'Passwords match' : 'Passwords do not match';
            }

            toggleVisibility(pass, passToggle);
            toggleVisibility(pass2, pass2Toggle);

            if (pass) {
                pass.addEventListener('input', function () {
                    updateStrength();
                    updateMatch();
                });
            }
            if (pass2) {
                pass2.addEventListener('input', updateMatch);
            }

            form.addEventListener('submit', function () {
                btn.classList.add('evos-btn-loading');
                btn.setAttribute('disabled', 'disabled');
            });
        })();
    </script>
@stop

@section('auth_footer')
    <p class="my-0">
        <a class="evos-auth-link" href="{{ $loginUrl }}">
            {{ __('adminlte::adminlte.i_already_have_a_membership') }}
        </a>
    </p>
@stop
