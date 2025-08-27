@extends('layouts.app')

@section('title', 'Manajemen Device')
@section('page-title', 'Manajemen Device')
@section('page-subtitle', 'Kelola device untuk sistem pengecekan')

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
                    >
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Update the header actions section -->
        <div class="flex items-center space-x-3">
            <button 
                id="export-excel-btn"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Excel</span>
            </button>

            <button 
                id="add-device-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Device</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Device dengan Gambar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $devices->whereNotNull('image_path')->count() }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Ruangan dengan Device</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $devices->groupBy('room_id')->count() }}</p>
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
                                @if($device->image_path)
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 relative">
                                        <img src="{{ asset('storage/' . $device->image_path) }}" 
                                             alt="{{ $device->device_name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjY0IiBoZWlnaHQ9IjY0IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyNEg0NFY0MEgyMFYyNFoiIGZpbGw9IiNEM0Q3RDEiLz4KPHBhdGggZD0iTTI4IDMySDM2VjQwSDI4VjMyWiIgZmlsbD0iI0QzRDdEMSIvPgo8L3N2Zz4K'">
                                        <!-- Status indicator -->
                                        <div class="absolute -top-1 -right-1 w-3 h-3 device-status-{{ $device->status ?? 'unknown' }} rounded-full border-2 border-white"></div>
                                    </div>
                                @else
                                    <div class="bg-telkomsel-blue rounded-lg p-2 relative">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <!-- Status indicator -->
                                        <div class="absolute -top-1 -right-1 w-3 h-3 device-status-{{ $device->status ?? 'unknown' }} rounded-full border-2 border-white"></div>
                                    </div>
                                @endif
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
                                    data-category="{{ $device->category }}"
                                    data-merk="{{ $device->merk }}"
                                    data-tahun-po="{{ $device->tahun_po }}"
                                    data-no-po="{{ $device->no_po }}"
                                    data-tahun-bast="{{ $device->tahun_bast }}"
                                    data-no-bast="{{ $device->no_bast }}"
                                    data-condition="{{ $device->condition }}"
                                    data-notes="{{ $device->notes }}"
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

                        <div class="space-y-3">
                            <!-- Add condition status -->
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Kondisi</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $device->condition === 'baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($device->condition) }}
                                </span>
                            </div>
                            
                            <!-- Add merk -->
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Merk</span>
                                <span class="font-medium text-gray-900">{{ $device->merk }}</span>
                            </div>
                            
                            <!-- Add PO/BAST info if exists -->
                            @if($device->tahun_po || $device->no_po)
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">PO</span>
                                <span class="text-sm text-gray-900">
                                    {{ $device->no_po }} ({{ $device->tahun_po }})
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button 
                                class="edit-device-image-btn flex-1 bg-telkomsel-blue text-white px-4 py-2 rounded-lg hover:bg-telkomsel-blue/80 transition-colors text-sm font-medium flex items-center justify-center space-x-2"
                                data-device-id="{{ $device->device_id }}"
                                data-device-name="{{ $device->device_name }}"
                                data-current-image="{{ $device->image_path }}"
                                title="Edit Device Image">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $device->image_path ? 'Edit Gambar' : 'Tambah Gambar' }}</span>
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
<div id="device-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-start justify-center p-4 overflow-y-auto">
    <div class="relative min-h-[calc(100vh-2rem)] flex items-center justify-center py-8">
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
                
                <div>
                    <label for="device-image-form" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Device
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-telkomsel-red transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="device-image-form" class="relative cursor-pointer bg-white rounded-md font-medium text-telkomsel-red hover:text-telkomsel-dark-red focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-telkomsel-red">
                                    <span>Upload gambar</span>
                                    <input id="device-image-form" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF max 10MB</p>
                        </div>
                    </div>
                    <div id="device_image_error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- New fields for category, merk, tahun, no po, kondisi, and notes -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="category" 
                        name="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        required
                    >
                    <div id="category-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700 mb-2">
                        Merk <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="merk" 
                        name="merk" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        required
                    >
                    <div id="merk-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="tahun_po" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun PO
                        </label>
                        <input 
                            type="number" 
                            id="tahun_po" 
                            name="tahun_po" 
                            min="1900" 
                            max="{{ date('Y') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        >
                        <div id="tahun_po-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="no_po" class="block text-sm font-medium text-gray-700 mb-2">
                            No PO
                        </label>
                        <input 
                            type="text" 
                            id="no_po" 
                            name="no_po" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        >
                        <div id="no_po-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="tahun_bast" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun BAST
                        </label>
                        <input 
                            type="number" 
                            id="tahun_bast" 
                            name="tahun_bast" 
                            min="1900" 
                            max="{{ date('Y') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        >
                        <div id="tahun_bast-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="no_bast" class="block text-sm font-medium text-gray-700 mb-2">
                            No BAST
                        </label>
                        <input 
                            type="text" 
                            id="no_bast" 
                            name="no_bast" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        >
                        <div id="no_bast-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="condition" 
                        name="condition" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        required
                    >
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                    <div id="condition-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    ></textarea>
                    <div id="notes-error" class="text-red-500 text-sm mt-1 hidden"></div>
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
</div>

