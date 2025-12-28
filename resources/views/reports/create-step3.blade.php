@extends('layouts.app')

@section('title', 'Buat Laporan - Pilih Fasilitas')

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
        width: 75%;
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
    
    .selection-info {
        background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: var(--gray-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .info-value {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 1.1rem;
    }
    
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex: 1;
    }
    
    .facility-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .facility-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .facility-card.selected {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    
    .facility-card.selected::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        border-radius: 0 12px 0 40px;
    }
    
    .facility-card.selected::after {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .facility-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .facility-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }
    
    .facility-desc {
        color: var(--gray-color);
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .step-footer {
        margin-top: auto;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
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
                    <div class="step-circle {{ $index <= 2 ? ($index === 2 ? 'active' : 'completed') : '' }}" 
                         id="stepCircle{{ $index + 1 }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="step-label {{ $index === 2 ? 'active' : '' }}">
                        {{ $step }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    
    <div class="card step-card">
        <div class="card-body">
            <div class="step-header">
                <h1 class="step-title">Langkah 3: Pilih Fasilitas</h1>
                <p class="step-subtitle">
                    Pilih fasilitas yang mengalami kerusakan atau masalah
                </p>
            </div>
            
            
            @php
                $building = session('report_data.building_id');
                $room = session('report_data.room_id');
                
                
                $buildings = app('App\Services\SupabaseService')->getBuildings();
                $buildingInfo = collect($buildings)->where('id', $building)->first();
                
                $rooms = app('App\Services\SupabaseService')->getRooms($building);
                $roomInfo = collect($rooms)->where('id', $room)->first();
            @endphp
            
            <div class="selection-info">
                <div class="info-item">
                    <div class="info-label">Gedung</div>
                    <div class="info-value">{{ $buildingInfo['name'] ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Ruangan</div>
                    <div class="info-value">{{ $roomInfo['name'] ?? 'N/A' }}</div>
                </div>
            </div>
            
            
            <form action="{{ route('reports.create-step4') }}" method="POST" id="facilityForm">
                @csrf
                <input type="hidden" name="building_id" value="{{ $building }}">
                <input type="hidden" name="room_id" value="{{ $room }}">
                
                <div class="facilities-grid" id="facilitiesGrid">
                    @foreach($facilities as $facility)
                        <div class="facility-card" data-facility-id="{{ $facility['id'] }}">
                            <div class="facility-icon">
                                @switch($facility['name'])
                                    @case('Toilet')
                                        <i class="fas fa-toilet"></i>
                                        @break
                                    @case('AC')
                                        <i class="fas fa-snowflake"></i>
                                        @break
                                    @case('Proyektor')
                                        <i class="fas fa-video"></i>
                                        @break
                                    @case('Meja & Kursi')
                                        <i class="fas fa-chair"></i>
                                        @break
                                    @case('Pintu')
                                        <i class="fas fa-door-closed"></i>
                                        @break
                                    @case('Lampu')
                                        <i class="fas fa-lightbulb"></i>
                                        @break
                                    @case('Wi-Fi')
                                        <i class="fas fa-wifi"></i>
                                        @break
                                    @default
                                        <i class="fas fa-tools"></i>
                                @endswitch
                            </div>
                            <h3 class="facility-title">{{ $facility['name'] }}</h3>
                            @if(isset($facility['subtypes']))
                                <p class="facility-desc">
                                    {{ implode(', ', $facility['subtypes']) }}
                                </p>
                            @endif
                            <input type="radio" 
                                   name="facility_id" 
                                   value="{{ $facility['id'] }}" 
                                   id="facility{{ $facility['id'] }}"
                                   style="display: none;">
                        </div>
                    @endforeach
                </div>
                
                
                <button type="submit" style="display: none;"></button>
            </form>
            
            
            <div class="step-footer">
                <a href="{{ route('reports.create-step2') }}?building_id={{ $building }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                <button type="button" class="btn btn-primary" id="nextButton" disabled>
                    Lanjut ke Detail
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const facilityCards = document.querySelectorAll('.facility-card');
        const facilityForm = document.getElementById('facilityForm');
        const nextButton = document.getElementById('nextButton');
        
        let selectedFacility = null;
        
        
        facilityCards.forEach(card => {
            card.addEventListener('click', function() {
                const facilityId = this.getAttribute('data-facility-id');
                const radioInput = document.getElementById(`facility${facilityId}`);
                
                
                facilityCards.forEach(c => c.classList.remove('selected'));
                
                
                this.classList.add('selected');
                radioInput.checked = true;
                
                selectedFacility = facilityId;
                nextButton.disabled = false;
                nextButton.classList.remove('btn-secondary');
                nextButton.classList.add('btn-primary');
            });
        });
        
        
        nextButton.addEventListener('click', function() {
            if (selectedFacility) {
                facilityForm.submit();
            }
        });
        
        
        facilityCards.forEach(card => {
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