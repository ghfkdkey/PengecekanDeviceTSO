@extends('layouts.app')

@section('title', 'Manajemen Lantai')
@section('page-title', 'Manajemen Lantai')
@section('page-subtitle', 'Kelola data lantai untuk sistem pengecekan device')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1">
            <!-- Search Bar -->
            <div class="max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-floors" 
                        placeholder="Cari lantai..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red"
                    >
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <button 
                id="add-floor-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Lantai</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Lantai</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-floors">{{ $floors->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ruangan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $floors->sum(function($floor) { return $floor->rooms->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Device</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $floors->sum(function($floor) { return $floor->devices->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Floors Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Lantai</h3>
        </div>
        
        <div id="floors-container" class="p-6">
            @if($floors->count() > 0)
                <div id="floors-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($floors as $floor)
                    <div class="floor-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" data-floor-id="{{ $floor->floor_id }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-red rounded-lg p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $floor->floor_name }}</h4>
                                    <p class="text-sm text-gray-600">ID: #{{ $floor->floor_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-floor-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-floor-id="{{ $floor->floor_id }}"
                                    data-floor-name="{{ $floor->floor_name }}"
                                    title="Edit Lantai"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-floor-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-floor-id="{{ $floor->floor_id }}"
                                    data-floor-name="{{ $floor->floor_name }}"
                                    title="Hapus Lantai"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Ruangan</span>
                                <span class="font-semibold text-gray-900">{{ $floor->rooms->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Device</span>
                                <span class="font-semibold text-gray-900">{{ $floor->devices->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('rooms.index') }}?floor={{ $floor->floor_id }}" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                                Lihat Ruangan
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div id="empty-state" class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Lantai</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan lantai pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-floor-btn').click()"
                    >
                        Tambah Lantai Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Floor Modal -->
<div id="floor-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Lantai</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="floor-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="floor-id" name="floor_id">
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div>
                <label for="floor-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lantai <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="floor-name" 
                    name="floor_name" 
                    placeholder="Contoh: Lantai 1, Ground Floor, Basement"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                >
                <div id="floor-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div class="flex space-x-4 pt-4">
                <button 
                    type="button" 
                    id="cancel-btn"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="flex-1 bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-4 py-3 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all font-medium"
                >
                    <span id="submit-text">Simpan</span>
                    <svg id="loading-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            
            <p class="text-gray-700 mb-6">
                Apakah Anda yakin ingin menghapus lantai "<span id="delete-floor-name" class="font-semibold"></span>"?
            </p>
            
            <div class="flex space-x-4">
                <button 
                    type="button" 
                    id="cancel-delete-btn"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    id="confirm-delete-btn"
                    class="flex-1 bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium"
                >
                    <span id="delete-text">Hapus</span>
                    <svg id="delete-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const addFloorBtn = document.getElementById('add-floor-btn');
    const floorModal = document.getElementById('floor-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const floorForm = document.getElementById('floor-form');
    const modalContent = document.getElementById('modal-content');
    const searchInput = document.getElementById('search-floors');

    // Modal functions
    function openModal(isEdit = false, floorData = null) {
        const modalTitle = document.getElementById('modal-title');
        const floorId = document.getElementById('floor-id');
        const floorName = document.getElementById('floor-name');
        const formMethod = document.getElementById('form-method');
        const submitText = document.getElementById('submit-text');

        if (isEdit && floorData) {
            modalTitle.textContent = 'Edit Lantai';
            floorId.value = floorData.id;
            floorName.value = floorData.name;
            formMethod.value = 'PUT';
            submitText.textContent = 'Update';
        } else {
            modalTitle.textContent = 'Tambah Lantai';
            floorId.value = '';
            floorName.value = '';
            formMethod.value = 'POST';
            submitText.textContent = 'Simpan';
        }

        floorModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        floorName.focus();
    }

    function closeModalFunc() {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            floorModal.classList.add('hidden');
            document.getElementById('floor-name-error').classList.add('hidden');
            floorForm.reset();
        }, 300);
    }

    // Event listeners
    addFloorBtn.addEventListener('click', () => openModal());
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);

    // Close modal when clicking outside
    floorModal.addEventListener('click', function(e) {
        if (e.target === floorModal) {
            closeModalFunc();
        }
    });

    // Edit floor buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-floor-btn')) {
            const btn = e.target.closest('.edit-floor-btn');
            const floorData = {
                id: btn.dataset.floorId,
                name: btn.dataset.floorName
            };
            openModal(true, floorData);
        }
    });

    // Delete floor buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-floor-btn')) {
            const btn = e.target.closest('.delete-floor-btn');
            const floorId = btn.dataset.floorId;
            const floorName = btn.dataset.floorName;
            
            document.getElementById('delete-floor-name').textContent = floorName;
            document.getElementById('confirm-delete-btn').dataset.floorId = floorId;
            deleteModal.classList.remove('hidden');
        }
    });

    // Cancel delete
    document.getElementById('cancel-delete-btn').addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });

    // Confirm delete
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        const floorId = this.dataset.floorId;
        const deleteText = document.getElementById('delete-text');
        const deleteSpinner = document.getElementById('delete-spinner');
        
        // Show loading state
        deleteText.classList.add('hidden');
        deleteSpinner.classList.remove('hidden');
        this.disabled = true;
        
        // Create form for delete request
        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `/floors/${floorId}`;
        deleteForm.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        deleteForm.appendChild(csrfToken);
        deleteForm.appendChild(methodInput);
        document.body.appendChild(deleteForm);
        
        // Submit the form
        deleteForm.submit();
    });

    // Close delete modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
        }
    });

    // Form submission
    floorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingSpinner = document.getElementById('loading-spinner');
        const floorNameError = document.getElementById('floor-name-error');
        
        // Hide previous errors
        floorNameError.classList.add('hidden');
        
        // Show loading state
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        const floorId = document.getElementById('floor-id').value;
        const method = document.getElementById('form-method').value;
        
        // Determine URL and method
        let url = '/floors';
        let fetchMethod = 'POST';
        
        if (method === 'PUT' && floorId) {
            url = `/floors/${floorId}`;
            fetchMethod = 'POST'; // Laravel uses POST with _method override for PUT
        }
        
        // Add CSRF token if not present
        if (!formData.has('_token')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            if (csrfToken) {
                formData.append('_token', csrfToken);
            }
        }
        
        // Submit form
        fetch(url, {
            method: fetchMethod,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message (you can implement toast/notification here)
                console.log('Success:', data.message);
                
                // Reload page to show updated data
                window.location.reload();
            } else {
                throw data;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show validation errors
            if (error.errors && error.errors.floor_name) {
                floorNameError.textContent = error.errors.floor_name[0];
                floorNameError.classList.remove('hidden');
            } else {
                // Show general error
                floorNameError.textContent = error.message || 'Terjadi kesalahan saat menyimpan data.';
                floorNameError.classList.remove('hidden');
            }
            
            // Reset loading state
            submitText.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            submitBtn.disabled = false;
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const floorCards = document.querySelectorAll('.floor-card');
        const floorsGrid = document.getElementById('floors-grid');
        const emptyState = document.getElementById('empty-state');
        let visibleCount = 0;
        
        floorCards.forEach(card => {
            const floorName = card.querySelector('h4').textContent.toLowerCase();
            const floorId = card.querySelector('p').textContent.toLowerCase();
            
            if (floorName.includes(searchTerm) || floorId.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide empty state based on search results
        if (visibleCount === 0 && floorCards.length > 0) {
            if (floorsGrid) floorsGrid.style.display = 'none';
            
            // Create search empty state if it doesn't exist
            let searchEmptyState = document.getElementById('search-empty-state');
            if (!searchEmptyState) {
                searchEmptyState = document.createElement('div');
                searchEmptyState.id = 'search-empty-state';
                searchEmptyState.className = 'text-center py-12';
                searchEmptyState.innerHTML = `
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-600">Tidak ditemukan lantai yang sesuai dengan pencarian Anda.</p>
                `;
                document.getElementById('floors-container').appendChild(searchEmptyState);
            }
            searchEmptyState.style.display = 'block';
        } else {
            if (floorsGrid) floorsGrid.style.display = 'grid';
            const searchEmptyState = document.getElementById('search-empty-state');
            if (searchEmptyState) {
                searchEmptyState.style.display = 'none';
            }
        }
    });

    // Handle escape key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!floorModal.classList.contains('hidden')) {
                closeModalFunc();
            }
            if (!deleteModal.classList.contains('hidden')) {
                deleteModal.classList.add('hidden');
            }
        }
    });

    // Auto-focus search on Ctrl+K or Cmd+K
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });

    // Real-time validation for floor name
    document.getElementById('floor-name').addEventListener('input', function() {
        const floorNameError = document.getElementById('floor-name-error');
        const value = this.value.trim();
        
        if (value.length > 0 && value.length < 2) {
            floorNameError.textContent = 'Nama lantai minimal 2 karakter.';
            floorNameError.classList.remove('hidden');
        } else if (value.length > 50) {
            floorNameError.textContent = 'Nama lantai maksimal 50 karakter.';
            floorNameError.classList.remove('hidden');
        } else {
            floorNameError.classList.add('hidden');
        }
    });

    // Add smooth animations for cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.floor-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        observer.observe(card);
    });
});
</script>