<!-- Image Upload Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-start justify-center p-4 overflow-y-auto">  
    <div class="relative min-h-[calc(100vh-2rem)] flex items-center justify-center py-8">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 id="image-modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Gambar Device</h3>
                    <button id="close-image-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="image-form" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="image-device-id" name="device_id">
                <input type="hidden" id="image-form-method" name="_method" value="POST">
                
                <!-- Current Image Preview -->
                <div id="current-image-preview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    <div class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 border border-gray-300">
                        <img id="current-image" src="" alt="Current Image" class="w-full h-full object-cover">
                    </div>
                </div>
                
                <!-- Image Upload -->
                <div>
                    <label for="device-image" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Device <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-telkomsel-red transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="device-image" class="relative cursor-pointer bg-white rounded-md font-medium text-telkomsel-red hover:text-telkomsel-dark-red focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-telkomsel-red">
                                    <span>Upload gambar</span>
                                    <input id="device-image" name="image" type="file" class="sr-only" accept="image/*" required>
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF max 10MB</p>
                        </div>
                    </div>
                    <div id="image-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    
                    <!-- Image Preview -->
                    <div id="image-preview-container" class="hidden mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 border border-gray-300">
                            <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-4 pt-4">
                    <button 
                        type="button" 
                        id="cancel-image-btn"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        id="submit-image-btn"
                        class="flex-1 bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-4 py-3 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all font-medium disabled:opacity-50"
                    >
                        <span id="submit-image-text">Upload</span>
                        <svg id="loading-image-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-start justify-center p-4 overflow-y-auto">
    <div class="relative min-h-[calc(100vh-2rem)] flex items-center justify-center py-8">
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
</div>
@endsection

