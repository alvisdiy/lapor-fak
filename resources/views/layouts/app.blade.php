<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Layanan Pengaduan')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #3a0ca3;
            --accent-color: #7209b7;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f94144;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
            --radius: 12px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
            backdrop-filter: blur(10px);
            border-bottom: none;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            font-size: 1.8rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }
        
        .btn-logout {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white !important;
            padding: 8px 20px;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Main Content */
        main {
            flex: 1;
            padding: 2rem 0;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Button Styling */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Form Styling */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }
        
        /* Table Styling */
        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(67, 97, 238, 0.03);
            --bs-table-hover-bg: rgba(67, 97, 238, 0.05);
        }
        
        .table th {
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 2px solid var(--primary-light);
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        
        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: var(--shadow);
            padding: 1rem 1.25rem;
        }
        
        /* Badge Styling */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        
        .status-dikirim { 
            background: linear-gradient(135deg, #ffd166 0%, #fbbf24 100%);
            color: #78350f;
        }
        
        .status-diproses { 
            background: linear-gradient(135deg, #06d6a0 0%, #059669 100%);
            color: white;
        }
        
        .status-selesai { 
            background: linear-gradient(135deg, #118ab2 0%, #0d9488 100%);
            color: white;
        }
        
        .status-ditolak { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #0f172a 100%);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        /* Custom Utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-light) 0%, #f0f9ff 100%);
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand span {
                font-size: 1.2rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-building-cog"></i>
                <span>Layanan Pengaduan</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-clipboard-list me-1"></i> Laporan Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                            <i class="fas fa-user-circle me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt me-1"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    
    <main class="fade-in">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    
    <footer data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <p class="text-light mb-0">
                        Sistem pelaporan fasilitas kampus yang efisien dan modern.
                        Dukung pengelolaan fasilitas kampus yang lebih baik.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light mb-0">
                        © {{ date('Y') }} Layanan Pengaduan. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
        
        
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
    
    @yield('scripts')
</body>
</html>