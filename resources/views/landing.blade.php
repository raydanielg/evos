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
    <link rel="icon" type="image/png" href="{{ asset('eco-e.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .hero-section {
            background-color: #064e3b; /* Dark Green */
            background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            color: white;
            padding: 20px 5% 80px 5%;
            position: relative;
            overflow: hidden;
        }
        .navbar-custom {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 60px;
            flex-wrap: wrap;
        }
        .nav-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .nav-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .nav-btn.active {
            background: rgba(255,255,255,0.2);
        }
        .hero-content {
            display: flex;
            align-items: center;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .hero-logo-container {
            background: #f39c12;
            padding: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .hero-logo-container img {
            height: 80px;
            width: 80px;
            object-fit: contain;
        }
        .hero-text h2 {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #f39c12;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .hero-text h1 {
            font-size: 64px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1;
        }
        .hero-text p.slogan {
            font-style: italic;
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        .btn-group-custom {
            display: flex;
            gap: 15px;
        }
        .btn-get-started {
            background: #f39c12;
            color: #064e3b;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
        }
        .btn-sign-in {
            border: 1px solid rgba(255,255,255,0.5);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
        }

        /* Description Section */
        .description-section {
            padding: 80px 5%;
            background: #f8f9fa;
            color: #333;
        }
        .desc-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            gap: 50px;
            align-items: center;
        }
        .desc-text {
            flex: 1;
        }
        .desc-text h3 {
            color: #064e3b;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .desc-text p {
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
        }
        .features-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #064e3b;
        }
        .feature-item i {
            color: #f39c12;
        }
        .desc-image {
            flex: 1;
            text-align: center;
        }
        .desc-image img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
        }

        /* Video Section Integration */
        .video-section {
            background: #fff;
            padding: 60px 5%;
        }
        .video-card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 30px;
            border-top: 4px solid #f39c12;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 10px;
            background: #000;
            margin-bottom: 20px;
        }
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .hero-content, .desc-container {
                flex-direction: column;
                text-align: center;
            }
            .hero-text h1 {
                font-size: 40px;
            }
            .btn-group-custom {
                justify-content: center;
            }
            .navbar-custom {
                justify-content: center;
            }
        }
    </style>
@stop

@section('body')
    <!-- Hero Section -->
    <div class="hero-section">
        <nav class="navbar-custom">
            @auth
                <a href="{{ url('/home') }}" class="nav-btn active"><i class="fas fa-tachometer-alt mr-1"></i> Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-btn active"><i class="fas fa-sign-in-alt mr-1"></i> Sign In</a>
            @endauth
        </nav>

        <div class="hero-content">
            <div class="hero-logo-container">
                <img src="{{ asset('eco-e.png') }}" alt="EVOS Logo">
            </div>
            <div class="hero-text">
                <h2>Smart Education Management</h2>
                <h1>EVOS PLUS</h1>
                <p class="slogan">"Empowering Teachers with Collaborative Digital Solutions"</p>
                <div class="btn-group-custom">
                    @auth
                        <a href="{{ url('/home') }}" class="btn-get-started"><i class="fas fa-tachometer-alt mr-1"></i> Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-get-started">Get Started</a>
                        <a href="{{ route('login') }}" class="btn-sign-in">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="description-section">
        <div class="desc-container">
            <div class="desc-text">
                <p><strong>EVOS Plus</strong> is a modern, web-based school collaboration system designed specifically for teachers to manage, share, and analyze students' academic performance across multiple schools.</p>
                <p>The system allows teachers from different schools to work together by contributing subject results into a unified platform. Initially focused on one or two subjects, EVOS Plus ensures accurate tracking of continuous assessment and performance trends.</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i> Manage results easily
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i> Multi-school collaboration
                    </div>
                </div>
            </div>
            <div class="desc-image">
                <!-- Using a placeholder or the same logo if person image not available -->
                <img src="{{ asset('eco-e.png') }}" alt="Collaboration" style="max-height: 400px; opacity: 0.8;">
            </div>
        </div>
    </div>

    <!-- Video Integration -->
    <div class="video-section" id="videoSection">
        <div class="video-card">
            <h4 class="text-center mb-4" style="color: #064e3b; font-weight: 700;">
                <i class="fas fa-play-circle mr-2"></i> Maelekezo ya Mfumo
            </h4>
            <div class="video-container">
                <video id="introVideo" controls poster="{{ asset('eco-e.png') }}">
                    <source src="{{ asset('evos.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="text-center">
                <a href="{{ asset('evos.mp4') }}" download class="btn btn-outline-success btn-sm">
                    <i class="fas fa-download mr-1"></i> Pakua Video ya Maelekezo
                </a>
                <p class="text-muted small mt-3">
                    Tazama video hii kuelewa jinsi EVOS Plus inavyorahisisha usimamizi wa matokeo.
                </p>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 bg-dark text-white-50">
        <small>&copy; {{ date('Y') }} EVOS Plus. Haki zote zimehifadhiwa.</small>
    </footer>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        function playSystemVideo() {
            const videoSection = document.getElementById('videoSection');
            const video = document.getElementById('introVideo');
            
            // Scroll to video section smoothly
            videoSection.scrollIntoView({ behavior: 'smooth' });
            
            // Wait for scroll to finish then play
            setTimeout(() => {
                video.play();
                // Optional: Request full screen
                // if (video.requestFullscreen) video.requestFullscreen();
            }, 800);
        }
    </script>
@stop