@push('scripts')
<script>
    // Elements
    const addDeviceBtn = document.getElementById('add-device-btn');
    const deviceModal = document.getElementById('device-modal');
    const deleteModal = document.getElementById('delete-modal');
    const imageModal = document.getElementById('image-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const imageForm = document.getElementById('image-form');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const roomFilter = document.getElementById('room-filter');
    const typeFilter = document.getElementById('type-filter');
    const searchDevices = document.getElementById('search-devices');
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
    const deviceForm = document.getElementById('device-form');

    // Image modal elements
    const closeImageModal = document.getElementById('close-image-modal');
    const cancelImageBtn = document.getElementById('cancel-image-btn');
    const submitImageBtn = document.getElementById('submit-image-btn');
    const submitImageText = document.getElementById('submit-image-text');
    const loadingImageSpinner = document.getElementById('loading-image-spinner');
    const deviceImageInput = document.getElementById('device-image');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const currentImagePreview = document.getElementById('current-image-preview');
    const currentImage = document.getElementById('current-image');
    const imageModalTitle = document.getElementById('image-modal-title');
    const imageDeviceId = document.getElementById('image-device-id');
    const imageFormMethod = document.getElementById('image-form-method');

    // Delete modal elements
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const deleteDeviceName = document.getElementById('delete-device-name');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');

    let currentView = 'grid';
    let deleteDeviceId = null;
    let currentImageDeviceId = null;

    // Initialize
    init();

    function init() {
        bindEvents();
        updateCharCounts();
        filterDevices();
    }

    function bindEvents() {
        // Modal events
        if (addDeviceBtn) {
            addDeviceBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openModal('add');
            });
        }

        if (closeModal) {
            closeModal.addEventListener('click', function(e) {
                e.preventDefault();
                closeDeviceModal();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeDeviceModal();
            });
        }

        if (deviceModal) {
            deviceModal.addEventListener('click', function(e) {
                if (e.target === deviceModal) {
                    closeDeviceModal();
                }
            });
        }

        // Image modal events
        if (closeImageModal) {
            closeImageModal.addEventListener('click', function(e) {
                e.preventDefault();
                closeImageModalHandler();
            });
        }

        if (cancelImageBtn) {
            cancelImageBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeImageModalHandler();
            });
        }

        if (imageModal) {
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    closeImageModalHandler();
                }
            });
        }

        // Delete modal events
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeDeleteModal();
            });
        }

        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        }

        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                confirmDelete();
            });
        }

        if (imageForm) {
            imageForm.addEventListener('submit', handleImageSubmit);
        }

        if (deviceNameInput) {
            deviceNameInput.addEventListener('input', updateCharCounts);
        }

        if (serialNumberInput) {
            serialNumberInput.addEventListener('input', updateCharCounts);
        }

        // Image input events
        if (deviceImageInput) {
            deviceImageInput.addEventListener('change', handleImageChange);
        }

        // Filter and search events
        if (roomFilter) {
            roomFilter.addEventListener('change', filterDevices);
        }

        if (typeFilter) {
            typeFilter.addEventListener('change', filterDevices);
        }

        if (searchDevices) {
            searchDevices.addEventListener('input', debounce(filterDevices, 300));
        }

        // View toggle events
        if (gridViewBtn) {
            gridViewBtn.addEventListener('click', function() {
                toggleView('grid');
            });
        }

        if (listViewBtn) {
            listViewBtn.addEventListener('click', function() {
                toggleView('list');
            });
        }

        // Dynamic event binding for device cards
        bindDeviceCardEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', handleKeyboardShortcuts);

        // Form submit event
        if (deviceForm) {
            deviceForm.addEventListener('submit', handleSubmit);
            console.log('Form submit handler attached');
        }
    }

    function bindDeviceCardEvents() {
        // Edit buttons
        const editButtons = document.querySelectorAll('.edit-device-btn');
        editButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const deviceId = this.getAttribute('data-device-id');
                const deviceName = this.getAttribute('data-device-name');
                const deviceType = this.getAttribute('data-device-type');
                const serialNumber = this.getAttribute('data-serial-number');
                const roomId = this.getAttribute('data-room-id');
                const category = this.getAttribute('data-category');
                const merk = this.getAttribute('data-merk');
                const tahunPo = this.getAttribute('data-tahun-po');
                const noPo = this.getAttribute('data-no-po');
                const tahunBast = this.getAttribute('data-tahun-bast');
                const noBast = this.getAttribute('data-no-bast');
                const condition = this.getAttribute('data-condition');
                const notes = this.getAttribute('data-notes');
                
                openModal('edit', { 
                    deviceId,
                    deviceName, 
                    deviceType, 
                    serialNumber, 
                    roomId,
                    category,
                    merk,
                    tahunPo,
                    noPo,
                    tahunBast,
                    noBast,
                    condition,
                    notes
                });
            });
        });

        // Image buttons
        const imageButtons = document.querySelectorAll('.edit-device-image-btn');
        imageButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const deviceId = this.getAttribute('data-device-id');
                const deviceName = this.getAttribute('data-device-name');
                const currentImage = this.getAttribute('data-current-image');
                
                openImageModal(deviceId, deviceName, currentImage);
            });
        });

        // Delete buttons
        const deleteButtons = document.querySelectorAll('.delete-device-btn');
        deleteButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const deviceId = this.getAttribute('data-device-id');
                const deviceName = this.getAttribute('data-device-name');
                
                openDeleteModal(deviceId, deviceName);
            });
        });
    }

    function openModal(mode, data) {
        data = data || {};
        const isEdit = mode === 'edit';

        // Update modal title and button text
        if (modalTitle) {
            modalTitle.textContent = isEdit ? 'Edit Device' : 'Tambah Device';
        }
        if (submitText) {
            submitText.textContent = isEdit ? 'Update' : 'Simpan';
        }

        // Reset form
        if (deviceForm) {
            deviceForm.reset();
        }
        clearErrors();

        if (isEdit && data) {
            // Set values for existing fields
            if (deviceIdInput) deviceIdInput.value = data.deviceId || '';
            if (deviceNameInput) deviceNameInput.value = data.deviceName || '';
            if (deviceTypeSelect) deviceTypeSelect.value = data.deviceType || '';
            if (serialNumberInput) serialNumberInput.value = data.serialNumber || '';
            if (roomSelect) roomSelect.value = data.roomId || '';
            
            // Set values for new fields
            if (document.getElementById('category')) {
                document.getElementById('category').value = data.category || '';
            }
            if (document.getElementById('merk')) {
                document.getElementById('merk').value = data.merk || '';
            }
            if (document.getElementById('tahun_po')) {
                document.getElementById('tahun_po').value = data.tahunPo || '';
            }
            if (document.getElementById('no_po')) {
                document.getElementById('no_po').value = data.noPo || '';
            }
            if (document.getElementById('tahun_bast')) {
                document.getElementById('tahun_bast').value = data.tahunBast || '';
            }
            if (document.getElementById('no_bast')) {
                document.getElementById('no_bast').value = data.noBast || '';
            }
            if (document.getElementById('condition')) {
                document.getElementById('condition').value = data.condition || '';
            }
            if (document.getElementById('notes')) {
                document.getElementById('notes').value = data.notes || '';
            }

            if (formMethod) formMethod.value = 'PUT';
        } else {
            if (deviceIdInput) deviceIdInput.value = '';
            if (formMethod) formMethod.value = 'POST';
        }

        updateCharCounts();
        showModal(deviceModal);

        // Focus on first input
        setTimeout(function() {
            if (roomSelect) {
                roomSelect.focus();
            }
        }, 100);
    }

    function closeDeviceModal() {
        hideModal(deviceModal);
        clearErrors();
    }

    function openImageModal(deviceId, deviceName, currentImage) {
        currentImageDeviceId = deviceId;
        
        if (imageDeviceId) {
            imageDeviceId.value = deviceId;
        }
        
        if (imageModalTitle) {
            imageModalTitle.textContent = currentImage ? 'Edit Gambar Device' : 'Tambah Gambar Device';
        }
        
        if (submitImageText) {
            submitImageText.textContent = currentImage ? 'Update' : 'Upload';
        }

        // Show current image if exists
        if (currentImage && currentImage !== 'null' && currentImage !== '') {
            if (currentImage) {
                currentImage.src = '/storage/' + currentImage;
            }
            if (currentImagePreview) {
                currentImagePreview.classList.remove('hidden');
            }
        } else {
            if (currentImagePreview) {
                currentImagePreview.classList.add('hidden');
            }
        }

        // Reset form
        if (imageForm) {
            imageForm.reset();
        }
        if (imagePreviewContainer) {
            imagePreviewContainer.classList.add('hidden');
        }
        hideImageError();

        showModal(imageModal);
    }

    function closeImageModalHandler() {
        hideModal(imageModal);
        currentImageDeviceId = null;
        if (imagePreviewContainer) {
            imagePreviewContainer.classList.add('hidden');
        }
        if (currentImagePreview) {
            currentImagePreview.classList.add('hidden');
        }
        hideImageError();
    }

    function openDeleteModal(deviceId, deviceName) {
        deleteDeviceId = deviceId;
        if (deleteDeviceName) {
            deleteDeviceName.textContent = deviceName;
        }
        showModal(deleteModal);
    }

    function closeDeleteModal() {
        hideModal(deleteModal);
        deleteDeviceId = null;
    }

    function showModal(modal) {
        if (!modal) return;
        
        modal.classList.remove('hidden');
        setTimeout(function() {
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function hideModal(modal) {
        if (!modal) return;
        
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
        setTimeout(function() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    async function handleSubmit(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        if (!validateForm()) {
            console.log('Form validation failed');
            return;
        }
        console.log('Form validation passed');

        if (!validateForm()) return;

        const formData = new FormData(deviceForm);
        const deviceId = document.getElementById('device-id').value;
        const isEdit = deviceId !== '';
        const url = isEdit ? `/devices/${deviceId}` : '/devices';

        setLoading(true);

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message || 'Device berhasil disimpan!', 'success');
                closeDeviceModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        showError(`${key.replace('_', '-')}-error`, result.errors[key][0]);
                    });
                } else {
                    showNotification(result.message || 'Terjadi kesalahan!', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan pada server!', 'error');
        } finally {
            setLoading(false);
        }
    }

    function handleImageSubmit(e) {
        e.preventDefault();

        if (!validateImageForm()) return;

        const formData = new FormData(imageForm);
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const tokenInput = document.querySelector('input[name="_token"]');
        formData.append('_token', csrfToken ? csrfToken.content : (tokenInput ? tokenInput.value : ''));

        setImageLoading(true);

        fetch('/devices/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            setImageLoading(false);

            if (result.success) {
                showNotification(result.message || 'Gambar berhasil diupload!', 'success');
                closeImageModalHandler();
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                if (result.errors) {
                    showImageError(result.errors.image ? result.errors.image[0] : 'Terjadi kesalahan!');
                } else {
                    showImageError(result.message || 'Terjadi kesalahan!');
                }
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            setImageLoading(false);
            showImageError('Terjadi kesalahan pada server!');
        });
    }

    function handleImageChange(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            showImageError('Ukuran file maksimal 10MB');
            e.target.value = '';
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showImageError('File harus berupa gambar');
            e.target.value = '';
            return;
        }

        hideImageError();

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            if (imagePreview) {
                imagePreview.src = e.target.result;
            }
            if (imagePreviewContainer) {
                imagePreviewContainer.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    function validateImageForm() {
        hideImageError();

        if (!deviceImageInput.files || deviceImageInput.files.length === 0) {
            showImageError('Pilih gambar terlebih dahulu');
            return false;
        }

        return true;
    }

    function showImageError(message) {
        const errorElement = document.getElementById('image-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    function hideImageError() {
        const errorElement = document.getElementById('image-error');
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    }

    function confirmDelete() {
        if (!deleteDeviceId) return;

        setDeleteLoading(true);

        const data = {};
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const tokenInput = document.querySelector('input[name="_token"]');
        data._token = csrfToken ? csrfToken.content : (tokenInput ? tokenInput.value : '');
        data._method = 'DELETE';

        fetch('/devices/' + deleteDeviceId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            setDeleteLoading(false);

            if (result.success) {
                showNotification(result.message || 'Device berhasil dihapus!', 'success');
                closeDeleteModal();
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showNotification(result.message || 'Gagal menghapus device!', 'error');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            setDeleteLoading(false);
            showNotification('Terjadi kesalahan pada server!', 'error');
        });
    }

    function validateForm() {
        clearErrors();
        let isValid = true;

        // Validate room selection
        if (!document.getElementById('room-select').value) {
            showError('room_id-error', 'Pilih ruangan terlebih dahulu');
            isValid = false;
        }

        // Validate device name
        const deviceName = document.getElementById('device-name').value.trim();
        if (!deviceName) {
            showError('device_name-error', 'Nama device harus diisi');
            isValid = false;
        }

        // Validate device type
        if (!document.getElementById('device-type').value) {
            showError('device_type-error', 'Tipe device harus dipilih');
            isValid = false;
        }

        // Add validation for new required fields
        if (!document.getElementById('category').value.trim()) {
            showError('category-error', 'Kategori harus diisi');
            isValid = false;
        }

        if (!document.getElementById('merk').value.trim()) {
            showError('merk-error', 'Merk harus diisi');
            isValid = false;
        }

        if (!document.getElementById('condition').value) {
            showError('condition-error', 'Kondisi harus dipilih');
            isValid = false;
        }

        return isValid;
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
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(function(error) {
            error.classList.add('hidden');
            error.textContent = '';
        });

        // Remove error styling
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(function(input) {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        });
    }

    function updateCharCounts() {
        // Device name character count
        if (deviceNameInput && deviceCharCount) {
            const deviceCount = deviceNameInput.value.length || 0;
            deviceCharCount.textContent = deviceCount;
            updateCharCountColor(deviceCharCount, deviceCount, 100);
        }

        // Serial number character count
        if (serialNumberInput && serialCharCount) {
            const serialCount = serialNumberInput.value.length || 0;
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
        const roomValue = roomFilter ? roomFilter.value : '';
        const typeValue = typeFilter ? typeFilter.value : '';
        const searchValue = searchDevices ? searchDevices.value.toLowerCase().trim() : '';
        const deviceCards = document.querySelectorAll('.device-card');

        let visibleCount = 0;

        deviceCards.forEach(function(card) {
            const roomId = card.getAttribute('data-room-id');
            const roomName = card.getAttribute('data-room-name').toLowerCase();
            const deviceType = card.getAttribute('data-device-type');
            const deviceNameElement = card.querySelector('h4');
            const deviceName = deviceNameElement ? deviceNameElement.textContent.toLowerCase() : '';
            const deviceId = card.getAttribute('data-device-id');

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
        const emptyHtml = '<div id="empty-state" class="text-center py-12">' +
            '<svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>' +
            '</svg>' +
            '<h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>' +
            '<p class="text-gray-600 mb-4">Tidak ditemukan device yang sesuai dengan pencarian Anda.</p>' +
            '<button class="text-telkomsel-red hover:text-telkomsel-dark-red font-medium" onclick="resetFilters()">Reset Filter</button>' +
        '</div>';

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
            if (gridViewBtn) {
                gridViewBtn.classList.add('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
                gridViewBtn.classList.remove('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            }

            if (listViewBtn) {
                listViewBtn.classList.add('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
                listViewBtn.classList.remove('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            }

            if (devicesGrid) {
                devicesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
            }
        } else {
            if (listViewBtn) {
                listViewBtn.classList.add('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
                listViewBtn.classList.remove('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
            }

            if (gridViewBtn) {
                gridViewBtn.classList.add('text-gray-600', 'hover:text-telkomsel-red', 'hover:bg-gray-100');
                gridViewBtn.classList.remove('text-telkomsel-red', 'bg-telkomsel-red/10', 'border-telkomsel-red/20');
            }

            if (devicesGrid) {
                devicesGrid.className = 'space-y-4';
            }
        }
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

    function setImageLoading(loading) {
        if (loading) {
            if (submitImageBtn) submitImageBtn.disabled = true;
            if (submitImageText) submitImageText.classList.add('hidden');
            if (loadingImageSpinner) loadingImageSpinner.classList.remove('hidden');
        } else {
            if (submitImageBtn) submitImageBtn.disabled = false;
            if (submitImageText) submitImageText.classList.remove('hidden');
            if (loadingImageSpinner) loadingImageSpinner.classList.add('hidden');
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

    function showNotification(message, type) {
        type = type || 'info';
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 max-w-sm w-full transform transition-all duration-300 translate-x-full';

        const bgColor = type === 'success' ? 'bg-green-500' : 
                       type === 'error' ? 'bg-red-500' : 
                       type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

        const icon = type === 'success' ? 'M5 13l4 4L19 7' :
                    type === 'error' ? 'M6 18L18 6M6 6l12 12' :
                    type === 'warning' ? 'M12 8v4m0 4h.01' :
                    'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

        notification.innerHTML = '<div class="' + bgColor + ' text-white p-4 rounded-lg shadow-lg flex items-center space-x-3">' +
            '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' + icon + '"/>' +
            '</svg>' +
            '<p class="flex-1">' + message + '</p>' +
            '<button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">' +
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' +
                '</svg>' +
            '</button>' +
        '</div>';

        document.body.appendChild(notification);

        // Slide in
        setTimeout(function() {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(function() {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(function() {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    function handleKeyboardShortcuts(e) {
        // Ctrl/Cmd + N: Add new device
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            if (!deviceModal || deviceModal.classList.contains('hidden')) {
                openModal('add');
            }
        }

        // Escape: Close modals
        if (e.key === 'Escape') {
            if (deviceModal && !deviceModal.classList.contains('hidden')) {
                closeDeviceModal();
            }
            if (imageModal && !imageModal.classList.contains('hidden')) {
                closeImageModalHandler();
            }
            if (deleteModal && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }

        // Ctrl/Cmd + F: Focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            if (searchDevices) {
                searchDevices.focus();
            }
        }
    }
    const exportExcelBtn = document.getElementById('export-excel-btn');

    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            try {
                const response = await fetch('/devices/export-excel', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    // Check if response is JSON (error) or blob (file)
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const result = await response.json();
                        showNotification(result.message || 'Gagal mengekspor data', 'error');
                    } else {
                        // Handle successful file download
                        const blob = await response.blob();
                        const filename = response.headers.get('content-disposition')?.split('filename=')[1] || 'devices.xlsx';
                        
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    }
                } else {
                    const error = await response.json();
                    showNotification(error.message || 'Gagal mengekspor data', 'error');
                }
            } catch (error) {
                console.error('Export error:', error);
                showNotification('Terjadi kesalahan saat mengekspor data', 'error');
            }
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction() {
            const args = arguments;
            const later = function() {
                clearTimeout(timeout);
                func.apply(null, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Make resetFilters available globally
    window.resetFilters = resetFilters;
</script>
@endpush