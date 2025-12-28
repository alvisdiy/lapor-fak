@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('styles')
<style>
    .detail-header {
        margin-bottom: 2rem;
    }
    
    .report-main-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }
    
    .report-subtitle {
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .report-code-badge {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .report-main-card {
        margin-bottom: 2rem;
    }
    
    .status-timeline {
        padding: 1.5rem;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    
    .timeline-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }
    
    .timeline-step {
        text-align: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.2rem;
        color: white;
        background: #e2e8f0;
        transition: var(--transition);
    }
    
    .step-icon.active {
        background: var(--primary-color);
        transform: scale(1.1);
    }
    
    .step-icon.completed {
        background: #10b981;
    }
    
    .step-label {
        font-size: 0.85rem;
        color: var(--gray-color);
        font-weight: 500;
    }
    
    .step-date {
        font-size: 0.75rem;
        color: var(--gray-color);
        margin-top: 4px;
    }
    
    .info-section {
        margin-bottom: 2rem;
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
    
    .description-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }
    
    .description-content {
        line-height: 1.8;
        color: var(--dark-color);
        white-space: pre-wrap;
    }
    
    .photo-gallery {
        margin-bottom: 2rem;
    }
    
    .gallery-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .photo-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        height: 200px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .photo-item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 1rem;
        color: white;
        opacity: 0;
        transition: var(--transition);
    }
    
    .photo-item:hover .photo-overlay {
        opacity: 1;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .modal-image {
        width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    
    @media (max-width: 768px) {
        .timeline-steps {
            flex-direction: column;
            align-items: flex-start;
            gap: 2rem;
        }
        
        .timeline-steps::before {
            display: none;
        }
        
        .timeline-step {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
            width: 100%;
        }
        
        .step-icon {
            margin: 0;
            flex-shrink: 0;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div data-aos="fade-up">
    
    <div class="detail-header">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="report-main-title">{{ $report['title'] ?? 'Detail Laporan' }}</h1>
                <div class="report-subtitle">
                    @if($report['report_code'] ?? false)
                        <span class="report-code-badge">{{ $report['report_code'] }}</span>
                    @endif
                    <span class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($report['created_at'])->format('d F Y, H:i') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        
        
        @php
            $statusColors = [
                'Dikirim' => ['bg' => 'linear-gradient(135deg, #ffd166 0%, #fbbf24 100%)', 'color' => '#78350f'],
                'Diproses' => ['bg' => 'linear-gradient(135deg, #06d6a0 0%, #059669 100%)', 'color' => 'white'],
                'Selesai' => ['bg' => 'linear-gradient(135deg, #118ab2 0%, #0d9488 100%)', 'color' => 'white'],
                'Ditolak' => ['bg' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', 'color' => 'white'],
            ];
            $currentStatus = $report['status'] ?? 'Dikirim';
        @endphp
        
        <div class="d-inline-block p-3 rounded" style="background: {{ $statusColors[$currentStatus]['bg'] }}; color: {{ $statusColors[$currentStatus]['color'] }};">
            <i class="fas fa-circle me-2"></i>
            <strong>Status: {{ $currentStatus }}</strong>
        </div>
    </div>
    
    
    <div class="status-timeline" data-aos="fade-up" data-aos-delay="100">
        <h3 class="timeline-title">
            <i class="fas fa-stream"></i>
            Timeline Status
        </h3>
        
        <div class="timeline-steps">
            @php
                $steps = [
                    'Dikirim' => ['icon' => 'paper-plane', 'label' => 'Dikirim', 'date' => $report['created_at']],
                    'Diproses' => ['icon' => 'cog', 'label' => 'Diproses', 'date' => null],
                    'Selesai' => ['icon' => 'check-circle', 'label' => 'Selesai', 'date' => null],
                ];
                
                $currentStep = array_search($currentStatus, array_keys($steps));
                $currentStep = $currentStep !== false ? $currentStep : 0;
            @endphp
            
            @foreach($steps as $key => $step)
                @php
                    $stepStatus = '';
                    $stepDate = null;
                    
                    if ($key === $currentStatus) {
                        $stepStatus = 'active';
                        $stepDate = $report['created_at'];
                    } elseif (array_search($key, array_keys($steps)) < $currentStep) {
                        $stepStatus = 'completed';
                        $stepDate = $report['created_at'];
                    }
                @endphp
                
                <div class="timeline-step">
                    <div class="step-icon {{ $stepStatus }}">
                        <i class="fas fa-{{ $step['icon'] }}"></i>
                    </div>
                    <div class="step-label">{{ $step['label'] }}</div>
                    @if($stepDate)
                        <div class="step-date">
                            {{ \Carbon\Carbon::parse($stepDate)->format('d M Y') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
    
    <div class="info-section" data-aos="fade-up" data-aos-delay="200">
        <h3 class="section-title">
            <i class="fas fa-info-circle"></i>
            Informasi Laporan
        </h3>
        
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon info-icon-1">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="info-title">Lokasi</h4>
                <p class="info-value">{{ $report['location'] ?? 'N/A' }}</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon info-icon-2">
                    <i class="fas fa-tools"></i>
                </div>
                <h4 class="info-title">Fasilitas</h4>
                <p class="info-value">{{ $report['facility'] ?? 'N/A' }}</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon info-icon-3">
                    <i class="fas fa-building"></i>
                </div>
                <h4 class="info-title">Gedung</h4>
                <p class="info-value">{{ $report['building_id'] ? 'Gedung ' . $report['building_id'] : 'N/A' }}</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon info-icon-4">
                    <i class="fas fa-door-closed"></i>
                </div>
                <h4 class="info-title">Ruangan</h4>
                <p class="info-value">{{ $report['room_id'] ? 'Ruangan ' . $report['room_id'] : 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    
    <div class="description-card" data-aos="fade-up" data-aos-delay="300">
        <h3 class="section-title">
            <i class="fas fa-align-left"></i>
            Deskripsi Kerusakan
        </h3>
        <div class="description-content">
            {{ $report['description'] ?? 'Tidak ada deskripsi' }}
        </div>
    </div>
    
    
    @php
        $photos = [];
        if (!empty($report['photo_urls'])) {
            $photos = json_decode($report['photo_urls'], true);
        } elseif (!empty($report['photo_url'])) {
            $photos = [$report['photo_url']];
        }
    @endphp
    
    @if(!empty($photos))
        <div class="photo-gallery" data-aos="fade-up" data-aos-delay="400">
            <h3 class="gallery-title">
                <i class="fas fa-images"></i>
                Foto Laporan
            </h3>
            
            <div class="gallery-grid">
                @foreach($photos as $index => $photo)
                    <div class="photo-item" data-bs-toggle="modal" data-bs-target="#photoModal{{ $index }}">
                        <img src="{{ asset($photo) }}" alt="Foto Laporan {{ $index + 1 }}">
                        <div class="photo-overlay">
                            <small>Foto {{ $index + 1 }}</small>
                        </div>
                    </div>
                    
                    
                    <div class="modal fade" id="photoModal{{ $index }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Foto Laporan - {{ $index + 1 }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset($photo) }}" class="modal-image" alt="Foto Laporan {{ $index + 1 }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    
    <div class="action-buttons" data-aos="fade-up" data-aos-delay="500">
        <a href="{{ route('reports.edit', $report['id']) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i> Edit Laporan
        </a>
        
        <form action="{{ route('reports.destroy', $report['id']) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i> Hapus Laporan
            </button>
        </form>
        
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const photoItems = document.querySelectorAll('.photo-item');
        photoItems.forEach(item => {
            item.addEventListener('click', function() {
                const modalId = this.getAttribute('data-bs-target');
                const modal = new bootstrap.Modal(document.querySelector(modalId));
                modal.show();
            });
        });
        
        
        const timelineSteps = document.querySelectorAll('.timeline-step');
        timelineSteps.forEach((step, index) => {
            setTimeout(() => {
                step.style.opacity = '1';
                step.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
</script>
@endsection