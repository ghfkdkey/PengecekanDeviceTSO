@extends('layouts.app')

@section('title', 'Manajemen Device')
@section('page-title', 'Manajemen Device')
@section('page-subtitle', 'Kelola device untuk sistem pengecekan')

@section('content')
<div 
    class="space-y-6" 
    id="device-manager-container"
    data-floors="{{ $floors->map(function($floor) { return ['floor_id' => $floor->floor_id, 'floor_name' => $floor->floor_name, 'building_id' => $floor->building_id]; })->values()->toJson() }}"
    data-rooms="{{ $rooms->map(function($room) { return ['room_id' => $room->room_id, 'room_name' => $room->room_name, 'floor_id' => $room->floor_id, 'building_id' => $room->floor->building_id]; })->values()->toJson() }}">
    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <div class="flex-1 space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
            <!-- Building Filter -->
            <div class="lg:max-w-xs">
                <select 
                    id="building-filter" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red bg-white"
                >
                    <option value="">Semua Gedung</option>
                    @foreach($buildingsForFilter as $building)
                        <option value="{{ $building->building_id }}" {{ request('building') == $building->building_id ? 'selected' : '' }}>
                            {{ $building->building_name }} ({{ $building->building_code }})
                        </option>
                    @endforeach
                </select>
            </div>
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
                <div id="devices-table" class="overflow-x-auto">
                    <table class="min-w-full table-auto border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Nama Device</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tipe Device</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Ruangan</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Nomor Seri</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Kondisi</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($devices as $device)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $device->device_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($device->device_type) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $device->room->room_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($device->serial_number)
                                        <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $device->serial_number }}</span>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $device->condition === 'baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($device->condition) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <!-- View Button -->
                                    <button 
                                        class="view-device-btn text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                        data-device-id="{{ $device->device_id }}"
                                        data-device-name="{{ $device->device_name }}"
                                        data-device-type="{{ $device->device_type }}"
                                        data-serial-number="{{ $device->serial_number }}"
                                        data-room-name="{{ $device->room->room_name ?? 'N/A' }}"
                                        data-category="{{ $device->category }}"
                                        data-merk="{{ $device->merk }}"
                                        data-tahun-po="{{ $device->tahun_po }}"
                                        data-no-po="{{ $device->no_po }}"
                                        data-tahun-bast="{{ $device->tahun_bast }}"
                                        data-no-bast="{{ $device->no_bast }}"
                                        data-condition="{{ $device->condition }}"
                                        data-notes="{{ $device->notes }}"
                                        data-image-path="{{ $device->image_path }}"
                                        title="View Device"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <!-- Edit Button -->
                                    <button 
                                        class="edit-device-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                        data-device-id="{{ $device->device_id }}"
                                        data-device-name="{{ $device->device_name }}"
                                        data-device-type="{{ $device->device_type }}"
                                        data-serial-number="{{ $device->serial_number }}"
                                        data-room-id="{{ $device->room_id }}"
                                        data-building-id="{{ $device->room->floor->building_id ?? '' }}"
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
                                    <!-- Delete Button -->
                                    <button 
                                        class="delete-device-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                        data-device-id="{{ $device->device_id }}"
                                        data-device-name="{{ $device->device_name }}"
                                        title="Delete Device"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div id="empty-state" class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada device yang ditemukan</h3>
                    <p class="text-gray-600 mb-4">Ayo mulai tambahkan device terlebih dahulu.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-device-btn').click()"
                    >
                        Tambah Device
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Device Modal -->
<div id="device-view-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Detail Device</h3>
                <!-- FIX: Changed id to be unique -->
                <button id="close-view-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mt-4 space-y-2">
                <div id="view-modal-image-container" class="mb-4">
                    <img id="view-device-image" src="https://placehold.co/600x400/e2e8f0/adb5bd?text=No+Image" alt="Device Image" class="w-full h-48 object-cover rounded-lg bg-gray-100">
                </div>
                <div class="grid grid-cols-3 gap-x-4 gap-y-2">
                    <strong class="col-span-1 text-gray-500">Nama</strong><span id="view-device-name" class="col-span-2 text-gray-800 font-medium">: </span>
                    <strong class="col-span-1 text-gray-500">Tipe</strong><span id="view-device-type" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Serial Number</strong><span id="view-serial-number" class="col-span-2 text-gray-700 font-mono text-sm">: </span>
                    <strong class="col-span-1 text-gray-500">Ruangan</strong><span id="view-room" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Kategori</strong><span id="view-category" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Merk</strong><span id="view-merk" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Tahun PO</strong><span id="view-tahun-po" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">No PO</strong><span id="view-no-po" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Tahun BAST</strong><span id="view-tahun-bast" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">No BAST</strong><span id="view-no-bast" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500">Kondisi</strong><span id="view-condition" class="col-span-2 text-gray-700">: </span>
                    <strong class="col-span-1 text-gray-500 align-top">Notes</strong>
                    <div class="col-span-2">
                        <p id="view-notes" class="text-gray-700 relative -left-1"></p>
                    </div>
                </div>
            </div>
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
                    <label for="building-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Gedung <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="building-select" 
                        name="building_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        required
                    >
                        <option value="">Pilih Gedung</option>
                        @foreach($buildingsForFilter as $building)
                            <option value="{{ $building->building_id }}">
                                {{ $building->building_name }} ({{ $building->building_code }})
                            </option>
                        @endforeach
                    </select>
                    <div id="building_id-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="floor-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Lantai <span class="text-red-500">*</span>
                    </label>
                    <select id="floor-select" name="floor_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors" required disabled>
                        <option value="">Pilih Lantai</option>
                    </select>
                    <div id="floor_id-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="room-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="room-select" 
                        name="room_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                        required
                        disabled
                    >
                        <option value="">Pilih Ruangan</option>
                        <!-- Options akan diisi via JavaScript berdasarkan building yang dipilih -->
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
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Device
                    </label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    <div id="image-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    
                    <div id="image-preview-wrapper" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image Preview</label>
                        <div class="relative group">
                            <img id="image-preview-element" src="#" alt="Image Preview" class="rounded-lg object-cover h-48 w-full">
                            <button type="button" id="remove-image-btn" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full p-1 hover:bg-opacity-75 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
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
    const container = document.getElementById('device-manager-container');

    const floorsData = JSON.parse(container.dataset.floors || '[]');
    const roomsData = JSON.parse(container.dataset.rooms || '[]');

    const addDeviceBtn = document.getElementById('add-device-btn');
    const deviceModal = document.getElementById('device-modal');
    const deleteModal = document.getElementById('delete-modal');
    const imageModal = document.getElementById('image-modal');
    const deviceViewModal = document.getElementById('device-view-modal');
    const closeModal = document.getElementById('close-modal');
    const closeViewModal = document.getElementById('close-view-modal'); 
    const cancelBtn = document.getElementById('cancel-btn');
    const imageForm = document.getElementById('image-form');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const roomFilter = document.getElementById('room-filter');
    const typeFilter = document.getElementById('type-filter');
    const buildingFilter = document.getElementById('building-filter');
    const searchDevices = document.getElementById('search-devices');
    const devicesContainer = document.getElementById('devices-container');
    const devicesGrid = document.getElementById('devices-grid');
    const filteredCount = document.getElementById('filtered-count');
    const totalDevicesElement = document.getElementById('total-devices');
    const deviceNameInput = document.getElementById('device-name');
    const serialNumberInput = document.getElementById('serial-number');
    const deviceCharCount = document.getElementById('device-char-count');
    const serialCharCount = document.getElementById('serial-char-count');
    const roomSelect = document.getElementById('room-select');
    const buildingSelect = document.getElementById('building-select');
    const floorSelect = document.getElementById('floor-select');
    const deviceTypeSelect = document.getElementById('device-type');
    const deviceIdInput = document.getElementById('device-id');
    const formMethod = document.getElementById('form-method');
    const deviceForm = document.getElementById('device-form');
    const imageInput = document.getElementById('image');
    const imagePreviewWrapper = document.getElementById('image-preview-wrapper');
    const imagePreviewElement = document.getElementById('image-preview-element');
    const removeImageBtn = document.getElementById('remove-image-btn');

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

        if (closeViewModal) {
            closeViewModal.addEventListener('click', function(e) {
                e.preventDefault();
                closeViewModalHandler();
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
        
        if (deviceViewModal) {
            deviceViewModal.addEventListener('click', function(e) {
                if (e.target === deviceViewModal) {
                    closeViewModalHandler();
                }
            });
        }

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

        if (deviceImageInput) { 
            deviceImageInput.addEventListener('change', handleImageChange);
        }
        if (imageInput) {
            imageInput.addEventListener('change', handleImagePreview);
        }
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', removeImagePreview);
        }
        if (roomFilter) {
            roomFilter.addEventListener('change', filterDevices);
        }
        if (buildingSelect) {
            buildingSelect.addEventListener('change', handleBuildingChange);
        }
        if (floorSelect) { 
            floorSelect.addEventListener('change', handleFloorChange);
        }
        if (typeFilter) {
            typeFilter.addEventListener('change', filterDevices);
        }
        if (buildingFilter) {
            buildingFilter.addEventListener('change', filterDevices);
        }
        if (searchDevices) {
            searchDevices.addEventListener('input', debounce(filterDevices, 300));
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

    function handleImagePreview(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreviewElement.src = e.target.result;
                imagePreviewWrapper.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            removeImagePreview();
        }
    }

    function removeImagePreview() {
        imageInput.value = ''; // Hapus file yang sudah dipilih
        imagePreviewElement.src = '#';
        imagePreviewWrapper.classList.add('hidden');
    }

    function bindDeviceCardEvents() {
        // View buttons
        const viewButtons = document.querySelectorAll('.view-device-btn');
        viewButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const data = {
                    deviceName: this.getAttribute('data-device-name'),
                    deviceType: this.getAttribute('data-device-type'),
                    serialNumber: this.getAttribute('data-serial-number'),
                    roomName: this.getAttribute('data-room-name'),
                    category: this.getAttribute('data-category'),
                    merk: this.getAttribute('data-merk'),
                    tahunPo: this.getAttribute('data-tahun-po'),
                    noPo: this.getAttribute('data-no-po'),
                    tahunBast: this.getAttribute('data-tahun-bast'),
                    noBast: this.getAttribute('data-no-bast'),
                    condition: this.getAttribute('data-condition'),
                    notes: this.getAttribute('data-notes'),
                    imagePath: this.getAttribute('data-image-path')
                };

                openViewModal(data);
            });
        });

        // Edit buttons
        const editButtons = document.querySelectorAll('.edit-device-btn');
        editButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const deviceData = { 
                    deviceId: this.getAttribute('data-device-id'),
                    deviceName: this.getAttribute('data-device-name'), 
                    deviceType: this.getAttribute('data-device-type'), 
                    serialNumber: this.getAttribute('data-serial-number'), 
                    roomId: this.getAttribute('data-room-id'),
                    buildingId: this.getAttribute('data-building-id'),
                    category: this.getAttribute('data-category'),
                    merk: this.getAttribute('data-merk'),
                    tahunPo: this.getAttribute('data-tahun-po'),
                    noPo: this.getAttribute('data-no-po'),
                    tahunBast: this.getAttribute('data-tahun-bast'),
                    noBast: this.getAttribute('data-no-bast'),
                    condition: this.getAttribute('data-condition'),
                    notes: this.getAttribute('data-notes')
                };
                openModal('edit', deviceData);
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

    function openViewModal(data) {
        // Populate the modal with the device details
        document.getElementById('view-device-name').textContent = `: ${data.deviceName || 'N/A'}`;
        document.getElementById('view-device-type').textContent = `: ${data.deviceType || 'N/A'}`;
        document.getElementById('view-serial-number').textContent = `: ${data.serialNumber || 'N/A'}`;
        document.getElementById('view-room').textContent = `: ${data.roomName || 'N/A'}`;
        document.getElementById('view-category').textContent = `: ${data.category || 'N/A'}`;
        document.getElementById('view-merk').textContent = `: ${data.merk || 'N/A'}`;
        document.getElementById('view-tahun-po').textContent = `: ${data.tahunPo || 'N/A'}`;
        document.getElementById('view-no-po').textContent = `: ${data.noPo || 'N/A'}`;
        document.getElementById('view-tahun-bast').textContent = `: ${data.tahunBast || 'N/A'}`;
        document.getElementById('view-no-bast').textContent = `: ${data.noBast || 'N/A'}`;
        document.getElementById('view-condition').textContent = `: ${data.condition || 'N/A'}`;
        document.getElementById('view-notes').textContent = `: ${data.notes || 'Tidak ada catatan.'}`;

        // Show image
        const imageElement = document.getElementById('view-device-image');
        if (data.imagePath && data.imagePath !== 'null') {
            imageElement.src = '/storage/' + data.imagePath;
            imageElement.onerror = () => { imageElement.src = 'https://placehold.co/600x400/e2e8f0/adb5bd?text=Image+Not+Found'; };
        } else {
            imageElement.src = 'https://placehold.co/600x400/e2e8f0/adb5bd?text=No+Image';
        }

        // Open the modal
        showModal(deviceViewModal);
    }
    
    // FIX: New handler function for closing view modal
    function closeViewModalHandler() {
        hideModal(deviceViewModal);
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
        removeImagePreview();

        if (isEdit && data) {
            // Set values for existing fields
            if (deviceIdInput) deviceIdInput.value = data.deviceId || '';
            if (deviceNameInput) deviceNameInput.value = data.deviceName || '';
            if (deviceTypeSelect) deviceTypeSelect.value = data.deviceType || '';
            if (serialNumberInput) serialNumberInput.value = data.serialNumber || '';
            if (roomSelect) roomSelect.value = data.roomId || '';

            if (data.roomId) {
                const selectedRoom = roomsData.find(room => room.room_id == data.roomId);
                if (selectedRoom) {
                    const selectedFloor = floorsData.find(floor => floor.floor_id == selectedRoom.floor_id);
                    const existingImagePath = document.querySelector(`.edit-device-btn[data-device-id="${data.deviceId}"]`)?.getAttribute('data-image-path');
                    if (existingImagePath && existingImagePath !== 'null') {
                        imagePreviewElement.src = `/storage/${existingImagePath}`;
                        imagePreviewWrapper.classList.remove('hidden');
                    }
                    if (selectedFloor) {
                        buildingSelect.value = selectedFloor.building_id || '';
                        handleBuildingChange();
                        
                        // Beri jeda agar UI browser sempat me-render dropdown lantai
                        setTimeout(() => {
                            floorSelect.value = selectedFloor.floor_id || '';
                            handleFloorChange();

                            // Beri jeda lagi untuk me-render dropdown ruangan
                            setTimeout(() => {
                                roomSelect.value = data.roomId || '';
                            }, 50);
                        }, 50);
                    }
                }
            }
            
            // Set values for new fields
            if (document.getElementById('category')) document.getElementById('category').value = data.category || '';
            if (document.getElementById('merk')) document.getElementById('merk').value = data.merk || '';
            if (document.getElementById('tahun_po')) document.getElementById('tahun_po').value = data.tahunPo || '';
            if (document.getElementById('no_po')) document.getElementById('no_po').value = data.noPo || '';
            if (document.getElementById('tahun_bast')) document.getElementById('tahun_bast').value = data.tahunBast || '';
            if (document.getElementById('no_bast')) document.getElementById('no_bast').value = data.noBast || '';
            if (document.getElementById('condition')) document.getElementById('condition').value = data.condition || '';
            if (document.getElementById('notes')) document.getElementById('notes').value = data.notes || '';

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
        removeImagePreview();
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
        const currentImageElem = document.getElementById('current-image');
        if (currentImage && currentImage !== 'null' && currentImage !== '') {
            if (currentImageElem) {
                currentImageElem.src = '/storage/' + currentImage;
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

    function handleBuildingChange() {
        const selectedBuildingId = buildingSelect.value;
        
        // Reset dropdown lantai dan ruangan
        floorSelect.innerHTML = '<option value="">Pilih Lantai</option>';
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        floorSelect.disabled = true;
        roomSelect.disabled = true;
        
        if (selectedBuildingId) {
            floorSelect.disabled = false;
            const filteredFloors = floorsData.filter(floor => floor.building_id == selectedBuildingId);
            
            filteredFloors.forEach(floor => {
                const option = document.createElement('option');
                option.value = floor.floor_id;
                option.textContent = floor.floor_name;
                floorSelect.appendChild(option);
            });
        }
    }

    function handleFloorChange() {
        const selectedFloorId = floorSelect.value;

        // Reset dropdown ruangan
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        roomSelect.disabled = true;

        if (selectedFloorId) {
            // Aktifkan dropdown ruangan
            roomSelect.disabled = false;

            // Filter ruangan berdasarkan floor_id yang dipilih (ini logika yang benar)
            const filteredRooms = roomsData.filter(room => room.floor_id == selectedFloorId);
            
            // Isi pilihan ruangan
            filteredRooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.room_id;
                option.textContent = room.room_name;
                roomSelect.appendChild(option);
            });
        }
    }

    function handleImageSubmit(e) {
        e.preventDefault();

        if (!validateImageForm()) return;

        const formData = new FormData(imageForm);
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        formData.append('_token', csrfToken ? csrfToken.content : '');

        setImageLoading(true);

        fetch('/devices/upload-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            setImageLoading(false);

            if (result.success) {
                showNotification(result.message || 'Gambar berhasil diupload!', 'success');
                closeImageModalHandler();
                setTimeout(() => location.reload(), 1000);
            } else {
                if (result.errors) {
                    showImageError(result.errors.image ? result.errors.image[0] : 'Terjadi kesalahan!');
                } else {
                    showImageError(result.message || 'Terjadi kesalahan!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            setImageLoading(false);
            showImageError('Terjadi kesalahan pada server!');
        });
    }

    function handleImageChange(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            showImageError('Ukuran file maksimal 10MB');
            e.target.value = '';
            return;
        }

        if (!file.type.startsWith('image/')) {
            showImageError('File harus berupa gambar');
            e.target.value = '';
            return;
        }

        hideImageError();

        const reader = new FileReader();
        reader.onload = function(e) {
            if (imagePreview) imagePreview.src = e.target.result;
            if (imagePreviewContainer) imagePreviewContainer.classList.remove('hidden');
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

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        fetch(`/devices/${deleteDeviceId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken ? csrfToken.content : ''
            }
        })
        .then(response => response.json())
        .then(result => {
            setDeleteLoading(false);

            if (result.success) {
                showNotification(result.message || 'Device berhasil dihapus!', 'success');
                closeDeleteModal();
                setTimeout(() => location.reload(), 1000);
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

        if (!buildingSelect.value) {
            showError('building_id-error', 'Pilih gedung terlebih dahulu');
            isValid = false;
        }

        if (!document.getElementById('room-select').value) {
            showError('room_id-error', 'Pilih ruangan terlebih dahulu');
            isValid = false;
        }

        const deviceName = document.getElementById('device-name').value.trim();
        if (!deviceName) {
            showError('device_name-error', 'Nama device harus diisi');
            isValid = false;
        }

        if (!document.getElementById('device-type').value) {
            showError('device_type-error', 'Tipe device harus dipilih');
            isValid = false;
        }

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
            const input = errorElement.previousElementSibling;
            if (input) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            }
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });

        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        });
    }

    function updateCharCounts() {
        if (deviceNameInput && deviceCharCount) {
            const deviceCount = deviceNameInput.value.length || 0;
            deviceCharCount.textContent = deviceCount;
            updateCharCountColor(deviceCharCount, deviceCount, 100);
        }

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

    function getDeviceBuildingId(roomId) {
        @foreach($rooms as $room)
            if ('{{ $room->room_id }}' === roomId) {
                return '{{ $room->floor->building_id ?? '' }}';
            }
        @endforeach
        return '';
    }
    function filterDevices() {
        const roomValue = roomFilter ? roomFilter.value : '';
        const typeValue = typeFilter ? typeFilter.value : '';
        const buildingValue = buildingFilter ? buildingFilter.value : '';
        const searchValue = searchDevices ? searchDevices.value.toLowerCase().trim() : '';
        const allRows = document.querySelectorAll('#devices-table tbody tr');

        let visibleCount = 0;

        allRows.forEach(row => {
            const deviceName = row.cells[0].textContent.toLowerCase();
            const deviceType = row.cells[1].textContent.toLowerCase();
            const roomName = row.cells[2].textContent.toLowerCase();
            const serialNumber = row.cells[3].textContent.toLowerCase();
            const deviceRoomId = row.querySelector('.edit-device-btn').getAttribute('data-room-id');

            const deviceBuildingId = getDeviceBuildingId(deviceRoomId);

            let shouldShow = true;

            if (roomValue && deviceRoomId !== roomValue) {
                shouldShow = false;
            }

            if (typeValue && !deviceType.includes(typeValue.toLowerCase())) {
                shouldShow = false;
            }

            if (buildingValue && deviceBuildingId !== buildingValue) {
                shouldShow = false;
            }

            if (searchValue && 
                !deviceName.includes(searchValue) && 
                !roomName.includes(searchValue) &&
                !serialNumber.includes(searchValue)) {
                shouldShow = false;
            }

            if (shouldShow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (filteredCount) {
            filteredCount.textContent = visibleCount;
        }

        const table = document.getElementById('devices-table');
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0 && table) {
             if (!document.getElementById('empty-search-state')) showEmptySearchState(table);
        } else {
            const emptySearch = document.getElementById('empty-search-state');
            if (emptySearch) emptySearch.remove();
        }
    }

    function showEmptySearchState(table) {
        const emptyHtml = `<div id="empty-search-state" class="text-center py-12">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
            <p class="text-gray-600 mb-4">Tidak ditemukan device yang sesuai dengan pencarian Anda.</p>
            <button class="text-telkomsel-red hover:text-telkomsel-dark-red font-medium" onclick="resetFilters()">Reset Filter</button>
        </div>`;
        table.insertAdjacentHTML('afterend', emptyHtml);
    }

    function resetFilters() {
        if (searchDevices) searchDevices.value = '';
        if (roomFilter) roomFilter.value = '';
        if (typeFilter) typeFilter.value = '';
        if (buildingFilter) buildingFilter.value = '';
        filterDevices();
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
        
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 max-w-sm w-full transform transition-all duration-300 translate-x-full';

        const bgColor = type === 'success' ? 'bg-green-500' : 
                        type === 'error' ? 'bg-red-500' : 
                        type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

        const icon = type === 'success' ? 'M5 13l4 4L19 7' :
                     type === 'error' ? 'M6 18L18 6M6 6l12 12' :
                     type === 'warning' ? 'M12 8v4m0 4h.01' :
                     'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

        notification.innerHTML = `<div class="${bgColor} text-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"/></svg>
            <p class="flex-1">${message}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>`;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    function handleKeyboardShortcuts(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            if (!deviceModal || deviceModal.classList.contains('hidden')) {
                openModal('add');
            }
        }

        if (e.key === 'Escape') {
            if (deviceModal && !deviceModal.classList.contains('hidden')) closeDeviceModal();
            if (imageModal && !imageModal.classList.contains('hidden')) closeImageModalHandler();
            if (deleteModal && !deleteModal.classList.contains('hidden')) closeDeleteModal();
            // FIX: Add check for view modal
            if (deviceViewModal && !deviceViewModal.classList.contains('hidden')) closeViewModalHandler();
        }

        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            if (searchDevices) searchDevices.focus();
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
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const result = await response.json();
                        showNotification(result.message || 'Gagal mengekspor data', 'error');
                    } else {
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

    window.resetFilters = resetFilters;
</script>
@endpush