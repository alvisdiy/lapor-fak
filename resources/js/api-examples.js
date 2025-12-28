async function handleLogin(event) {
    event.preventDefault();
    
    const fullName = document.getElementById('full_name').value;
    const nim = document.getElementById('nim').value;
    
    try {
        const response = await api.login(fullName, nim);
        
        if (response.success) {
            localStorage.setItem('user', JSON.stringify(response.data.user));
            window.location.href = '/dashboard';
        }
    } catch (error) {
        alert('Login gagal: ' + error.message);
    }
}

async function loadReports(status = null) {
    try {
        const response = await api.getReports(status);
        const reportsList = document.getElementById('reports-list');
        
        if (response.success && response.data.length > 0) {
            reportsList.innerHTML = response.data.map(report => `
                <div class="report-card">
                    <h3>${report.title}</h3>
                    <p><strong>Status:</strong> ${report.status}</p>
                    <p><strong>Lokasi:</strong> ${report.location}</p>
                    <p><strong>Kode:</strong> ${report.report_code}</p>
                    <p>${report.description.substring(0, 100)}...</p>
                    <div class="actions">
                        <a href="/reports/${report.id}" class="btn btn-primary">Lihat Detail</a>
                        <button onclick="deleteReport(${report.id})" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            `).join('');
        } else {
            reportsList.innerHTML = '<p>Tidak ada laporan</p>';
        }
    } catch (error) {
        console.error('Error loading reports:', error);
        alert('Gagal memuat laporan');
    }
}

function filterByStatus() {
    const status = document.getElementById('status-filter').value;
    loadReports(status);
}

async function loadBuildings() {
    try {
        const response = await api.getBuildings();
        const select = document.getElementById('building_id');
        
        select.innerHTML = '<option value="">-- Pilih Gedung --</option>' +
            response.data.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading buildings:', error);
    }
}

async function loadRooms() {
    const buildingId = document.getElementById('building_id').value;
    
    if (!buildingId) return;
    
    try {
        const response = await api.getRooms(buildingId);
        const select = document.getElementById('room_id');
        
        select.innerHTML = '<option value="">-- Pilih Ruangan --</option>' +
            response.data.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading rooms:', error);
    }
}

async function loadFacilities() {
    try {
        const response = await api.getFacilities();
        const select = document.getElementById('facility_id');
        
        select.innerHTML = '<option value="">-- Pilih Fasilitas --</option>' +
            response.data.map(f => `<option value="${f.id}">${f.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading facilities:', error);
    }
}

async function submitReport(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        building_id: parseInt(formData.get('building_id')),
        room_id: parseInt(formData.get('room_id')),
        facility_id: parseInt(formData.get('facility_id')),
        description: formData.get('description')
    };
    
    try {
        const payload = new FormData();
        payload.append('building_id', data.building_id);
        payload.append('room_id', data.room_id);
        payload.append('facility_id', data.facility_id);
        payload.append('description', data.description);
        
        const photoFiles = document.getElementById('photos').files;
        for (let i = 0; i < photoFiles.length; i++) {
            payload.append('photos[]', photoFiles[i]);
        }
        
        const response = await fetch('/api/reports', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: payload
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Laporan berhasil dibuat!');
            window.location.href = '/dashboard';
        } else {
            alert('Gagal membuat laporan: ' + result.message);
        }
    } catch (error) {
        console.error('Error creating report:', error);
        alert('Terjadi kesalahan');
    }
}

async function loadReportDetail(reportId) {
    try {
        const response = await api.getReport(reportId);
        
        if (response.success) {
            const report = response.data;
            document.getElementById('report-title').textContent = report.title;
            document.getElementById('report-status').textContent = report.status;
            document.getElementById('report-location').textContent = report.location;
            document.getElementById('report-facility').textContent = report.facility;
            document.getElementById('report-code').textContent = report.report_code;
            document.getElementById('report-description').textContent = report.description;
            document.getElementById('report-date').textContent = new Date(report.created_at).toLocaleString('id-ID');
            
            // Load photos
            if (report.photo_urls) {
                const photos = JSON.parse(report.photo_urls);
                const photoContainer = document.getElementById('photos');
                photoContainer.innerHTML = photos.map(photo => 
                    `<img src="${photo}" alt="Photo" class="report-photo">`
                ).join('');
            }
        }
    } catch (error) {
        console.error('Error loading report:', error);
        alert('Gagal memuat laporan');
    }
}

async function loadReportForEdit(reportId) {
    try {
        const response = await api.getReportEditData(reportId);
        
        if (response.success) {
            const { report, buildings, rooms, facilities } = response.data;
            
            document.getElementById('report_id').value = report.id;
            document.getElementById('description').value = report.description;
            document.getElementById('status').value = report.status;
            
            document.getElementById('building_id').innerHTML = 
                buildings.map(b => 
                    `<option value="${b.id}" ${b.id === report.building_id ? 'selected' : ''}>${b.name}</option>`
                ).join('');
            
            document.getElementById('room_id').innerHTML = 
                rooms.map(r => 
                    `<option value="${r.id}" ${r.id === report.room_id ? 'selected' : ''}>${r.name}</option>`
                ).join('');
            
            document.getElementById('facility_id').innerHTML = 
                facilities.map(f => 
                    `<option value="${f.id}" ${f.id === report.facility_id ? 'selected' : ''}>${f.name}</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading report for edit:', error);
    }
}

async function submitEditReport(event) {
    event.preventDefault();
    
    const reportId = document.getElementById('report_id').value;
    const data = {
        description: document.getElementById('description').value,
        status: document.getElementById('status').value
    };
    
    try {
        const response = await api.updateReport(reportId, data);
        
        if (response.success) {
            alert('Laporan berhasil diperbarui!');
            window.location.href = '/dashboard';
        } else {
            alert('Gagal memperbarui laporan: ' + response.message);
        }
    } catch (error) {
        console.error('Error updating report:', error);
        alert('Terjadi kesalahan');
    }
}

async function deleteReport(reportId) {
    if (!confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
        return;
    }
    
    try {
        const response = await api.deleteReport(reportId);
        
        if (response.success) {
            alert('Laporan berhasil dihapus!');
            window.location.reload();
        } else {
            alert('Gagal menghapus laporan: ' + response.message);
        }
    } catch (error) {
        console.error('Error deleting report:', error);
        alert('Terjadi kesalahan');
    }
}

async function handleLogout() {
    try {
        await api.logout();
        localStorage.removeItem('user');
        window.location.href = '/login';
    } catch (error) {
        console.error('Error logging out:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Load data yang diperlukan saat halaman dimuat
    if (document.getElementById('building_id')) {
        loadBuildings();
    }
    
    if (document.getElementById('reports-list')) {
        loadReports();
    }
});
