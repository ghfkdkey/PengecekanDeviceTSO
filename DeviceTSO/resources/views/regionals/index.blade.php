@extends('layouts.app')

@section('title', 'Manajemen Regional')
@section('page-title', 'Manajemen Regional')
@section('page-subtitle', 'Kelola data regional untuk sistem pengecekan device')
@section('content')
<div class="space-y-6 font-poppins">
    <!-- Success/Error Messages -->
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

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
            <!-- Search Bar -->
            <div class="max-w-md mb-4 sm:mb-0">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-regionals" 
                        placeholder="Cari regional..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red"
                    >
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Filter by Area -->
            <div class="max-w-md">
                <select 
                    id="filter-area"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red"
                >
                    <option value="">Semua Area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->area_id }}" {{ request('area') == $area->area_id ? 'selected' : '' }}>
                            {{ $area->area_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <button 
                id="add-regional-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Regional</span>
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
                    <p class="text-sm font-medium text-gray-600">Total Regional</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-regionals">{{ $regionals->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Area Terdaftar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $areas->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Building</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $regionals->sum(function($regional) { return $regional->buildings->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Regionals Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Regional</h3>
        </div>
        
        <div id="regionals-container" class="p-6">
            @if($regionals->count() > 0)
                <div id="regionals-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($regionals as $regional)
                    <div class="regional-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" 
                         data-regional-id="{{ $regional->regional_id }}" 
                         data-area-id="{{ $regional->area_id }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-blue rounded-lg p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $regional->regional_name }}</h4>
                                    <p class="text-sm text-gray-600">ID: #{{ $regional->regional_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-regional-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-regional-id="{{ $regional->regional_id }}"
                                    data-regional-name="{{ $regional->regional_name }}"
                                    data-area-id="{{ $regional->area_id }}"
                                    title="Edit Regional"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-regional-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-regional-id="{{ $regional->regional_id }}"
                                    data-regional-name="{{ $regional->regional_name }}"
                                    title="Hapus Regional"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Area</span>
                                <span class="font-semibold text-telkomsel-blue">
                                    {{ $regional->area ? $regional->area->area_name : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Building</span>
                                <span class="font-semibold text-gray-900">{{ $regional->buildings->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('buildings.index') }}?regional={{ $regional->regional_id }}" class="w-full bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                                Lihat Building
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Regional</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan regional pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-regional-btn').click()"
                    >
                        Tambah Regional Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Regional Modal -->
<div id="regional-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Regional</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="regional-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="regional-id" name="regional_id">
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div>
                <label for="area-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Area <span class="text-red-500">*</span>
                </label>
                <select 
                    id="area-select" 
                    name="area_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                >
                    <option value="">Pilih Area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->area_id }}">{{ $area->area_name }}</option>
                    @endforeach
                </select>
                <div id="area-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div>
                <label for="regional-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Regional <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="regional-name" 
                    name="regional_name" 
                    placeholder="Contoh: Regional Jakarta Pusat, Regional Bandung"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="100"
                >
                <div id="regional-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
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
                Apakah Anda yakin ingin menghapus regional "<span id="delete-regional-name" class="font-semibold"></span>"?
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
    const addRegionalBtn = document.getElementById('add-regional-btn');
    const regionalModal = document.getElementById('regional-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const regionalForm = document.getElementById('regional-form');
    const modalContent = document.getElementById('modal-content');
    const searchInput = document.getElementById('search-regionals');
    const filterArea = document.getElementById('filter-area');

    // Modal functions
    function openModal(isEdit = false, regionalData = null) {
        const modalTitle = document.getElementById('modal-title');
        const regionalId = document.getElementById('regional-id');
        const regionalName = document.getElementById('regional-name');
        const areaSelect = document.getElementById('area-select');
        const formMethod = document.getElementById('form-method');
        const submitText = document.getElementById('submit-text');

        if (isEdit && regionalData) {
            modalTitle.textContent = 'Edit Regional';
            regionalId.value = regionalData.id;
            regionalName.value = regionalData.name;
            areaSelect.value = regionalData.areaId;
            formMethod.value = 'PUT';
            submitText.textContent = 'Update';
        } else {
            modalTitle.textContent = 'Tambah Regional';
            regionalId.value = '';
            regionalName.value = '';
            areaSelect.value = '';
            formMethod.value = 'POST';
            submitText.textContent = 'Simpan';
        }

        regionalModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        areaSelect.focus();
    }

    function closeModalFunc() {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            regionalModal.classList.add('hidden');
            clearErrors();
            regionalForm.reset();
        }, 300);
    }

    function clearErrors() {
        document.getElementById('area-error').classList.add('hidden');
        document.getElementById('regional-name-error').classList.add('hidden');
    }

    // Event listeners
    addRegionalBtn.addEventListener('click', () => openModal());
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);

    // Close modal when clicking outside
    regionalModal.addEventListener('click', function(e) {
        if (e.target === regionalModal) {
            closeModalFunc();
        }
    });

    // Edit regional buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-regional-btn')) {
            const btn = e.target.closest('.edit-regional-btn');
            const regionalData = {
                id: btn.dataset.regionalId,
                name: btn.dataset.regionalName,
                areaId: btn.dataset.areaId
            };
            openModal(true, regionalData);
        }
    });

    // Delete regional buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-regional-btn')) {
            const btn = e.target.closest('.delete-regional-btn');
            const regionalId = btn.dataset.regionalId;
            const regionalName = btn.dataset.regionalName;
            
            document.getElementById('delete-regional-name').textContent = regionalName;
            document.getElementById('confirm-delete-btn').dataset.regionalId = regionalId;
            deleteModal.classList.remove('hidden');
        }
    });

    // Cancel delete
    document.getElementById('cancel-delete-btn').addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });

    // Confirm delete
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        const regionalId = this.dataset.regionalId;
        const deleteText = document.getElementById('delete-text');
        const deleteSpinner = document.getElementById('delete-spinner');
        
        // Show loading state
        deleteText.classList.add('hidden');
        deleteSpinner.classList.remove('hidden');
        this.disabled = true;
        
        // Create delete request
        fetch(`/regionals/${regionalId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menghapus regional');
                // Reset loading state
                deleteText.classList.remove('hidden');
                deleteSpinner.classList.add('hidden');
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus regional');
            // Reset loading state
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

    // Ganti event listener form submission Anda dengan yang ini
    regionalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(this);
        const regionalId = document.getElementById('regional-id').value;
        const method = document.getElementById('form-method').value;
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingSpinner = document.getElementById('loading-spinner');

        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

        let url = '{{ route("regionals.store") }}';
        if (method === 'PUT' && regionalId) {
            url = `/regionals/${regionalId}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(async response => {
            // Cek jika respons tidak 'ok' (bukan status 2xx)
            if (!response.ok) {
                // Coba untuk mendapatkan detail error dari body JSON
                const errorData = await response.json().catch(() => null);
                // Lemparkan error dengan data tersebut agar bisa ditangkap di .catch()
                throw { status: response.status, data: errorData };
            }
            return response.json();
        })
        .then(data => {
            // Jika request berhasil (status 2xx) dan ada 'success: true'
            if (data.success) {
                window.location.reload();
            } else {
                // Ini untuk kasus aneh dimana server merespon 200 OK tapi ada pesan error
                alert(data.message || 'Terjadi kesalahan yang tidak diketahui.');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error); // Tampilkan detail error di console
            
            // Cek jika error berasal dari validasi (status 422)
            if (error.status === 422 && error.data && error.data.errors) {
                const errors = error.data.errors;
                if (errors.area_id) {
                    const errorDiv = document.getElementById('area-error');
                    errorDiv.textContent = errors.area_id[0];
                    errorDiv.classList.remove('hidden');
                }
                if (errors.regional_name) {
                    const errorDiv = document.getElementById('regional-name-error');
                    errorDiv.textContent = errors.regional_name[0];
                    errorDiv.classList.remove('hidden');
                }
            } else {
                // Untuk semua error lain (misal 500 Internal Server Error, 419, dll)
                // Tampilkan pesan dari server jika ada, jika tidak, tampilkan pesan generik
                const errorMessage = error.data ? error.data.message : 'Tidak dapat terhubung ke server. Periksa koneksi Anda atau hubungi administrator.';
                alert('Error: ' + errorMessage);
            }
        })
        .finally(() => {
            // Selalu jalankan ini setelah fetch selesai (baik sukses maupun gagal)
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        filterRegionals();
    });

    // Filter by area
    filterArea.addEventListener('change', function() {
        filterRegionals();
    });

    function filterRegionals() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedArea = filterArea.value;
        const regionalCards = document.querySelectorAll('.regional-card');
        let visibleCount = 0;

        regionalCards.forEach(card => {
            const regionalName = card.querySelector('h4').textContent.toLowerCase();
            const regionalId = card.dataset.regionalId.toLowerCase();
            const areaName = card.querySelector('.text-telkomsel-blue').textContent.toLowerCase();
            const cardAreaId = card.dataset.areaId;

            const matchesSearch = regionalName.includes(searchTerm) || 
                                regionalId.includes(searchTerm) || 
                                areaName.includes(searchTerm);
            
            const matchesArea = !selectedArea || cardAreaId === selectedArea;

            const isVisible = matchesSearch && matchesArea;

            if (isVisible) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide empty state
        const regionalsGrid = document.getElementById('regionals-grid');
        const emptyState = document.getElementById('empty-state');
        
        if (visibleCount === 0 && (searchTerm !== '' || selectedArea !== '')) {
            if (regionalsGrid) regionalsGrid.style.display = 'none';
            showSearchEmptyState(searchTerm, selectedArea);
        } else {
            if (regionalsGrid) regionalsGrid.style.display = 'grid';
            hideSearchEmptyState();
        }

        // Update statistics
        updateStatistics();
    }

    function showSearchEmptyState(searchTerm, selectedArea) {
        hideSearchEmptyState(); // Remove existing search empty state
        
        let message = 'Tidak ditemukan regional';
        if (searchTerm && selectedArea) {
            const areaName = filterArea.options[filterArea.selectedIndex].text;
            message += ` yang sesuai dengan pencarian "${searchTerm}" di area "${areaName}"`;
        } else if (searchTerm) {
            message += ` yang sesuai dengan pencarian "${searchTerm}"`;
        } else if (selectedArea) {
            const areaName = filterArea.options[filterArea.selectedIndex].text;
            message += ` di area "${areaName}"`;
        }

        const searchEmptyState = document.createElement('div');
        searchEmptyState.id = 'search-empty-state';
        searchEmptyState.className = 'text-center py-12';
        searchEmptyState.innerHTML = `
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
            <p class="text-gray-600">${message}.</p>
        `;
        document.getElementById('regionals-container').appendChild(searchEmptyState);
    }

    function hideSearchEmptyState() {
        const searchEmptyState = document.getElementById('search-empty-state');
        if (searchEmptyState) {
            searchEmptyState.remove();
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key closes modals
        if (e.key === 'Escape') {
            if (!regionalModal.classList.contains('hidden')) {
                closeModalFunc();
            }
            if (!deleteModal.classList.contains('hidden')) {
                deleteModal.classList.add('hidden');
            }
        }
        
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        // Ctrl/Cmd + N for new regional
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            openModal();
        }
    });

    // Form validation on input
    document.getElementById('area-select').addEventListener('change', function() {
        const errorDiv = document.getElementById('area-error');
        if (this.value && !errorDiv.classList.contains('hidden')) {
            errorDiv.classList.add('hidden');
        }
    });

    document.getElementById('regional-name').addEventListener('input', function() {
        const errorDiv = document.getElementById('regional-name-error');
        if (this.value.trim() && !errorDiv.classList.contains('hidden')) {
            errorDiv.classList.add('hidden');
        }
    });

    // Auto-save search and filter state
    function saveFilterState() {
        if (searchInput.value) {
            sessionStorage.setItem('regionalSearchTerm', searchInput.value);
        } else {
            sessionStorage.removeItem('regionalSearchTerm');
        }
        
        if (filterArea.value) {
            sessionStorage.setItem('regionalAreaFilter', filterArea.value);
        } else {
            sessionStorage.removeItem('regionalAreaFilter');
        }
    }

    function restoreFilterState() {
        const savedSearch = sessionStorage.getItem('regionalSearchTerm');
        const savedArea = sessionStorage.getItem('regionalAreaFilter');
        
        if (savedSearch) {
            searchInput.value = savedSearch;
        }
        
        if (savedArea) {
            filterArea.value = savedArea;
        }
        
        if (savedSearch || savedArea) {
            filterRegionals();
        }
    }

    // Restore filter state on page load
    restoreFilterState();

    // Save filter state on input
    searchInput.addEventListener('input', saveFilterState);
    filterArea.addEventListener('change', saveFilterState);

    // Animation for cards on load
    function animateCardsOnLoad() {
        const cards = document.querySelectorAll('.regional-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Update statistics dynamically
    function updateStatistics() {
        const visibleCards = document.querySelectorAll('.regional-card[style*="display: block"], .regional-card:not([style*="display: none"])').length;
        const totalRegionalsElement = document.getElementById('total-regionals');
        if (totalRegionalsElement) {
            totalRegionalsElement.textContent = visibleCards || document.querySelectorAll('.regional-card').length;
        }
    }

    // Animate cards if they exist
    if (document.querySelectorAll('.regional-card').length > 0) {
        animateCardsOnLoad();
    }

    // Initialize tooltips
    function initTooltips() {
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'fixed bg-gray-800 text-white text-xs px-2 py-1 rounded z-50 pointer-events-none';
                tooltip.textContent = this.getAttribute('title');
                tooltip.style.left = e.pageX + 10 + 'px';
                tooltip.style.top = e.pageY - 30 + 'px';
                document.body.appendChild(tooltip);
                this.tooltipElement = tooltip;
                this.removeAttribute('title');
            });
            
            element.addEventListener('mouseleave', function() {
                if (this.tooltipElement) {
                    this.tooltipElement.remove();
                    this.tooltipElement = null;
                }
            });
        });
    }

    // Initialize tooltips
    initTooltips();

    console.log('Regional management script loaded successfully');
});
</script>
@endpush
