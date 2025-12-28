@extends('layouts.app')

@section('title', 'Buat Laporan - Pilih Gedung')

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
        width: 25%;
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
    
    .search-container {
        margin-bottom: 2rem;
    }
    
    .search-input {
        position: relative;
    }
    
    .search-input i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-color);
    }
    
    .search-input input {
        padding-left: 50px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        font-size: 1rem;
        transition: var(--transition);
    }
    
    .search-input input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .buildings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex: 1;
    }
    
    .building-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .building-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }
    
    .building-card.selected {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    
    .building-card.selected::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        border-radius: 0 12px 0 40px;
    }
    
    .building-card.selected::after {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .building-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .building-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }
    
    .building-address {
        color: var(--gray-color);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    
    .building-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--gray-color);
    }
    
    .building-meta i {
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
        .buildings-grid {
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
                    <div class="step-circle {{ $index === 0 ? 'active' : '' }}" id="stepCircle{{ $index + 1 }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="step-label {{ $index === 0 ? 'active' : '' }}">
                        {{ $step }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    
    <div class="card step-card">
        <div class="card-body">
            <div class="step-header">
                <h1 class="step-title">Langkah 1: Pilih Gedung</h1>
                <p class="step-subtitle">
                    Pilih gedung tempat fasilitas yang bermasalah berada
                </p>
            </div>
            
            
            <div class="search-container">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" 
                           class="form-control" 
                           placeholder="Cari nama gedung..." 
                           id="searchBuilding">
                </div>
            </div>
            
            
            <form action="{{ route('reports.create-step2') }}" method="POST" id="buildingForm">
                @csrf
                
                <div class="buildings-grid" id="buildingsGrid">
                    @foreach($buildings as $building)
                        <div class="building-card" data-building-id="{{ $building['id'] }}">
                            <div class="building-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="building-title">{{ $building['name'] }}</h3>
                            <p class="building-address">{{ $building['address'] }}</p>
                            <div class="building-meta">
                                <span>
                                    <i class="fas fa-map-marker-alt"></i>
                                    Kampus Utama
                                </span>
                                <span>
                                    <i class="fas fa-door-closed"></i>
                                    {{ count(app('App\Services\SupabaseService')->getRooms($building['id'])) }} Ruangan
                                </span>
                            </div>
                            <input type="radio" 
                                   name="building_id" 
                                   value="{{ $building['id'] }}" 
                                   id="building{{ $building['id'] }}"
                                   style="display: none;">
                        </div>
                    @endforeach
                </div>
                
                
                <button type="submit" style="display: none;"></button>
            </form>
            
            
            <div class="empty-state" id="emptyState" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h4 class="empty-text">Gedung tidak ditemukan</h4>
                <button type="button" class="btn btn-outline-primary" id="resetSearch">
                    Tampilkan Semua Gedung
                </button>
            </div>
            
            
            <div class="step-footer">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Batal
                </a>
                <button type="button" class="btn btn-primary" id="nextButton" disabled>
                    Lanjut ke Ruangan
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
        const buildingCards = document.querySelectorAll('.building-card');
        const buildingForm = document.getElementById('buildingForm');
        const nextButton = document.getElementById('nextButton');
        const searchInput = document.getElementById('searchBuilding');
        const emptyState = document.getElementById('emptyState');
        const buildingsGrid = document.getElementById('buildingsGrid');
        const resetSearch = document.getElementById('resetSearch');
        
        let selectedBuilding = null;
        
        
        buildingCards.forEach(card => {
            card.addEventListener('click', function() {
                const buildingId = this.getAttribute('data-building-id');
                const radioInput = document.getElementById(`building${buildingId}`);
                
                
                buildingCards.forEach(c => c.classList.remove('selected'));
                
                
                this.classList.add('selected');
                radioInput.checked = true;
                
                selectedBuilding = buildingId;
                nextButton.disabled = false;
                nextButton.classList.remove('btn-secondary');
                nextButton.classList.add('btn-primary');
            });
        });
        
        
        nextButton.addEventListener('click', function() {
            if (selectedBuilding) {
                buildingForm.submit();
            }
        });
        
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            buildingCards.forEach(card => {
                const title = card.querySelector('.building-title').textContent.toLowerCase();
                const address = card.querySelector('.building-address').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || address.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            
            if (visibleCount === 0 && searchTerm) {
                emptyState.style.display = 'block';
                buildingsGrid.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                buildingsGrid.style.display = 'grid';
            }
            
            
            if (selectedBuilding) {
                const selectedCard = document.querySelector(`[data-building-id="${selectedBuilding}"]`);
                if (selectedCard.style.display === 'none') {
                    selectedCard.classList.remove('selected');
                    selectedBuilding = null;
                    nextButton.disabled = true;
                    nextButton.classList.remove('btn-primary');
                    nextButton.classList.add('btn-secondary');
                }
            }
        });
        
        
        resetSearch.addEventListener('click', function() {
            searchInput.value = '';
            buildingCards.forEach(card => {
                card.style.display = 'block';
            });
            emptyState.style.display = 'none';
            buildingsGrid.style.display = 'grid';
        });
        
        
        buildingCards.forEach(card => {
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