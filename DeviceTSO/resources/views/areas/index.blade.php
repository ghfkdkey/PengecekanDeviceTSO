@extends('layouts.app')

@section('title', 'Manajemen Area')
@section('page-title', 'Manajemen Area')
@section('page-subtitle', 'Kelola data area untuk sistem pengecekan device')

@section('content')
<div class="space-y-6 font-poppins">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1">
            <div class="max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-areas" 
                        placeholder="Cari area..."
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
                id="add-area-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Area</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Area</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-areas">{{ $areas->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Regional</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $areas->sum(function($area) { return $area->regionals->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Area</h3>
        </div>
        
        <div id="areas-container" class="p-6">
            @if($areas->count() > 0)
                <div id="areas-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($areas as $area)
                    <div class="area-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" data-area-id="{{ $area->area_id }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-blue rounded-lg p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $area->area_name }}</h4>
                                    <p class="text-sm text-gray-600">ID: #{{ $area->area_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-area-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-area-id="{{ $area->area_id }}"
                                    data-area-name="{{ $area->area_name }}"
                                    title="Edit Area"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-area-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-area-id="{{ $area->area_id }}"
                                    data-area-name="{{ $area->area_name }}"
                                    title="Hapus Area"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Regional</span>
                                <span class="font-semibold text-gray-900">{{ $area->regionals->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('regionals.index') }}?area={{ $area->area_id }}" class="w-full bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                                Lihat Regional
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div id="empty-state" class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Area</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan area pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-area-btn').click()"
                    >
                        Tambah Area Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="area-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Area</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="area-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="area-id" name="area_id">
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div>
                <label for="area-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Area <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="area-name" 
                    name="area_name" 
                    placeholder="Contoh: Area Jawa Barat, Area Sumatera Utara"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="100"
                >
                <div id="area-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
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
                Apakah Anda yakin ingin menghapus area "<span id="delete-area-name" class="font-semibold"></span>"?
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
    const addAreaBtn = document.getElementById('add-area-btn');
    const areaModal = document.getElementById('area-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const areaForm = document.getElementById('area-form');
    const modalContent = document.getElementById('modal-content');
    const searchInput = document.getElementById('search-areas');

    // Modal functions
    function openModal(isEdit = false, areaData = null) {
        const modalTitle = document.getElementById('modal-title');
        const areaIdInput = document.getElementById('area-id');
        const areaNameInput = document.getElementById('area-name');
        const formMethodInput = document.getElementById('form-method');
        const submitText = document.getElementById('submit-text');

        if (isEdit && areaData) {
            modalTitle.textContent = 'Edit Area';
            areaIdInput.value = areaData.id;
            areaNameInput.value = areaData.name;
            formMethodInput.value = 'PUT';
            submitText.textContent = 'Update';
        } else {
            modalTitle.textContent = 'Tambah Area';
            areaForm.reset();
            formMethodInput.value = 'POST';
            submitText.textContent = 'Simpan';
        }

        areaModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        areaNameInput.focus();
    }

    function closeModalFunc() {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            areaModal.classList.add('hidden');
            clearErrors();
            areaForm.reset();
        }, 300);
    }

    function clearErrors() {
        document.getElementById('area-name-error').classList.add('hidden');
    }

    // Event listeners
    addAreaBtn.addEventListener('click', () => openModal());
    closeModalBtn.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);

    // Close modal when clicking outside
    areaModal.addEventListener('click', function(e) {
        if (e.target === areaModal) {
            closeModalFunc();
        }
    });

    // Edit and Delete area buttons (using event delegation)
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-area-btn');
        if (editBtn) {
            const areaData = {
                id: editBtn.dataset.areaId,
                name: editBtn.dataset.areaName,
            };
            openModal(true, areaData);
        }

        const deleteBtn = e.target.closest('.delete-area-btn');
        if (deleteBtn) {
            const areaId = deleteBtn.dataset.areaId;
            const areaName = deleteBtn.dataset.areaName;
            
            document.getElementById('delete-area-name').textContent = areaName;
            document.getElementById('confirm-delete-btn').dataset.areaId = areaId;
            deleteModal.classList.remove('hidden');
        }
    });

    // Cancel delete
    document.getElementById('cancel-delete-btn').addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });

    // Confirm delete
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        const areaId = this.dataset.areaId;
        const deleteText = document.getElementById('delete-text');
        const deleteSpinner = document.getElementById('delete-spinner');
        
        // Show loading state
        deleteText.classList.add('hidden');
        deleteSpinner.classList.remove('hidden');
        this.disabled = true;
        
        // Create delete request
        fetch(`/areas/${areaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menghapus area');
                // Reset loading state
                deleteText.classList.remove('hidden');
                deleteSpinner.classList.add('hidden');
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus area');
            deleteText.classList.remove('hidden');
            deleteSpinner.classList.add('hidden');
            this.disabled = false;
        });
    });

    // Close delete modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
        }
    });

    // Form submission
    areaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(this);
        const areaId = document.getElementById('area-id').value;
        const method = document.getElementById('form-method').value;
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingSpinner = document.getElementById('loading-spinner');

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

        let url = '/areas';
        if (method === 'PUT' && areaId) {
            url = `/areas/${areaId}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST', // Laravel uses POST for PUT/PATCH via _method field
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                if (data.errors && data.errors.area_name) {
                    const errorDiv = document.getElementById('area-name-error');
                    errorDiv.textContent = data.errors.area_name[0];
                    errorDiv.classList.remove('hidden');
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menyimpan area');
                }
                
                // Reset loading state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan area');
            
            // Reset loading state
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const areaCards = document.querySelectorAll('.area-card');
        let visibleCount = 0;

        areaCards.forEach(card => {
            const areaName = card.querySelector('h4').textContent.toLowerCase();
            const areaId = card.querySelector('.text-sm.text-gray-600').textContent.toLowerCase();
            const isVisible = areaName.includes(searchTerm) || areaId.includes(searchTerm);

            if (isVisible) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state for search results
        const areasGrid = document.getElementById('areas-grid');
        let searchEmptyState = document.getElementById('search-empty-state');
        
        if (searchEmptyState) {
            searchEmptyState.remove();
        }

        if (visibleCount === 0 && searchTerm !== '') {
            if (areasGrid) areasGrid.style.display = 'none';
            
            searchEmptyState = document.createElement('div');
            searchEmptyState.id = 'search-empty-state';
            searchEmptyState.className = 'text-center py-12';
            searchEmptyState.innerHTML = `
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600">Tidak ditemukan area yang sesuai dengan pencarian "${searchTerm}".</p>
            `;
            document.getElementById('areas-container').appendChild(searchEmptyState);
        } else {
            if (areasGrid) areasGrid.style.display = 'grid';
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!areaModal.classList.contains('hidden')) closeModalFunc();
            if (!deleteModal.classList.contains('hidden')) deleteModal.classList.add('hidden');
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            openModal();
        }
    });

    // Form validation on input
    document.getElementById('area-name').addEventListener('input', function() {
        const errorDiv = document.getElementById('area-name-error');
        if (this.value.trim() && !errorDiv.classList.contains('hidden')) {
            errorDiv.classList.add('hidden');
        }
    });

    // Animation for cards on load
    function animateCardsOnLoad() {
        const cards = document.querySelectorAll('.area-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 80);
        });
    }

    animateCardsOnLoad();

    console.log('Area management script loaded successfully');
});
</script>
@endpush