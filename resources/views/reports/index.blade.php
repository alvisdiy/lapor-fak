@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--gray-color);
        font-size: 1rem;
    }
    
    .filter-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }
    
    .filter-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-options {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: var(--dark-color);
        font-weight: 500;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    
    .filter-btn:hover, .filter-btn.active {
        border-color: var(--primary-color);
        background: var(--primary-light);
        color: var(--primary-color);
    }
    
    .filter-btn .count {
        background: #e2e8f0;
        color: var(--dark-color);
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .filter-btn.active .count {
        background: var(--primary-color);
        color: white;
    }
    
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .report-card {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid #e2e8f0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-color);
    }
    
    .report-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
    }
    
    .report-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .report-code {
        font-size: 0.85rem;
        color: var(--primary-color);
        font-weight: 500;
        display: inline-block;
        padding: 4px 10px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 20px;
    }
    
    .report-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .report-details {
        margin-bottom: 1.5rem;
        flex: 1;
    }
    
    .report-detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
        color: white;
        font-size: 0.9rem;
    }
    
    .detail-icon.location { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .detail-icon.facility { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .detail-icon.date { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    
    .detail-content {
        flex: 1;
    }
    
    .detail-label {
        font-size: 0.8rem;
        color: var(--gray-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    
    .detail-value {
        font-weight: 500;
        color: var(--dark-color);
        font-size: 0.95rem;
    }
    
    .report-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .report-status {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .report-actions {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary-color);
        border: none;
        transition: var(--transition);
    }
    
    .action-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
    
    .empty-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .empty-description {
        color: var(--gray-color);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .pagination .page-item .page-link {
        border: none;
        color: var(--dark-color);
        padding: 8px 16px;
        border-radius: 8px;
        margin: 0 4px;
        transition: var(--transition);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--primary-color);
        color: white;
    }
    
    .pagination .page-item .page-link:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<div data-aos="fade-up">
    
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="page-title">Laporan Saya</h1>
                <p class="page-subtitle">
                    Kelola dan pantau semua laporan fasilitas yang telah Anda buat
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('reports.create-step1') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Buat Laporan Baru
                </a>
            </div>
        </div>
    </div>
    
    <div class="filter-card" data-aos="fade-up" data-aos-delay="100">
        <h3 class="filter-title">
            <i class="fas fa-filter"></i>
            Filter Status
        </h3>
        <div class="filter-options">
            @php
                
                $totalAll = count($allReports);
                $totalDikirim = collect($allReports)->where('status', 'Dikirim')->count();
                $totalDiproses = collect($allReports)->where('status', 'Diproses')->count();
                $totalSelesai = collect($allReports)->where('status', 'Selesai')->count();
                $totalDitolak = collect($allReports)->where('status', 'Ditolak')->count();
            @endphp
            
            
            <button type="button" onclick="filterReports('all')" 
                    class="filter-btn {{ !request()->has('status') || request('status') == 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                Semua
                <span class="count">{{ $totalAll }}</span>
            </button>
            
            
            <button type="button" onclick="filterReports('Dikirim')" 
                    class="filter-btn {{ request('status') == 'Dikirim' ? 'active' : '' }}">
                <i class="fas fa-paper-plane"></i>
                Dikirim
                <span class="count">{{ $totalDikirim }}</span>
            </button>
            
            
            <button type="button" onclick="filterReports('Diproses')" 
                    class="filter-btn {{ request('status') == 'Diproses' ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                Diproses
                <span class="count">{{ $totalDiproses }}</span>
            </button>
            
            
            <button type="button" onclick="filterReports('Selesai')" 
                    class="filter-btn {{ request('status') == 'Selesai' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                Selesai
                <span class="count">{{ $totalSelesai }}</span>
            </button>
            
            
            <button type="button" onclick="filterReports('Ditolak')" 
                    class="filter-btn {{ request('status') == 'Ditolak' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i>
                Ditolak
                <span class="count">{{ $totalDitolak }}</span>
            </button>
        </div>
    </div>
    
    
    @if(count($reports) > 0)
        <div class="reports-grid">
            @foreach($reports as $report)
                @php
                    $statusColors = [
                        'Dikirim' => ['bg' => 'linear-gradient(135deg, #ffd166 0%, #fbbf24 100%)', 'color' => '#78350f'],
                        'Diproses' => ['bg' => 'linear-gradient(135deg, #06d6a0 0%, #059669 100%)', 'color' => 'white'],
                        'Selesai' => ['bg' => 'linear-gradient(135deg, #118ab2 0%, #0d9488 100%)', 'color' => 'white'],
                        'Ditolak' => ['bg' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', 'color' => 'white'],
                    ];
                @endphp
                
                <div class="report-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="report-header">
                        <h3 class="report-title">{{ Str::limit($report['title'], 50) }}</h3>
                        <span class="report-code">{{ $report['report_code'] ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="report-body">
                        <div class="report-details">
                            <div class="report-detail-item">
                                <div class="detail-icon location">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Lokasi</div>
                                    <div class="detail-value">{{ Str::limit($report['location'], 40) }}</div>
                                </div>
                            </div>
                            
                            <div class="report-detail-item">
                                <div class="detail-icon facility">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Fasilitas</div>
                                    <div class="detail-value">{{ $report['facility'] }}</div>
                                </div>
                            </div>
                            
                            <div class="report-detail-item">
                                <div class="detail-icon date">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Dilaporkan</div>
                                    <div class="detail-value">
                                        {{ \Carbon\Carbon::parse($report['created_at'])->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="report-footer">
                        <span class="report-status" style="background: {{ $statusColors[$report['status']]['bg'] }}; color: {{ $statusColors[$report['status']]['color'] }};">
                            {{ $report['status'] }}
                        </span>
                        
                        <div class="report-actions">
                            <a href="{{ route('reports.show', $report['id']) }}" class="action-btn" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('reports.edit', $report['id']) }}" class="action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('reports.destroy', $report['id']) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        
        @if(request()->has('status') && request('status') != 'all')
            <div class="alert alert-info d-flex align-items-center justify-content-between" data-aos="fade-up">
                <div>
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan laporan dengan status: <strong>{{ request('status') }}</strong>
                    ({{ count($reports) }} dari {{ $totalAll }} laporan)
                </div>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-times me-1"></i> Hapus Filter
                </a>
            </div>
        @endif
    @else
        <div class="empty-state" data-aos="fade-up">
            <div class="empty-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="empty-title">
                @if(request()->has('status') && request('status') != 'all')
                    Tidak Ada Laporan dengan Status "{{ request('status') }}"
                @else
                    Belum Ada Laporan
                @endif
            </h3>
            <p class="empty-description">
                @if(request()->has('status') && request('status') != 'all')
                    Anda tidak memiliki laporan dengan status "{{ request('status') }}".
                @else
                    Anda belum membuat laporan apapun. Mulai dengan melaporkan kerusakan atau masalah fasilitas kampus.
                @endif
            </p>
            <a href="{{ route('reports.create-step1') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Buat Laporan Pertama
            </a>
            @if(request()->has('status') && request('status') != 'all')
                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-list me-2"></i> Lihat Semua Laporan
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    
    function filterReports(status) {
        if (status === 'all') {
            window.location.href = "{{ route('reports.index') }}";
        } else {
            window.location.href = "{{ route('reports.index') }}?status=" + status;
        }
    }
    
    
    document.addEventListener('DOMContentLoaded', function() {
        const reportCards = document.querySelectorAll('.report-card');
        reportCards.forEach(card => {
            card.addEventListener('click', function(e) {
                
                if (!e.target.closest('.action-btn')) {
                    const viewLink = this.querySelector('a[href*="/reports/"]');
                    if (viewLink) {
                        window.location.href = viewLink.href;
                    }
                }
            });
        });
        
        
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status') || 'all';
        document.querySelectorAll('.filter-btn').forEach(btn => {
            const btnText = btn.textContent.trim().replace(/\d+/g, '').trim();
            if ((statusParam === 'all' && btnText === 'Semua') || 
                btnText.toLowerCase().includes(statusParam.toLowerCase())) {
                btn.classList.add('active');
            }
        });
    });
</script>
@endsection