@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    .dashboard-welcome {
        background: linear-gradient(135deg, var(--primary-light) 0%, #f0f9ff 100%);
        border-radius: var(--radius);
        padding: 2rem;
        margin-bottom: 2rem;
        border-left: 5px solid var(--primary-color);
    }
    
    .welcome-text {
        font-size: 1.1rem;
        color: var(--dark-color);
        margin-bottom: 0;
    }
    
    .welcome-text strong {
        color: var(--primary-color);
    }
    
    .stats-card {
        padding: 1.5rem;
        text-align: center;
        transition: var(--transition);
        height: 100%;
        border-radius: var(--radius);
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .stats-icon-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stats-icon-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stats-icon-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--dark-color) 0%, #4a5568 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-label {
        color: var(--gray-color);
        font-weight: 500;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .quick-actions {
        margin: 2rem 0;
    }
    
    .action-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        text-align: center;
        height: 100%;
        transition: var(--transition);
        border: 1px solid #e2e8f0;
    }
    
    .action-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .action-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
        color: white;
    }
    
    .action-icon-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .action-icon-view {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    
    .action-icon-profile {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .action-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }
    
    .action-desc {
        color: var(--gray-color);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .recent-reports {
        margin-top: 2rem;
    }
    
    .report-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        background: white;
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
    }
    
    .report-item:hover {
        background: var(--primary-light);
        transform: translateX(5px);
    }
    
    .report-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        flex-shrink: 0;
    }
    
    .report-content {
        flex: 1;
    }
    
    .report-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: var(--dark-color);
    }
    
    .report-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--gray-color);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
    
    .empty-state-text {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    
    .chart-container {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        height: 100%;
    }
    
    .chart-title {
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--dark-color);
    }
</style>
@endsection

@section('content')
<div data-aos="fade-up">
    
    <div class="dashboard-welcome">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2">Selamat Datang, <strong>{{ Session::get('user.full_name', 'Pengguna') }}</strong>!</h1>
                <p class="welcome-text">
                    Ini adalah ringkasan aktivitas pelaporan fasilitas Anda. 
                    Pantau perkembangan laporan dan buat laporan baru untuk fasilitas yang bermasalah.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('reports.create-step1') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Buat Laporan Baru
                </a>
            </div>
        </div>
    </div>
</div>


<div class="row mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-md-4 mb-4">
        <div class="card stats-card">
            <div class="stats-icon stats-icon-1">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-number">{{ $stats['total_this_month'] }}</div>
            <div class="stats-label">Laporan Bulan Ini</div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card stats-card">
            <div class="stats-icon stats-icon-2">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-number">{{ $stats['completed'] }}</div>
            <div class="stats-label">Laporan Selesai</div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card stats-card">
            <div class="stats-icon stats-icon-3">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">{{ $stats['pending'] }}</div>
            <div class="stats-label">Menunggu Tindakan</div>
        </div>
    </div>
</div>


<div class="row quick-actions" data-aos="fade-up" data-aos-delay="200">


<div class="card recent-reports" data-aos="fade-up" data-aos-delay="300">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Laporan Terbaru</h5>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body">
        @if(count($reports) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>JUDUL LAPORAN</th>
                            <th>LOKASI</th>
                            <th>STATUS</th>
                            <th>TANGGAL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr data-aos="fade-right" data-aos-delay="{{ $loop->index * 50 }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="report-icon" style="background: linear-gradient(135deg, {{ $report['status'] == 'Selesai' ? '#10b981' : ($report['status'] == 'Diproses' ? '#3b82f6' : '#f59e0b') }} 0%, {{ $report['status'] == 'Selesai' ? '#059669' : ($report['status'] == 'Diproses' ? '#1d4ed8' : '#d97706') }} 100%);">
                                        <i class="fas fa-{{ $report['status'] == 'Selesai' ? 'check' : ($report['status'] == 'Diproses' ? 'cog' : 'paper-plane') }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ Str::limit($report['title'], 40) }}</div>
                                        <small class="text-muted">{{ $report['report_code'] ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ Str::limit($report['location'], 25) }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($report['status']) }}">
                                    {{ $report['status'] }}
                                </span>
                            </td>
                            <td class="text-muted">
                                {{ \Carbon\Carbon::parse($report['created_at'])->diffForHumans() }}
                            </td>
                            <td>
                                <a href="{{ route('reports.show', $report['id']) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5 class="empty-state-text">Belum ada laporan yang dibuat</h5>
                <p class="text-muted mb-4">Mulai dengan membuat laporan pertama Anda</p>
                <a href="{{ route('reports.create-step1') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const statsNumbers = document.querySelectorAll('.stats-number');
        statsNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            let currentValue = 0;
            const increment = finalValue / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(currentValue);
            }, 30);
        });
    });
</script>
@endsection