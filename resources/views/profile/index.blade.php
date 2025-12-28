@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 3rem 2rem;
        border-radius: var(--radius) var(--radius) 0 0;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1%, transparent 1%);
        background-size: 30px 30px;
        opacity: 0.3;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 4px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 2;
    }
    
    .profile-avatar i {
        font-size: 3.5rem;
        color: white;
    }
    
    .profile-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .profile-role {
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }
    
    .profile-body {
        padding: 2.5rem;
    }
    
    .info-section {
        margin-bottom: 2.5rem;
    }
    
    .section-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-light);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: var(--transition);
    }
    
    .info-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        color: white;
    }
    
    .info-icon-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .info-icon-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .info-icon-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .info-icon-4 { background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); }
    
    .info-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .info-value {
        color: var(--gray-color);
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1rem;
        }
        
        .profile-body {
            padding: 1.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="card" data-aos="fade-up">
    
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <h1 class="profile-name">{{ $user['full_name'] ?? 'Nama Pengguna' }}</h1>
        <p class="profile-role">Mahasiswa Aktif</p>
    </div>
    
    
    <div class="profile-body">
        
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-id-card text-primary"></i>
                Informasi Pribadi
            </h3>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon info-icon-1">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4 class="info-title">Nama Lengkap</h4>
                    <p class="info-value">{{ $user['full_name'] ?? 'N/A' }}</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon info-icon-2">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <h4 class="info-title">NIM</h4>
                    <p class="info-value">{{ $user['nim'] ?? 'N/A' }}</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon info-icon-3">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4 class="info-title">Program Studi</h4>
                    <p class="info-value">{{ $user['program_studi'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        
        <div class="action-buttons">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Keluar dari Akun
                </button>
            </form>
        </div>
    </div>
</div>


<div class="card mt-4" data-aos="fade-up" data-aos-delay="200">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-shield-alt me-2 text-primary"></i>
            Keamanan Akun
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-medium mb-2">Tips Keamanan Akun</h6>
                <ul class="text-muted mb-0">
                    <li>Jangan berikan kredensial login Anda kepada siapapun</li>
                    <li>Selalu logout setelah menggunakan aplikasi</li>
                    <li>Laporkan aktivitas mencurigakan kepada administrator</li>
                </ul>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-inline-block p-3 rounded bg-light">
                    <i class="fas fa-lock fa-2x text-primary mb-2"></i>
                    <p class="mb-0 small text-muted">Akun Terlindungi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            let currentValue = 0;
            const increment = finalValue / 30;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(currentValue);
            }, 50);
        });
    });
</script>
@endsection