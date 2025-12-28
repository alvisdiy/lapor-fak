@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('styles')
<style>
    .edit-header {
        margin-bottom: 2rem;
    }
    
    .edit-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }
    
    .edit-subtitle {
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
    
    .edit-card {
        margin-bottom: 2rem;
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
    
    .form-section {
        margin-bottom: 2rem;
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
    
    /* Photo Gallery Styles */
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
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }
    
    .photo-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        height: 180px;
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
    
    .photo-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        gap: 4px;
    }
    
    .photo-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: var(--dark-color);
        font-size: 0.8rem;
        transition: var(--transition);
        cursor: pointer;
    }
    
    .photo-btn:hover {
        background: white;
        transform: scale(1.1);
    }
    
    .photo-btn-delete {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }
    
    .photo-btn-delete:hover {
        background: #dc3545;
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
    
    /* Upload New Photos */
    .photo-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }
    
    .photo-upload-area:hover, .photo-upload-area.dragover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    
    .upload-icon {
        font-size: 2.5rem;
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
    
    .new-photos-preview {
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
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }
    
    /* Status Badge */
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
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
<div data-aos="fade-up">
    
    <div class="edit-header">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="edit-title">Edit Laporan</h1>
                <div class="edit-subtitle">
                    @if($report['report_code'] ?? false)
                        <span class="report-code-badge">{{ $report['report_code'] }}</span>
                    @endif
                    <span class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Terakhir diupdate: {{ \Carbon\Carbon::parse($report['updated_at'])->format('d F Y, H:i') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('reports.show', $report['id']) }}" class="btn btn-outline-secondary">
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
        
        <div class="status-badge" style="background: {{ $statusColors[$currentStatus]['bg'] }}; color: {{ $statusColors[$currentStatus]['color'] }};">
            <i class="fas fa-circle me-1"></i>
            Status: {{ $currentStatus }}
        </div>
    </div>
    
    
    <div class="card edit-card">
        <div class="card-body">
            <form action="{{ route('reports.update', $report['id']) }}" method="POST" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')
                
                
                <div class="info-section">
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
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4 class="info-title">Judul Laporan</h4>
                            <p class="info-value">{{ $report['title'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-icon info-icon-4">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="info-title">Dibuat Pada</h4>
                            <p class="info-value">
                                {{ \Carbon\Carbon::parse($report['created_at'])->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-align-left"></i>
                        Deskripsi Kerusakan
                    </h3>
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Deskripsi Lengkap
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control textarea-description" 
                                  id="description" 
                                  name="description" 
                                  rows="5"
                                  placeholder="Jelaskan kerusakan atau masalah yang terjadi..."
                                  required>{{ old('description', $report['description'] ?? '') }}</textarea>
                        <div class="form-text">
                            Minimal 10 karakter. Jelaskan dengan detail agar tim maintenance dapat memahami masalahnya.
                        </div>
                        <div class="text-end mt-1">
                            <small class="text-muted" id="charCounter">0 karakter</small>
                        </div>
                    </div>
                </div>
                
                
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-sync-alt"></i>
                        Status Laporan
                    </h3>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Dikirim" {{ old('status', $report['status']) == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="Diproses" {{ old('status', $report['status']) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ old('status', $report['status']) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ old('status', $report['status']) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <div class="form-text">
                            Perbarui status laporan sesuai perkembangan terbaru
                        </div>
                    </div>
                </div>
                
                
                @php
                    
                    $existingPhotos = [];
                    if (!empty($report['photo_urls'])) {
                        $existingPhotos = json_decode($report['photo_urls'], true);
                    } elseif (!empty($report['photo_url'])) {
                        $existingPhotos = [$report['photo_url']];
                    }
                    
                    
                    $deletedPhotos = [];
                @endphp
                
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-images"></i>
                        Foto Laporan
                    </h3>
                    
                    
                    <input type="hidden" id="deleted_photos" name="deleted_photos" value="">
                    
                    @if(!empty($existingPhotos))
                        <div class="photo-gallery">
                            <h4 class="gallery-title">
                                <i class="fas fa-photo-video"></i>
                                Foto yang Sudah Diupload
                            </h4>
                            
                            <div class="gallery-grid" id="existingPhotosGrid">
                                @foreach($existingPhotos as $index => $photo)
                                    <div class="photo-item" data-photo-url="{{ $photo }}">
                                        <img src="{{ asset($photo) }}" alt="Foto Laporan {{ $index + 1 }}">
                                        <div class="photo-actions">
                                            <a href="{{ asset($photo) }}" target="_blank" class="photo-btn" title="Lihat di tab baru">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <button type="button" class="photo-btn photo-btn-delete" 
                                                    onclick="markPhotoForDeletion('{{ $photo }}', this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="photo-overlay">
                                            <small>Foto {{ $index + 1 }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada foto yang diupload untuk laporan ini.
                        </div>
                    @endif
                    
                    
                    <div class="new-photos-section">
                        <h4 class="gallery-title">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Tambah Foto Baru
                        </h4>
                        
                        <div class="photo-upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4 class="upload-text">Klik untuk menambah foto baru</h4>
                            <p class="upload-subtext">
                                Seret dan lepas atau klik untuk memilih file
                            </p>
                            <p class="upload-subtext mb-0">
                                Format: JPG, PNG, GIF, WebP (maks. 5MB per foto)
                            </p>
                            <input type="file" 
                                   class="file-input" 
                                   id="new_photos" 
                                   name="new_photos[]" 
                                   accept="image/*"
                                   multiple>
                        </div>
                        
                        
                        <div class="new-photos-preview" id="newPhotosPreview" style="display: none;">
                            <h5 class="preview-title">Foto baru yang akan ditambahkan:</h5>
                            <div class="preview-grid" id="newPhotosGrid">
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="action-buttons">
                    <a href="{{ route('reports.show', $report['id']) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i> Hapus Laporan
                    </button>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
                <p>Apakah Anda yakin ingin menghapus laporan ini?</p>
                <p class="text-muted small">Semua data laporan termasuk foto akan dihapus permanen.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('reports.destroy', $report['id']) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('new_photos');
        const newPhotosPreview = document.getElementById('newPhotosPreview');
        const newPhotosGrid = document.getElementById('newPhotosGrid');
        const editForm = document.getElementById('editForm');
        const submitBtn = document.getElementById('submitBtn');
        const descriptionTextarea = document.getElementById('description');
        const deletedPhotosInput = document.getElementById('deleted_photos');
        
        let selectedFiles = [];
        let deletedPhotos = [];
        const maxSize = 5 * 1024 * 1024; 
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
                    errors.push(`${file.name}: Ukuran file terlalu besar (maks. 5MB)`);
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
            newPhotosGrid.innerHTML = '';
            
            if (selectedFiles.length === 0) {
                newPhotosPreview.style.display = 'none';
                return;
            }
            
            newPhotosPreview.style.display = 'block';
            
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
                    
                    newPhotosGrid.appendChild(previewItem);
                    
                    
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
        
        
        window.markPhotoForDeletion = function(photoUrl, button) {
            const photoItem = button.closest('.photo-item');
            
            if (!deletedPhotos.includes(photoUrl)) {
                deletedPhotos.push(photoUrl);
                photoItem.style.opacity = '0.5';
                photoItem.style.border = '2px solid #dc3545';
                button.innerHTML = '<i class="fas fa-undo"></i>';
                button.classList.remove('photo-btn-delete');
                button.classList.add('photo-btn-success');
                button.style.background = '#198754';
                button.title = 'Batalkan penghapusan';
                
                
                deletedPhotosInput.value = JSON.stringify(deletedPhotos);
                
                button.setAttribute('onclick', `unmarkPhotoForDeletion('${photoUrl}', this)`);
            }
        };
        
        
        window.unmarkPhotoForDeletion = function(photoUrl, button) {
            const index = deletedPhotos.indexOf(photoUrl);
            if (index > -1) {
                deletedPhotos.splice(index, 1);
                const photoItem = button.closest('.photo-item');
                photoItem.style.opacity = '1';
                photoItem.style.border = 'none';
                button.innerHTML = '<i class="fas fa-trash"></i>';
                button.classList.remove('photo-btn-success');
                button.classList.add('photo-btn-delete');
                button.style.background = '';
                button.title = 'Hapus foto';
                
                
                deletedPhotosInput.value = JSON.stringify(deletedPhotos);
                
                button.setAttribute('onclick', `markPhotoForDeletion('${photoUrl}', this)`);
            }
        };
        
        
        descriptionTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const counter = document.getElementById('charCounter');
            
            if (counter) {
                counter.textContent = `${length} karakter`;
                
                if (length < 10) {
                    counter.style.color = '#dc2626';
                } else if (length < 50) {
                    counter.style.color = '#f59e0b';
                } else {
                    counter.style.color = '#10b981';
                }
            }
        });
        
        
        descriptionTextarea.dispatchEvent(new Event('input'));
        
        
        editForm.addEventListener('submit', function(e) {
            
            const description = descriptionTextarea.value.trim();
            if (!description || description.length < 10) {
                e.preventDefault();
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
            
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
        });
        
        
        window.confirmDelete = function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        };
    });
</script>
@endsection