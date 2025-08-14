@extends('layouts.app')

@section('title', 'Manajemen Device')
@section('page-title', 'Manajemen Device')
@section('page-subtitle', 'Kelola device untuk sistem pengecekan')

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
    
    .device-status-online { 
        background-color: #10B981; 
        animation: pulse-green 2s infinite;
    }
    .device-status-offline { 
        background-color: #EF4444; 
    }
    .device-status-unknown { 
        background-color: #F59E0B; 
    }
    
    @keyframes pulse-green {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 font-poppins">
    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <div class="flex-1 space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
            <!-- Room Filter -->
            <div class="lg:max-w-xs">
                <select 
                    id="room-filter" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red bg-white"
                >
                    <option value="">Semua Ruangan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->room_id }}" {{ request('room') == $room->room_id ? 'selected' : '' }}>
                            {{ $room->room_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Device Type Filter -->
            <div class="lg:max-w-xs">
                <select 
                    id="type-filter" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red bg-white"
                >
                    <option value="">Semua Tipe</option>
                    @foreach($deviceTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Search Bar -->
            <div class="lg:max-w-md flex-1">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-devices" 
                        placeholder="Cari device..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red"
                        value="{{ request('search') }}"
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
                id="refresh-btn"
                class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center space-x-2"
                title="Refresh Data"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
            
            <button 
                id="add-device-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Device</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Device</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-devices">{{ $devices->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Device Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $devices->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ruangan Terpakai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $devices->groupBy('room_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-telkomsel-red/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tipe Device</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $deviceTypes->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Device</h3>
                    <p class="text-sm text-gray-600 mt-1">Total <span id="filtered-count">{{ $devices->count() }}</span> device ditemukan</p>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button id="grid-view" class="p-2 rounded-lg text-telkomsel-red bg-telkomsel-red/10 border border-telkomsel-red/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button id="list-view" class="p-2 rounded-lg text-gray-600 hover:text-telkomsel-red hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="devices-container" class="p-6">
            @if($devices->count() > 0)
                <div id="devices-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($devices as $device)
                    <div class="device-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" 
                         data-device-id="{{ $device->device_id }}" 
                         data-room-id="{{ $device->room_id }}"
                         data-device-type="{{ strtolower($device->device_type ?? '') }}"
                         data-room-name="{{ $device->room->room_name ?? '' }}">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-blue rounded-lg p-2 relative">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <!-- Status indicator -->
                                    <div class="absolute -top-1 -right-1 w-3 h-3 device-status-{{ $device->status ?? 'unknown' }} rounded-full border-2 border-white"></div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $device->device_name }}</h4>
                                    <p class="text-sm text-gray-600">ID: #{{ $device->device_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-device-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-device-id="{{ $device->device_id }}"
                                    data-device-name="{{ $device->device_name }}"
                                    data-device-type="{{ $device->device_type }}"
                                    data-serial-number="{{ $device->serial_number }}"
                                    data-room-id="{{ $device->room_id }}"
                                    title="Edit Device"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-device-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-device-id="{{ $device->device_id }}"
                                    data-device-name="{{ $device->device_name }}"
                                    title="Hapus Device"
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
                                <span class="font-semibold text-telkomsel-blue text-sm">{{ $device->room->room_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Tipe</span>
                                <span class="font-semibold text-gray-900 text-sm">{{ ucfirst($device->device_type) ?? 'N/A' }}</span>
                            </div>
                            @if($device->serial_number)
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Serial Number</span>
                                <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $device->serial_number }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                    @php
                        $checkRoute = Route::has('device.check') 
                            ? route('device.check', $device->device_id) 
                            : '#';
                    @endphp
                    
                    @if($checkRoute !== '#')
                        <a href="{{ $checkRoute }}" 
                           class="flex-1 bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                            Cek Device
                        </a>
                    @else
                        <button 
                            onclick="showComingSoonNotification()"
                            class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-center block text-sm font-medium"
                            disabled>
                            Cek Device (Coming Soon)
                        </button>
                    @endif
                    
                    <button 
                        class="bg-telkomsel-yellow/20 text-telkomsel-yellow px-4 py-2 rounded-lg hover:bg-telkomsel-yellow/30 transition-colors text-sm font-medium"
                        onclick="showHistoryModal({{ $device->device_id }})"
                        title="History Check"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>

                    </div>
                    @endforeach
                </div>
            @else
                <div id="empty-state" class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Device</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan device pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-device-btn').click()"
                    >
                        Tambah Device Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Device Modal -->
<div id="device-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Device</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="device-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="device-id" name="device_id">
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div>
                <label for="room-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Ruangan <span class="text-red-500">*</span>
                </label>
                <select 
                    id="room-select" 
                    name="room_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                >
                    <option value="">Pilih Ruangan</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->room_id }}">{{ $room->room_name }}</option>
                    @endforeach
                </select>
                <div id="room_id-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div>
                <label for="device-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Device <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="device-name" 
                    name="device_name" 
                    placeholder="Contoh: PC-01, Projector-A, Router-Main"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="100"
                >
                <div id="device_name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                <div class="text-gray-500 text-xs mt-1">
                    <span id="device-char-count">0</span>/100 karakter
                </div>
            </div>
            
            <div>
                <label for="device-type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipe Device
                </label>
                <select 
                    id="device-type" 
                    name="device_type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                >
                    <option value="">Pilih Tipe Device</option>
                    <option value="Computer">Computer</option>
                    <option value="Smartboard">Smartboard</option>
                    <option value="SmartTV">SmartTV</option>
                    <option value="Digital_Signage">Digital Signage</option>
                    <option value="VideoWall">VideoWall</option>
                    <option value="Mini_PC">Mini PC</option>
                    <option value="Polycom">Polycom</option>
                    <option value="TV_Samsung_85">TV Samsung 85</option>
                    <option value="other">Other</option>
                </select>
                <div id="device_type-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div>
                <label for="serial-number" class="block text-sm font-medium text-gray-700 mb-2">
                    Serial Number
                </label>
                <input 
                    type="text" 
                    id="serial-number" 
                    name="serial_number" 
                    placeholder="Opsional: Serial number atau kode unik"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    maxlength="100"
                >
                <div id="serial_number-error" class="text-red-500 text-sm mt-1 hidden"></div>
                <div class="text-gray-500 text-xs mt-1">
                    <span id="serial-char-count">0</span>/100 karakter
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
                Apakah Anda yakin ingin menghapus device "<span id="delete-device-name" class="font-semibold"></span>"?
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
    const addDeviceBtn = document.getElementById('add-device-btn');
    const deviceModal = document.getElementById('device-modal');
    const deleteModal = document.getElementById('delete-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const deviceForm = document.getElementById('device-form');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const roomFilter = document.getElementById('room-filter');
    const typeFilter = document.getElementById('type-filter');
    const searchDevices = document.getElementById('search-devices');
    const refreshBtn = document.getElementById('refresh-btn');
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const devicesContainer = document.getElementById('devices-container');
    const devicesGrid = document.getElementById('devices-grid');
    const filteredCount = document.getElementById('filtered-count');
    const totalDevicesElement = document.getElementById('total-devices');
    const deviceNameInput = document.getElementById('device-name');
    const serialNumberInput = document.getElementById('serial-number');
    const deviceCharCount = document.getElementById('device-char-count');
    const serialCharCount = document.getElementById('serial-char-count');
    const roomSelect = document.getElementById('room-select');
    const deviceTypeSelect = document.getElementById('device-type');
    const deviceIdInput = document.getElementById('device-id');
    const formMethod = document.getElementById('form-method');
    
    // Delete modal elements
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const deleteDeviceName = document.getElementById('delete-device-name');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');
    
    let currentView = 'grid';
    let deleteDeviceId = null;
    
    // Initialize
    init();
    
    function init() {
        bindEvents();
        updateCharCounts();
        filterDevices();
    }
    
    function bindEvents() {
        // Modal events
        addDeviceBtn?.addEventListener('click', () => openModal('add'));
        closeModal?.addEventListener('click', closeDeviceModal);
        cancelBtn?.addEventListener('click', closeDeviceModal);
        deviceModal?.addEventListener('click', (e) => {
            if (e.target === deviceModal) closeDeviceModal();
        });
        
        // Delete modal events
        cancelDeleteBtn?.addEventListener('click', closeDeleteModal);
        deleteModal?.addEventListener('click', (e) => {
            if (e.target === deleteModal) closeDeleteModal();
        });
        confirmDeleteBtn?.addEventListener('click', confirmDelete);
        
        // Form events
        deviceForm?.addEventListener('submit', handleSubmit);
        deviceNameInput?.addEventListener('input', updateCharCounts);
        serialNumberInput?.addEventListener('input', updateCharCounts);
        
        // Filter and search events
        roomFilter?.addEventListener('change', filterDevices);
        typeFilter?.addEventListener('change', filterDevices);
        searchDevices?.addEventListener('input', debounce(filterDevices, 300));
        refreshBtn?.addEventListener('click', refreshData);
        
        // View toggle events
        gridViewBtn?.addEventListener('click', () => toggleView('grid'));
        listViewBtn?.addEventListener('click', () => toggleView('list'));
        
        // Dynamic event binding for device cards
        bindDeviceCardEvents();
        
        // Keyboard shortcuts
        document.addEventListener('keydown', handleKeyboardShortcuts);
    }
    
    function bindDeviceCardEvents() {
        // Edit buttons
        document.querySelectorAll('.edit-device-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const deviceId = this.dataset.deviceId;
                const deviceName = this.dataset.deviceName;
                const deviceType = this.dataset.deviceType;
                const serialNumber = this.dataset.serialNumber;
                const roomId = this.dataset.roomId;
                openModal('edit', { deviceId, deviceName, deviceType, serialNumber, roomId });
            });
        });
        
        // Delete buttons
        document.querySelectorAll('.delete-device-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const deviceId = this.dataset.deviceId;
                const deviceName = this.dataset.deviceName;
                openDeleteModal(deviceId, deviceName);
            });
        });
    }
    
    function openModal(mode, data = null) {
        const isEdit = mode === 'edit';
        
        // Update modal title and button text
        modalTitle.textContent = isEdit ? 'Edit Device' : 'Tambah Device';
        submitText.textContent = isEdit ? 'Update' : 'Simpan';
        
        // Reset form
        deviceForm.reset();
        clearErrors();
        
        if (isEdit && data) {
            deviceIdInput.value = data.deviceId;
            deviceNameInput.value = data.deviceName || '';
            deviceTypeSelect.value = data.deviceType || '';
            serialNumberInput.value = data.serialNumber || '';
            roomSelect.value = data.roomId;
            formMethod.value = 'PUT';
        } else {
            deviceIdInput.value = '';
            formMethod.value = 'POST';
        }
        
        updateCharCounts();
        showModal(deviceModal);
        
        // Focus on first input
        setTimeout(() => {
            roomSelect.focus();
        }, 100);
    }
    
    function closeDeviceModal() {
        hideModal(deviceModal);
        clearErrors();
    }
    
    function openDeleteModal(deviceId, deviceName) {
        deleteDeviceId = deviceId;
        deleteDeviceName.textContent = deviceName;
        showModal(deleteModal);
    }
    
    function closeDeleteModal() {
        hideModal(deleteModal);
        deleteDeviceId = null;
    }
    
    function showModal(modal) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.bg-white').classList.remove('scale-95');
            modal.querySelector('.bg-white').classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }
    
    function hideModal(modal) {
        modal.querySelector('.bg-white').classList.remove('scale-100');
        modal.querySelector('.bg-white').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
    
    function handleSubmit(e) {
        e.preventDefault();
        
        if (!validateForm()) return;
        
        const formData = new FormData(deviceForm);
        const deviceId = deviceIdInput.value;
        const isEdit = deviceId !== '';
        const url = isEdit ? `/devices/${deviceId}` : '/devices';
        const method = isEdit ? 'PUT' : 'POST';
        
        setLoading(true);
        
        // Convert FormData to JSON for Laravel API
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        // Add CSRF token
        data._token = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value;
        
        if (isEdit) {
            data._method = 'PUT';
        }
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            setLoading(false);
            
            if (result.success) {
                showNotification(result.message || 'Device berhasil disimpan!', 'success');
                closeDeviceModal();
                refreshData();
            } else {
                if (result.errors) {
                    showValidationErrors(result.errors);
                } else {
                    showNotification(result.message || 'Terjadi kesalahan!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            setLoading(false);
            showNotification('Terjadi kesalahan pada server!', 'error');
        });
    }
    
    function confirmDelete() {
        if (!deleteDeviceId) return;
        
        setDeleteLoading(true);
        
        const data = {
            _token: document.querySelector('meta[name="csrf-token"]')?.content || 
                   document.querySelector('input[name="_token"]')?.value,
            _method: 'DELETE'
        };
        
        fetch(`/devices/${deleteDeviceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            setDeleteLoading(false);
            
            if (result.success) {
                showNotification(result.message || 'Device berhasil dihapus!', 'success');
                closeDeleteModal();
                refreshData();
            } else {
                showNotification(result.message || 'Gagal menghapus device!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            setDeleteLoading(false);
            showNotification('Terjadi kesalahan pada server!', 'error');
        });
    }
    
    function validateForm() {
        clearErrors();
        let isValid = true;
        
        // Validate room selection
        if (!roomSelect.value) {
            showError('room_id-error', 'Pilih ruangan terlebih dahulu');
            isValid = false;
        }
        
        // Validate device name
        const deviceName = deviceNameInput.value.trim();
        if (!deviceName) {
            showError('device_name-error', 'Nama device tidak boleh kosong');
            isValid = false;
        } else if (deviceName.length < 3) {
            showError('device_name-error', 'Nama device minimal 3 karakter');
            isValid = false;
        } else if (deviceName.length > 100) {
            showError('device_name-error', 'Nama device maksimal 100 karakter');
            isValid = false;
        }
        
        // Validate serial number length
        const serialNumber = serialNumberInput.value.trim();
        if (serialNumber && serialNumber.length > 100) {
            showError('serial_number-error', 'Serial number maksimal 100 karakter');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showValidationErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement && messages.length > 0) {
                showError(`${field}-error`, messages[0]);
            }
        }
    }
    
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            
            // Add error styling to input
            const input = errorElement.previousElementSibling;
            if (input) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            }
        }
    }
    
    function clearErrors() {
        document.querySelectorAll('[id$="-error"]').forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });
        
        // Remove error styling
        document.querySelectorAll('input, select').forEach(input => {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        });
    }
    
    function updateCharCounts() {
        // Device name character count
        const deviceCount = deviceNameInput?.value.length || 0;
        if (deviceCharCount) {
            deviceCharCount.textContent = deviceCount;
            updateCharCountColor(deviceCharCount, deviceCount, 100);
        }
        
        // Serial number character count
        const serialCount = serialNumberInput?.value.length || 0;
        if (serialCharCount) {
            serialCharCount.textContent = serialCount;
            updateCharCountColor(serialCharCount, serialCount, 100);
        }
    }
    
    function updateCharCountColor(element, count, max) {
        element.classList.remove('text-gray-500', 'text-yellow-600', 'text-red-500');
        
        if (count > max) {
            element.classList.add('text-red-500');
        } else if (count > max * 0.8) {
            element.classList.add('text-yellow-600');
        } else {
            element.classList.add('text-gray-500');
        }
    }
    
    function filterDevices() {
        const roomValue = roomFilter?.value || '';
        const typeValue = typeFilter?.value || '';
        const searchValue = searchDevices?.value.toLowerCase().trim() || '';
        const deviceCards = document.querySelectorAll('.device-card');
        
        let visibleCount = 0;
        
        deviceCards.forEach(card => {
            const roomId = card.dataset.roomId;
            const roomName = card.dataset.roomName.toLowerCase();
            const deviceType = card.dataset.deviceType;
            const deviceName = card.querySelector('h4').textContent.toLowerCase();
            const deviceId = card.dataset.deviceId;
            
            let shouldShow = true;
            
            // Filter by room
            if (roomValue && roomId !== roomValue) {
                shouldShow = false;
            }
            
            // Filter by device type
            if (typeValue && deviceType !== typeValue) {
                shouldShow = false;
            }
            
            // Filter by search
            if (searchValue && 
                !deviceName.includes(searchValue) && 
                !roomName.includes(searchValue) && 
                !deviceId.includes(searchValue) &&
                !deviceType.includes(searchValue)) {
                shouldShow = false;
            }
            
            if (shouldShow) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update count
        if (filteredCount) {
            filteredCount.textContent = visibleCount;
        }
        
        // Show/hide empty state
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0 && deviceCards.length > 0) {
            if (!emptyState) {
                showEmptySearchState();
            }
        } else if (emptyState) {
            emptyState.remove();
        }
    }
    
    function showEmptySearchState() {
        const emptyHtml = `
            <div id="empty-state" class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600 mb-4">Tidak ditemukan device yang sesuai dengan pencarian Anda.</p>
                <button 
                    class="text-telkomsel-red hover:text-telkomsel-dark-red font-medium"
                    onclick="resetFilters()"
                >
                    Reset Filter
                </button>
            </div>
        `;
        
        if (devicesGrid) {
            devicesGrid.insertAdjacentHTML('afterend', emptyHtml);
        }
    }
    
    function resetFilters() {
        if (searchDevices) searchDevices.value = '';
        if (roomFilter) roomFilter.value = '';
        if (typeFilter) typeFilter.value = '';
        filterDevices();
    }
    
    function toggleView(viewType) {
        currentView = viewType;
        
        if (viewType === 'grid') {
            gridViewBtn?.classList.add('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            gridViewBtn?.classList.remove('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            
            listViewBtn?.classList.add('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            listViewBtn?.classList.remove('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            
            if (devicesGrid) {
                devicesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
            }
        } else {
            listViewBtn?.classList.add('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            listViewBtn?.classList.remove('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            
            gridViewBtn?.classList.add('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            gridViewBtn?.classList.remove('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            
            if (devicesGrid) {
                devicesGrid.className = 'space-y-4';
            }
        }
    }
    
    function refreshData() {
        // Add loading state to refresh button
        const originalHtml = refreshBtn?.innerHTML || '';
        if (refreshBtn) {
            refreshBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="hidden sm:inline">Memuat...</span>
            `;
            refreshBtn.disabled = true;
        }
        
        // Simulate refresh (in real app, this would reload data)
        setTimeout(() => {
            if (refreshBtn) {
                refreshBtn.innerHTML = originalHtml;
                refreshBtn.disabled = false;
            }
            showNotification('Data berhasil diperbarui!', 'success');
            
            // Re-bind events after refresh
            bindDeviceCardEvents();
        }, 1000);
    }
    
    function setLoading(loading) {
        if (loading) {
            if (submitBtn) submitBtn.disabled = true;
            if (submitText) submitText.classList.add('hidden');
            if (loadingSpinner) loadingSpinner.classList.remove('hidden');
        } else {
            if (submitBtn) submitBtn.disabled = false;
            if (submitText) submitText.classList.remove('hidden');
            if (loadingSpinner) loadingSpinner.classList.add('hidden');
        }
    }
    
    function setDeleteLoading(loading) {
        if (loading) {
            if (confirmDeleteBtn) confirmDeleteBtn.disabled = true;
            if (deleteText) deleteText.classList.add('hidden');
            if (deleteSpinner) deleteSpinner.classList.remove('hidden');
        } else {
            if (confirmDeleteBtn) confirmDeleteBtn.disabled = false;
            if (deleteText) deleteText.classList.remove('hidden');
            if (deleteSpinner) deleteSpinner.classList.add('hidden');
        }
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full transform transition-all duration-300 translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-500' : 
                       type === 'error' ? 'bg-red-500' : 
                       type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
        
        const icon = type === 'success' ? 'M5 13l4 4L19 7' :
                    type === 'error' ? 'M6 18L18 6M6 6l12 12' :
                    type === 'warning' ? 'M12 8v4m0 4h.01' :
                    'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        
        notification.innerHTML = `
            <div class="${bgColor} text-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"/>
                </svg>
                <p class="flex-1">${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    function handleKeyboardShortcuts(e) {
        // Ctrl/Cmd + N: Add new device
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            if (!deviceModal?.classList.contains('hidden')) return;
            openModal('add');
        }
        
        // Escape: Close modals
        if (e.key === 'Escape') {
            if (deviceModal && !deviceModal.classList.contains('hidden')) {
                closeDeviceModal();
            }
            if (deleteModal && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
        
        // Ctrl/Cmd + F: Focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchDevices?.focus();
        }
        
        // Ctrl/Cmd + R: Refresh (prevent default browser refresh)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshData();
        }
    }
    
    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Make resetFilters available globally
    window.resetFilters = resetFilters;
});
</script>
@endpush