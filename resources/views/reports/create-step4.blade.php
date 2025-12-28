@extends('layouts.app')

@section('title', 'Buat Laporan - Lengkapi Detail')

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
        width: 100%;
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
    
    .selection-summary {
        background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
    }
    
    .summary-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .summary-item {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .summary-label {
        font-size: 0.85rem;
        color: var(--gray-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 1.1rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        transition: var(--transition);
        font-size: 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .textarea-description {
        min-height: 150px;
        resize: vertical;
    }
    
    .photo-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: var(--transition);
        margin-bottom: 1rem;
    }
    
    .photo-upload-area:hover, .photo-upload-area.dragover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    
    .upload-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .upload-text {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .upload-subtext {
        color: var(--gray-color);
        font-size: 0.9rem;
    }
    
    .file-input {
        display: none;
    }
    
    .photo-preview {
        margin-top: 1.5rem;
    }
    
    .preview-title {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 1rem;
    }
    
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        height: 120px;
    }
    
    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    
    .step-footer {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .confirmation-alert {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #10b981;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .confirmation-title {
        font-weight: 600;
        color: #065f46;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .confirmation-text {
        color: #047857;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
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
                    <div class="step-circle {{ $index === 3 ? 'active' : 'completed' }}" 
                         id="stepCircle{{ $index + 1 }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="step-label {{ $index === 3 ? 'active' : '' }}">
                        {{ $step }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    
    <div class="card step-card">
        <div class="card-body">
            <div class="step-header">
                <h1 class="step-title">Langkah 4: Lengkapi Detail Laporan</h1>
                <p class="step-subtitle">
                    Tambahkan deskripsi dan foto untuk melengkapi laporan Anda
                </p>
            </div>
            
            @if(session('report_data'))
                @php
                    $building_id = session('report_data.building_id');
                    $room_id = session('report_data.room_id');
                    $facility_id = session('report_data.facility_id');
                    
                    
                    $buildings = app('App\Services\SupabaseService')->getBuildings();
                    $building = collect($buildings)->where('id', $building_id)->first();
                    
                    
                    $rooms = app('App\Services\SupabaseService')->getRooms($building_id);
                    $room = collect($rooms)->where('id', $room_id)->first();
                    
                    
                    $facilities = app('App\Services\SupabaseService')->getFacilities();
                    $facility = collect($facilities)->where('id', $facility_id)->first();
                @endphp
                
                
                <div class="selection-summary">
                    <h3 class="summary-title">
                        <i class="fas fa-clipboard-check"></i>
                        Ringkasan Pilihan Anda
                    </h3>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-label">Gedung</div>
                            <div class="summary-value">{{ $building['name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Ruangan</div>
                            <div class="summary-value">{{ $room['name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Fasilitas</div>
                            <div class="summary-value">{{ $facility['name'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                
                
                <div class="confirmation-alert">
                    <h4 class="confirmation-title">
                        <i class="fas fa-check-circle"></i>
                        Hampir Selesai!
                    </h4>
                    <p class="confirmation-text">
                        Pastikan semua informasi yang Anda masukkan sudah benar. Setelah dikirim, 
                        laporan akan diproses oleh tim maintenance dan Anda dapat memantau perkembangannya 
                        di halaman "Laporan Saya".
                    </p>
                </div>
                
                
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
                    @csrf
                    
                    <input type="hidden" name="building_id" value="{{ $building_id }}">
                    <input type="hidden" name="room_id" value="{{ $room_id }}">
                    <input type="hidden" name="facility_id" value="{{ $facility_id }}">
                    
                    
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-align-left"></i>
                            Deskripsi Kerusakan
                        </h3>
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Jelaskan kerusakan atau masalah yang terjadi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control textarea-description" 
                                      id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Contoh: AC di ruangan ini tidak mengeluarkan udara dingin dan mengeluarkan suara berisik..."
                                      required></textarea>
                            <div class="form-text">
                                Jelaskan dengan detail agar tim maintenance dapat memahami masalahnya dengan baik.
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-camera"></i>
                            Tambahkan Foto (Opsional)
                        </h3>
                        
                        <div class="photo-upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4 class="upload-text">Klik untuk mengunggah foto</h4>
                            <p class="upload-subtext">
                                Unggah foto kerusakan untuk membantu tim maintenance memahami masalah
                            </p>
                            <p class="upload-subtext mb-0">
                                Format: JPG, PNG, GIF, WebP (maks. 2MB per foto)
                            </p>
                            <input type="file" 
                                   class="file-input" 
                                   id="photos" 
                                   name="photos[]" 
                                   accept="image/*"
                                   multiple>
                        </div>
                        
                        
                        <div class="photo-preview" id="photoPreview" style="display: none;">
                            <h5 class="preview-title">Foto yang akan diunggah:</h5>
                            <div class="preview-grid" id="previewGrid">
                                
                            </div>
                        </div>
                    </div>
                    
                    
                    <button type="submit" style="display: none;"></button>
                </form>
                
                
                <div class="step-footer">
                    <a href="{{ route('reports.create-step3') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                    </button>
                </div>
            @else
                
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <h5 class="alert-heading mb-2">Sesi Telah Berakhir</h5>
                            <p class="mb-0">Data laporan tidak ditemukan. Silakan mulai dari awal.</p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('reports.create-step1') }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i> Mulai dari Awal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('photos');
        const photoPreview = document.getElementById('photoPreview');
        const previewGrid = document.getElementById('previewGrid');
        const reportForm = document.getElementById('reportForm');
        const submitButton = document.getElementById('submitButton');
        const descriptionTextarea = document.getElementById('description');
        
        let selectedFiles = [];
        const maxSize = 2 * 1024 * 1024; 
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('dragover');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('dragover');
        }
        
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        
        function handleFiles(files) {
            const validFiles = [];
            const errors = [];
            
            for (let file of files) {
                
                if (!allowedTypes.includes(file.type)) {
                    errors.push(`${file.name}: Tipe file tidak didukung`);
                    continue;
                }
                
                
                if (file.size > maxSize) {
                    errors.push(`${file.name}: Ukuran file terlalu besar (maks. 2MB)`);
                    continue;
                }
                
                validFiles.push(file);
            }
            
            
            if (errors.length > 0) {
                alert('Kesalahan upload:\n' + errors.join('\n'));
            }
            
            
            validFiles.forEach(file => {
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });
            
            updatePreview();
        }
        
        
        function updatePreview() {
            previewGrid.innerHTML = '';
            
            if (selectedFiles.length === 0) {
                photoPreview.style.display = 'none';
                return;
            }
            
            photoPreview.style.display = 'block';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" class="preview-img" alt="Preview">
                        <button type="button" class="remove-btn" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    previewGrid.appendChild(previewItem);
                    
                    
                    previewItem.querySelector('.remove-btn').addEventListener('click', function() {
                        removeFile(index);
                    });
                };
                
                reader.readAsDataURL(file);
            });
        }
        
        
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
        }
        
        
        submitButton.addEventListener('click', function() {
            
            const description = descriptionTextarea.value.trim();
            if (!description || description.length < 10) {
                alert('Deskripsi kerusakan harus diisi minimal 10 karakter');
                descriptionTextarea.focus();
                return;
            }
            
            
            if (selectedFiles.length > 0) {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            }
            
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
            
            
            reportForm.submit();
        });
        
        
        descriptionTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const counter = document.getElementById('charCounter') || 
                           (function() {
                               const counter = document.createElement('div');
                               counter.id = 'charCounter';
                               counter.className = 'form-text text-end';
                               this.parentElement.appendChild(counter);
                               return counter;
                           }).call(this);
            
            counter.textContent = `${length} karakter`;
            
            if (length < 10) {
                counter.style.color = '#dc2626';
            } else if (length < 50) {
                counter.style.color = '#f59e0b';
            } else {
                counter.style.color = '#10b981';
            }
        });
        
        
        descriptionTextarea.dispatchEvent(new Event('input'));
    });
</script>
@endsection