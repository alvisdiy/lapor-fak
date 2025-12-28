@extends('layouts.app')

@section('title', 'Buat Laporan - Pilih Ruangan')

@section('styles')
<style>
    .step-progress {
        margin-bottom: 3rem;
    }
    
    .progress-container {
        position: relative;
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        margin-bottom: 2rem;
    }
    
    .progress-bar {
        position: absolute;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 2px;
        transition: width 0.5s ease;
        width: 50%;
    }
    
    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: -10px;
    }
    
    .progress-step {
        text-align: center;
        position: relative;
        z-index: 2;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 600;
        color: var(--gray-color);
        transition: var(--transition);
    }
    
    .step-circle.active {
        border-color: var(--primary-color);
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }
    
    .step-circle.completed {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }
    
    .step-label {
        font-size: 0.9rem;
        color: var(--gray-color);
        font-weight: 500;
    }
    
    .step-label.active {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .step-card {
        min-height: 500px;
        display: flex;
        flex-direction: column;
    }
    
    .step-header {
        margin-bottom: 2rem;
    }
    
    .step-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }
    
    .step-subtitle {
        color: var(--gray-color);
        font-size: 1.1rem;
    }
    
    .building-info {
        background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
    }
    
    .building-name {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .building-address {
        color: var(--gray-color);
        font-size: 0.95rem;
    }
    
    .rooms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex: 1;
    }
    
    .room-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .room-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .room-card.selected {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    
    .room-card.selected::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        border-radius: 0 12px 0 40px;
    }
    
    .room-card.selected::after {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .room-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .room-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }
    
    .room-details {
        color: var(--gray-color);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    
    .room-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--gray-color);
    }
    
    .room-meta i {
        margin-right: 4px;
    }
    
    .step-footer {
        margin-top: auto;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        grid-column: 1 / -1;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-text {
        color: var(--gray-color);
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .rooms-grid {
            grid-template-columns: 1fr;
        }
        
        .step-footer {
            flex-direction: column;
            gap: 1rem;
        }
        
        .step-footer .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div data-aos="fade-up">
    
    <div class="step-progress">
        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        
        <div class="progress-steps">
            @foreach(['Pilih Gedung', 'Pilih Ruangan', 'Pilih Fasilitas', 'Lengkapi Detail'] as $index => $step)
                <div class="progress-step">
                    <div class="step-circle {{ $index <= 1 ? ($index === 1 ? 'active' : 'completed') : '' }}" 
                         id="stepCircle{{ $index + 1 }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="step-label {{ $index === 1 ? 'active' : '' }}">
                        {{ $step }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    
    <div class="card step-card">
        <div class="card-body">
            <div class="step-header">
                <h1 class="step-title">Langkah 2: Pilih Ruangan</h1>
                <p class="step-subtitle">
                    Pilih ruangan di gedung {{ $building['name'] }} tempat fasilitas bermasalah
                </p>
            </div>
            
            
            <div class="building-info">
                <h3 class="building-name">
                    <i class="fas fa-building"></i>
                    {{ $building['name'] }}
                </h3>
                <p class="building-address">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $building['address'] }}
                </p>
            </div>
            
            
            <form action="{{ route('reports.create-step3') }}" method="POST" id="roomForm">
                @csrf
                <input type="hidden" name="building_id" value="{{ $building['id'] }}">
                
                <div class="rooms-grid" id="roomsGrid">
                    @if(count($rooms) > 0)
                        @foreach($rooms as $room)
                            <div class="room-card" data-room-id="{{ $room['id'] }}">
                                <div class="room-icon">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                                <h3 class="room-title">{{ $room['name'] }}</h3>
                                <p class="room-details">
                                    Gedung {{ $room['building'] }}, {{ $room['floor'] }}
                                </p>
                                <div class="room-meta">
                                    <span>
                                        <i class="fas fa-building"></i>
                                        Gedung {{ $room['building'] }}
                                    </span>
                                    <span>
                                        <i class="fas fa-layer-group"></i>
                                        {{ $room['floor'] }}
                                    </span>
                                </div>
                                <input type="radio" 
                                       name="room_id" 
                                       value="{{ $room['id'] }}" 
                                       id="room{{ $room['id'] }}"
                                       style="display: none;">
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-door-closed"></i>
                            </div>
                            <h4 class="empty-text">Tidak ada ruangan tersedia</h4>
                            <a href="{{ route('reports.create-step1') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i> Pilih Gedung Lain
                            </a>
                        </div>
                    @endif
                </div>
                
                
                <button type="submit" style="display: none;"></button>
            </form>
            
            
            <div class="step-footer">
                <a href="{{ route('reports.create-step1') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                
                @if(count($rooms) > 0)
                    <button type="button" class="btn btn-primary" id="nextButton" disabled>
                        Lanjut ke Fasilitas
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomCards = document.querySelectorAll('.room-card');
        const roomForm = document.getElementById('roomForm');
        const nextButton = document.getElementById('nextButton');
        
        let selectedRoom = null;
        
        
        roomCards.forEach(card => {
            card.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const radioInput = document.getElementById(`room${roomId}`);
                
                
                roomCards.forEach(c => c.classList.remove('selected'));
                
                
                this.classList.add('selected');
                radioInput.checked = true;
                
                selectedRoom = roomId;
                
                if (nextButton) {
                    nextButton.disabled = false;
                    nextButton.classList.remove('btn-secondary');
                    nextButton.classList.add('btn-primary');
                }
            });
        });
        
        
        if (nextButton) {
            nextButton.addEventListener('click', function() {
                if (selectedRoom) {
                    roomForm.submit();
                }
            });
        }
        
        
        roomCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = 'var(--shadow-hover)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                }
            });
        });
    });
</script>
@endsection