@extends('layouts.app')

@section('title', 'Manajemen Ruangan')
@section('page-title', 'Manajemen Ruangan')
@section('page-subtitle', 'Kelola data ruangan untuk sistem pengecekan device')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    .font-telkomsel {
        font-family: 'Telkomsel Batik Sans', sans-serif;
    }
    
    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }
    
    .text-telkomsel-red { color: #FF0025; }
    .bg-telkomsel-red { background-color: #FF0025; }
    .border-telkomsel-red { border-color: #FF0025; }
    .text-telkomsel-dark-red { color: #B90024; }
    .bg-telkomsel-dark-red { background-color: #B90024; }
    .text-telkomsel-yellow { color: #FDA22B; }
    .bg-telkomsel-yellow { background-color: #FDA22B; }
    .text-telkomsel-blue { color: #001A41; }
    .bg-telkomsel-blue { background-color: #001A41; }
    .bg-telkomsel-gray { background-color: #DBDBDB; }
</style>
@endpush

@section('content')
<div class="space-y-6 font-poppins">
    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <div class="flex-1 space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
            <!-- Floor Filter -->
            <div class="lg:max-w-xs">
                <select 
                    id="floor-filter" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red bg-white"
                >
                    <option value="">Semua Lantai</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor->floor_id }}" {{ request('floor') == $floor->floor_id ? 'selected' : '' }}>
                            {{ $floor->floor_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Search Bar -->
            <div class="lg:max-w-md flex-1">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-rooms" 
                        placeholder="Cari ruangan..."
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
        
        <div class="flex items-center space-x-3">        
            <button 
                id="add-room-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Ruangan</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ruangan</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-rooms">{{ $rooms->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Device</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $rooms->sum(function($room) { return $room->devices->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lantai Tersedia</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $floors->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Ruangan</h3>
                    <p class="text-sm text-gray-600 mt-1">Total <span id="filtered-count">{{ $rooms->count() }}</span> ruangan ditemukan</p>
                </div>
            </div>
        </div>
        
        <div id="rooms-container" class="p-6">
            @if($rooms->count() > 0)
                <div id="rooms-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rooms as $room)
                    <div class="room-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" 
                         data-room-id="{{ $room->room_id }}" 
                         data-floor-id="{{ $room->floor_id }}"
                         data-floor-name="{{ $room->floor->floor_name ?? '' }}">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-blue rounded-lg p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $room->room_name }}</h4>
                                    <p class="text-sm text-gray-600">ID: #{{ $room->room_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-room-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-room-id="{{ $room->room_id }}"
                                    data-room-name="{{ $room->room_name }}"
                                    data-floor-id="{{ $room->floor_id }}"
                                    title="Edit Ruangan"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-room-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-room-id="{{ $room->room_id }}"
                                    data-room-name="{{ $room->room_name }}"
                                    title="Hapus Ruangan"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Lantai</span>
                                <span class="font-semibold text-telkomsel-blue">{{ $room->floor->floor_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Device</span>
                                <span class="font-semibold text-gray-900">{{ $room->devices->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('devices.index') }}?room={{ $room->room_id }}" 
                               class="flex-1 bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                                Lihat Device
                            </a>
                            <button 
                                class="bg-telkomsel-yellow/20 text-telkomsel-yellow px-4 py-2 rounded-lg hover:bg-telkomsel-yellow/30 transition-colors text-sm font-medium"
                                title="Quick Stats"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div id="empty-state" class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Ruangan</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan ruangan pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-room-btn').click()"
                    >
                        Tambah Ruangan Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Room Modal -->
<div id="room-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Ruangan</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="room-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="room-id" name="room_id">
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div>
                <label for="floor-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Lantai <span class="text-red-500">*</span>
                </label>
                <select 
                    id="floor-select" 
                    name="floor_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                >
                    <option value="">Pilih Lantai</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor->floor_id }}">{{ $floor->floor_name }}</option>
                    @endforeach
                </select>
                <div id="floor-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div>
                <label for="room-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Ruangan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="room-name" 
                    name="room_name" 
                    placeholder="Contoh: Ruang Meeting A, Lab Komputer 1, Aula"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="100"
                >
                <div id="room-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                <div class="text-gray-500 text-xs mt-1">
                    <span id="char-count">0</span>/100 karakter
                </div>
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
                    class="flex-1 bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-4 py-3 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all font-medium disabled:opacity-50"
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
                Apakah Anda yakin ingin menghapus ruangan "<span id="delete-room-name" class="font-semibold"></span>"?
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
// FIXED ROOM AJAX IMPLEMENTATION
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const addRoomBtn = document.getElementById('add-room-btn');
    const roomModal = document.getElementById('room-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const roomForm = document.getElementById('room-form');
    const modalContent = document.getElementById('modal-content');
    const searchInput = document.getElementById('search-rooms');
    const floorFilter = document.getElementById('floor-filter');

    // Modal functions
    function openModal(isEdit = false, roomData = null) {
        const modalTitle = document.getElementById('modal-title');
        const roomId = document.getElementById('room-id');
        const roomName = document.getElementById('room-name');
        const floorSelect = document.getElementById('floor-select');
        const formMethod = document.getElementById('form-method');
        const submitText = document.getElementById('submit-text');

        if (isEdit && roomData) {
            modalTitle.textContent = 'Edit Ruangan';
            roomId.value = roomData.roomId;
            roomName.value = roomData.roomName;
            floorSelect.value = roomData.floorId;
            formMethod.value = 'PUT';
            submitText.textContent = 'Update';
        } else {
            modalTitle.textContent = 'Tambah Ruangan';
            roomId.value = '';
            roomName.value = '';
            floorSelect.value = '';
            formMethod.value = 'POST';
            submitText.textContent = 'Simpan';
        }

        // Clear errors
        document.querySelectorAll('[id$="-error"]').forEach(error => {
            error.classList.add('hidden');
        });

        roomModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        // Focus first input
        setTimeout(() => floorSelect.focus(), 100);
    }

    function closeModalFunc() {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            roomModal.classList.add('hidden');
            roomForm.reset();
            document.querySelectorAll('[id$="-error"]').forEach(error => {
                error.classList.add('hidden');
            });
        }, 300);
    }

    // Event listeners
    addRoomBtn.addEventListener('click', () => openModal());
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);

    // Close modal when clicking outside
    roomModal.addEventListener('click', function(e) {
        if (e.target === roomModal) {
            closeModalFunc();
        }
    });

    // Edit room buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-room-btn')) {
            const btn = e.target.closest('.edit-room-btn');
            const roomData = {
                roomId: btn.dataset.roomId,
                roomName: btn.dataset.roomName,
                floorId: btn.dataset.floorId
            };
            openModal(true, roomData);
        }
    });

    // Delete room buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-room-btn')) {
            const btn = e.target.closest('.delete-room-btn');
            const roomId = btn.dataset.roomId;
            const roomName = btn.dataset.roomName;
            
            document.getElementById('delete-room-name').textContent = roomName;
            document.getElementById('confirm-delete-btn').dataset.roomId = roomId;
            deleteModal.classList.remove('hidden');
        }
    });

    // Cancel delete
    document.getElementById('cancel-delete-btn').addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });

    // Confirm delete
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        const roomId = this.dataset.roomId;
        const deleteText = document.getElementById('delete-text');
        const deleteSpinner = document.getElementById('delete-spinner');
        
        // Show loading state
        deleteText.classList.add('hidden');
        deleteSpinner.classList.remove('hidden');
        this.disabled = true;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        // Submit delete request
        fetch(`/rooms/${roomId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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
                console.log('Success:', data.message);
                
                // Remove room card from UI
                removeRoomCard(roomId);
                
                // Close delete modal
                deleteModal.classList.add('hidden');
                
                // Update statistics
                updateStatistics();
                
                // Show success notification (optional)
                showNotification('Ruangan berhasil dihapus!', 'success');
            } else {
                throw data;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset loading state
            deleteText.classList.remove('hidden');
            deleteSpinner.classList.add('hidden');
            this.disabled = false;
            
            // Show error notification
            showNotification(error.message || 'Gagal menghapus ruangan!', 'error');
        });
    });

    // Close delete modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
        }
    });

    // Form submission - FIXED VERSION
    roomForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingSpinner = document.getElementById('loading-spinner');
        
        // Clear previous errors
        document.querySelectorAll('[id$="-error"]').forEach(error => {
            error.classList.add('hidden');
        });
        
        // Show loading state
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        const roomId = document.getElementById('room-id').value;
        const method = document.getElementById('form-method').value;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        // Determine URL and method
        let url = '/rooms';
        if (method === 'PUT' && roomId) {
            url = `/rooms/${roomId}`;
        }
        
        // Prepare request body
        const requestBody = {
            room_name: formData.get('room_name'),
            floor_id: formData.get('floor_id'),
            _token: csrfToken
        };
        
        if (method === 'PUT') {
            requestBody._method = 'PUT';
        }
        
        // Submit form
        fetch(url, {
            method: 'POST', // Always POST for Laravel with _method override
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestBody)
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
                console.log('Success:', data.message);
                
                if (method === 'POST') {
                    // ADD NEW ROOM CARD
                    addNewRoomCard(data.room);
                } else {
                    // UPDATE EXISTING ROOM CARD
                    updateRoomCard(data.room);
                }
                
                // Close modal
                closeModalFunc();
                
                // Update statistics
                updateStatistics();
                
                // Show success notification (optional)
                showNotification(data.message || 'Ruangan berhasil disimpan!', 'success');
                
                // Re-apply current filters
                filterRooms();
                
            } else {
                throw data;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show validation errors
            if (error.errors) {
                for (const [field, messages] of Object.entries(error.errors)) {
                    const errorElement = document.getElementById(`${field.replace('_', '-')}-error`);
                    if (errorElement && messages.length > 0) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }
                }
            } else {
                // Show general error
                showNotification(error.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        })
        .finally(() => {
            // Reset loading state
            submitText.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            submitBtn.disabled = false;
        });
    });

    // Function to add new room card to UI
    function addNewRoomCard(room) {
        const roomsGrid = document.getElementById('rooms-grid');
        const emptyState = document.getElementById('empty-state');
        
        // Remove empty state if exists
        if (emptyState) {
            emptyState.remove();
        }
        
        // Create room card HTML
        const roomCardHtml = createRoomCardHtml(room);
        
        // Insert new card at the beginning
        if (roomsGrid) {
            roomsGrid.insertAdjacentHTML('afterbegin', roomCardHtml);
            
            // Add animation to new card
            const newCard = roomsGrid.firstElementChild;
            newCard.style.opacity = '0';
            newCard.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                newCard.style.transition = 'all 0.3s ease';
                newCard.style.opacity = '1';
                newCard.style.transform = 'translateY(0)';
            }, 10);
        }
    }

    // Function to update existing room card
    function updateRoomCard(room) {
        const existingCard = document.querySelector(`[data-room-id="${room.room_id}"]`);
        if (!existingCard) return;
        
        // Update card content
        const roomNameElement = existingCard.querySelector('h4');
        const floorNameElement = existingCard.querySelector('.font-semibold.text-telkomsel-blue');
        const editBtn = existingCard.querySelector('.edit-room-btn');
        
        if (roomNameElement) {
            roomNameElement.textContent = room.room_name;
        }
        
        if (floorNameElement && room.floor) {
            floorNameElement.textContent = room.floor.floor_name;
        }
        
        // Update data attributes
        existingCard.dataset.roomName = room.room_name;
        existingCard.dataset.floorId = room.floor_id;
        existingCard.dataset.floorName = room.floor ? room.floor.floor_name : '';
        
        if (editBtn) {
            editBtn.dataset.roomName = room.room_name;
            editBtn.dataset.floorId = room.floor_id;
        }
        
        // Flash effect
        existingCard.style.backgroundColor = '#f0f9ff';
        setTimeout(() => {
            existingCard.style.transition = 'background-color 0.3s ease';
            existingCard.style.backgroundColor = '';
        }, 100);
    }

    // Function to remove room card from UI
    function removeRoomCard(roomId) {
        const roomCard = document.querySelector(`[data-room-id="${roomId}"]`);
        if (!roomCard) return;
        
        // Animate out
        roomCard.style.transition = 'all 0.3s ease';
        roomCard.style.opacity = '0';
        roomCard.style.transform = 'translateX(100px)';
        
        setTimeout(() => {
            roomCard.remove();
            
            // Show empty state if no rooms left
            const remainingCards = document.querySelectorAll('.room-card');
            if (remainingCards.length === 0) {
                showEmptyState();
            }
        }, 300);
    }

    // Function to create room card HTML
    function createRoomCardHtml(room) {
        const deviceCount = room.devices ? room.devices.length : 0;
        const floorName = room.floor ? room.floor.floor_name : 'N/A';
        
        return `
            <div class="room-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" 
                 data-room-id="${room.room_id}" 
                 data-floor-id="${room.floor_id}"
                 data-room-name="${room.room_name}"
                 data-floor-name="${floorName}">
                
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-telkomsel-blue rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-lg">${room.room_name}</h4>
                            <p class="text-sm text-gray-600">ID: #${room.room_id}</p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button 
                            class="edit-room-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                            data-room-id="${room.room_id}"
                            data-room-name="${room.room_name}"
                            data-floor-id="${room.floor_id}"
                            title="Edit Ruangan"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button 
                            class="delete-room-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                            data-room-id="${room.room_id}"
                            data-room-name="${room.room_name}"
                            title="Hapus Ruangan"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Lantai</span>
                        <span class="font-semibold text-telkomsel-blue">${floorName}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Device</span>
                        <span class="font-semibold text-gray-900">${deviceCount}</span>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <a href="/devices?room=${room.room_id}" 
                       class="flex-1 bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                        Lihat Device
                    </a>
                    <button 
                        class="bg-telkomsel-yellow/20 text-telkomsel-yellow px-4 py-2 rounded-lg hover:bg-telkomsel-yellow/30 transition-colors text-sm font-medium"
                        title="Quick Stats"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    }

    // Function to show empty state
    function showEmptyState() {
        const roomsContainer = document.getElementById('rooms-container');
        const emptyStateHtml = `
            <div id="empty-state" class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Ruangan</h3>
                <p class="text-gray-600 mb-4">Mulai dengan menambahkan ruangan pertama untuk sistem Anda.</p>
                <button 
                    class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                    onclick="document.getElementById('add-room-btn').click()"
                >
                    Tambah Ruangan Pertama
                </button>
            </div>
        `;
        
        // Insert empty state after rooms grid
        const roomsGrid = document.getElementById('rooms-grid');
        if (roomsGrid) {
            roomsGrid.insertAdjacentHTML('afterend', emptyStateHtml);
        }
    }

    // Function to update statistics
    function updateStatistics() {
        const totalRoomsElement = document.getElementById('total-rooms');
        const totalRooms = document.querySelectorAll('.room-card').length;
        
        if (totalRoomsElement) {
            totalRoomsElement.textContent = totalRooms;
        }
        
        // Update filtered count
        const filteredCountElement = document.getElementById('filtered-count');
        if (filteredCountElement) {
            const visibleRooms = document.querySelectorAll('.room-card:not([style*="display: none"])').length;
            filteredCountElement.textContent = visibleRooms;
        }
    }

    // Search and filter functionality
    function filterRooms() {
        const floorValue = floorFilter ? floorFilter.value : '';
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const roomCards = document.querySelectorAll('.room-card');
        
        let visibleCount = 0;
        
        roomCards.forEach(card => {
            const floorId = card.dataset.floorId;
            const floorName = (card.dataset.floorName || '').toLowerCase();
            const roomName = card.querySelector('h4').textContent.toLowerCase();
            const roomId = card.dataset.roomId;
            
            let shouldShow = true;
            
            // Filter by floor
            if (floorValue && floorId !== floorValue) {
                shouldShow = false;
            }
            
            // Filter by search
            if (searchValue && !roomName.includes(searchValue) && 
                !floorName.includes(searchValue) && !roomId.includes(searchValue)) {
                shouldShow = false;
            }
            
            if (shouldShow) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update filtered count
        const filteredCountElement = document.getElementById('filtered-count');
        if (filteredCountElement) {
            filteredCountElement.textContent = visibleCount;
        }
    }

    // Bind filter events
    if (floorFilter) {
        floorFilter.addEventListener('change', filterRooms);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Debounce search
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(filterRooms, 300);
        });
    }

    // Handle escape key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!roomModal.classList.contains('hidden')) {
                closeModalFunc();
            }
            if (!deleteModal.classList.contains('hidden')) {
                deleteModal.classList.add('hidden');
            }
        }
    });
});
</script>