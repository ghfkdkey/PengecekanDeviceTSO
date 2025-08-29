@extends('layouts.app')

@section('title', 'Device Check')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                            Pengecekan Device
                        </h1>
                        <p class="mt-2 text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Pilih lantai, ruangan, dan device untuk melakukan pengecekan
                        </p>
                        <!-- Current User Information -->
                        <div class="mt-3 flex items-center space-x-4">
                            @if(!Auth::user()->isAdmin())
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                                    {{ Auth::user()->role ?? 'Unknown Role' }}: <span class="font-medium text-gray-900">{{ Auth::user()->full_name ?? 'Unknown User' }}</span>
                                </span>
                            </div>
                            @endif
                            @if(!Auth::user()->isAdmin())
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span id="currentLocationInfo" class="text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                                    Regional:  {{Auth::user()->regional->regional_name}}<span class="font-medium">-</span> 
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('device-check-results.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v4a2 2 0 002 2h2m0-6h6a2 2 0 012 2v4a2 2 0 01-2 2h-6m0-6v6"></path>
                            </svg>
                            Lihat Hasil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Selection Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    Pilih Lokasi dan Device
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="floorSelect" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                            Lantai <span class="text-red-500">*</span>
                        </label>
                        <select id="floorSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Pilih Lantai</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->floor_id }}">{{ $floor->floor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="roomSelect" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                            Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select id="roomSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" disabled>
                            <option value="">Pilih Ruangan</option>
                        </select>
                    </div>
                    <div>
                        <label for="categorySelect" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                            Kategori
                        </label>
                        <select id="categorySelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" disabled>
                            <option value="">Semua Kategori</option>
                        </select>
                        <div id="categoryDebug" class="text-xs text-gray-500 mt-1"></div>
                    </div>
                    <div>
                        <label for="deviceSelect" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                            Device <span class="text-red-500">*</span>
                        </label>
                        <select id="deviceSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" disabled>
                            <option value="">Pilih Device</option>
                        </select>
                        <div id="deviceDebug" class="text-xs text-gray-500 mt-1"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <button id="loadChecklistBtn" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;" disabled>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v4a2 2 0 002 2h2m0-6h6a2 2 0 012 2v4a2 2 0 01-2 2h-6m0-6v6"></path>
                        </svg>
                        Muat Checklist
                    </button>
                </div>
            </div>
        </div>

        <!-- Device Information -->
        <div id="deviceInfo" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    Informasi Device
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" style="font-family: 'Poppins', sans-serif;">Nama Device</label>
                        <p id="deviceName" class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" style="font-family: 'Poppins', sans-serif;">Tipe Device</label>
                        <p id="deviceType" class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" style="font-family: 'Poppins', sans-serif;">Serial Number</label>
                        <p id="deviceSerial" class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checklist Section -->
        <div id="checklistSection" class="bg-white rounded-lg shadow-sm border border-gray-200 hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                        Checklist Pengecekan
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span id="overallStatus" class="text-sm text-gray-700" style="font-family: 'Poppins', sans-serif;">Status: 0%</span>
                        <button id="checkAllBtn" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            Check All
                        </button>
                        <button id="uncheckAllBtn" class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            Uncheck All
                        </button>
                    </div>
                </div>
                
                <form id="checklistForm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                        Pengecekan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                        Check
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                        Notes
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="checklistTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Checklist items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="resetBtn" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            Reset
                        </button>
                        <button type="submit" id="saveBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Hasil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                Berhasil!
            </h3>
            <p class="text-sm text-gray-500 mt-2" style="font-family: 'Poppins', sans-serif;">
                Hasil pengecekan device telah berhasil disimpan.
            </p>
            <div class="mt-4 flex justify-center space-x-3">
                <button onclick="closeSuccessModal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedDevice = null;
let checklistItems = [];
let deviceCategories = [];
let currentAreaRegionalInfo = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateCurrentLocationInfo();
});

// Setup event listeners
function setupEventListeners() {
    // Floor selection
    document.getElementById('floorSelect').addEventListener('change', function() {
        const floorId = this.value;
        if (floorId) {
            loadRooms(floorId);
        } else {
            resetRoomSelection();
        }
    });

    // Room selection
    document.getElementById('roomSelect').addEventListener('change', function() {
        const roomId = this.value;
        console.log('Room selected:', roomId);
        if (roomId) {
            loadDevices(roomId);
            loadAreaRegionalInfo(roomId); // New function to load area/regional info
        } else {
            resetDeviceSelection();
            hideAreaRegionalInfo();
        }
    });

    // Category selection
    document.getElementById('categorySelect').addEventListener('change', function() {
        console.log('Category selected:', this.value);
        filterDevicesByCategory();
    });

    // Device selection
    document.getElementById('deviceSelect').addEventListener('change', function() {
        const deviceId = this.value;
        if (deviceId) {
            selectedDevice = getSelectedDeviceData(deviceId);
            showDeviceInfo(selectedDevice);
            document.getElementById('loadChecklistBtn').disabled = false;
        } else {
            hideDeviceInfo();
            document.getElementById('loadChecklistBtn').disabled = true;
        }
    });

    // Load checklist button
    document.getElementById('loadChecklistBtn').addEventListener('click', function() {
        if (selectedDevice) {
            loadChecklist(selectedDevice.device_type);
        }
    });

    // Check all button
    document.getElementById('checkAllBtn').addEventListener('click', function() {
        checkAllItems();
    });

    // Uncheck all button
    document.getElementById('uncheckAllBtn').addEventListener('click', function() {
        uncheckAllItems();
    });

    // Reset button
    document.getElementById('resetBtn').addEventListener('click', function() {
        resetChecklist();
    });

    // Form submission
    document.getElementById('checklistForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveCheckResults();
    });
}

// Update current location info in header
function updateCurrentLocationInfo() {
    // This would typically get current user's area/regional assignment
    // For now, showing placeholder until room is selected
    const locationInfo = document.getElementById('currentLocationInfo');
    @if(Auth::user()->role !== 'admin')
        locationInfo.innerHTML = 'Regional: <span class="font-medium">{{ Auth::user()->regional->regional_name }}</span>';
    @endif

}

// Show area and regional information
function showAreaRegionalInfo(roomInfo) {
    // Update area information
    document.getElementById('selectedAreaName').textContent = roomInfo.area_name || 'Tidak tersedia';
    document.getElementById('selectedAreaCode').textContent = roomInfo.area_code || 'N/A';
    
    // Update regional information
    document.getElementById('selectedRegionalName').textContent = roomInfo.regional_name || 'Tidak tersedia';
    document.getElementById('selectedRegionalCode').textContent = roomInfo.regional_code || 'N/A';
    
    // Update assigned PIC information
    if (roomInfo.assigned_pic) {
        document.getElementById('assignedPicName').textContent = roomInfo.assigned_pic.name || 'Tidak ada PIC';
        document.getElementById('assignedPicRole').textContent = roomInfo.assigned_pic.role || 'N/A';
        
        // Update PIC status badge
        const statusElement = document.getElementById('assignedPicStatus');
        const status = roomInfo.assigned_pic.status || 'unknown';
        let statusClass = 'bg-gray-100 text-gray-800';
        let statusText = 'Status tidak diketahui';
        
        switch(status) {
            case 'active':
                statusClass = 'bg-green-100 text-green-800';
                statusText = 'Aktif';
                break;
            case 'inactive':
                statusClass = 'bg-red-100 text-red-800';
                statusText = 'Tidak Aktif';
                break;
            case 'on_duty':
                statusClass = 'bg-blue-100 text-blue-800';
                statusText = 'Sedang Bertugas';
                break;
            case 'off_duty':
                statusClass = 'bg-yellow-100 text-yellow-800';
                statusText = 'Tidak Bertugas';
                break;
        }
        
        statusElement.innerHTML = `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                ${statusText}
            </span>
        `;
    } else {
        document.getElementById('assignedPicName').textContent = 'Tidak ada PIC terassign';
        document.getElementById('assignedPicRole').textContent = 'N/A';
        document.getElementById('assignedPicStatus').innerHTML = `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                Tidak ada assignment
            </span>
        `;
    }
    
    // Show the area/regional info card
    document.getElementById('areaRegionalInfo').classList.remove('hidden');
    
    // Update header location info
    const locationInfo = document.getElementById('currentLocationInfo');
    locationInfo.innerHTML = `Area: <span class="font-medium">${roomInfo.area_name || 'N/A'}</span> | Regional: <span class="font-medium">${roomInfo.regional_name || 'N/A'}</span>`;
}

// Hide area and regional information
function hideAreaRegionalInfo() {
    document.getElementById('areaRegionalInfo').classList.add('hidden');
    updateCurrentLocationInfo();
}

// Load rooms by floor
async function loadRooms(floorId) {
    try {
        console.log('Loading rooms for floor:', floorId);
        const response = await fetch(`/api/rooms/${floorId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const rooms = await response.json();
        console.log('Rooms loaded:', rooms);
        
        const roomSelect = document.getElementById('roomSelect');
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        if (rooms && rooms.length > 0) {
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.room_id;
                option.textContent = room.room_name;
                roomSelect.appendChild(option);
            });
            roomSelect.disabled = false;
        } else {
            roomSelect.disabled = true;
            showNotification('Tidak ada ruangan di lantai ini', 'info');
        }
        
        resetDeviceSelection();
    } catch (error) {
        console.error('Error loading rooms:', error);
        showNotification('Error loading rooms: ' + error.message, 'error');
        document.getElementById('roomSelect').disabled = true;
    }
}

// Load devices by room
async function loadDevices(roomId) {
    try {
        console.log('Loading devices for room:', roomId);
        const response = await fetch(`/api/devices/${roomId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const devices = await response.json();
        console.log('Devices loaded:', devices);
        
        // Store devices for filtering
        window.availableDevices = devices;
        
        if (devices && devices.length > 0) {
            // Get unique categories
            deviceCategories = [...new Set(devices.map(device => device.device_type))];
            console.log('Available categories:', deviceCategories);
            
            const categorySelect = document.getElementById('categorySelect');
            categorySelect.innerHTML = '<option value="">Semua Kategori</option>';
            
            deviceCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
            
            // Enable category select
            categorySelect.disabled = false;
            console.log('Category select enabled:', categorySelect.disabled);
            
            // Show debug info
            document.getElementById('categoryDebug').textContent = `Available categories: ${deviceCategories.join(', ')}`;
            
            // Populate device select with all devices initially
            populateDeviceSelect(devices);
            
            // Enable device select
            document.getElementById('deviceSelect').disabled = false;
        } else {
            showNotification('Tidak ada device di ruangan ini', 'info');
            document.getElementById('categorySelect').disabled = true;
            document.getElementById('deviceSelect').disabled = true;
        }
        
        // Do not reset here; we just populated the selects
    } catch (error) {
        console.error('Error loading devices:', error);
        showNotification('Error loading devices: ' + error.message, 'error');
        document.getElementById('categorySelect').disabled = true;
        document.getElementById('deviceSelect').disabled = true;
    }
}

// Populate device select
function populateDeviceSelect(devices) {
    const deviceSelect = document.getElementById('deviceSelect');
    deviceSelect.innerHTML = '<option value="">Pilih Device</option>';
    
    if (devices && devices.length > 0) {
        devices.forEach(device => {
            const option = document.createElement('option');
            option.value = device.device_id;
            option.textContent = `${device.device_name} (${device.device_type})`;
            option.dataset.device = JSON.stringify(device);
            deviceSelect.appendChild(option);
        });
        deviceSelect.disabled = false;
        console.log('Device select populated with', devices.length, 'devices');
        document.getElementById('deviceDebug').textContent = `Available devices: ${devices.length}`;
    } else {
        deviceSelect.disabled = true;
        console.log('No devices to populate');
        document.getElementById('deviceDebug').textContent = 'No devices available';
    }
}

// Filter devices by category
function filterDevicesByCategory() {
    const selectedCategory = document.getElementById('categorySelect').value;
    const devices = window.availableDevices || [];
    
    let filteredDevices = devices;
    if (selectedCategory && selectedCategory !== '') {
        filteredDevices = devices.filter(device => device.device_type === selectedCategory);
    }
    
    console.log('Filtering devices by category:', selectedCategory, 'Found:', filteredDevices.length, 'devices');
    populateDeviceSelect(filteredDevices);
    // After changing category, clear current device selection but keep device dropdown enabled
    document.getElementById('deviceSelect').value = '';
    selectedDevice = null;
    document.getElementById('loadChecklistBtn').disabled = true;
    hideDeviceInfo();
    document.getElementById('checklistSection').classList.add('hidden');
}

// Get selected device data
function getSelectedDeviceData(deviceId) {
    const deviceSelect = document.getElementById('deviceSelect');
    const selectedOption = deviceSelect.querySelector(`option[value="${deviceId}"]`);
    
    if (selectedOption && selectedOption.dataset.device) {
        return JSON.parse(selectedOption.dataset.device);
    }
    
    return null;
}

// Show device information
function showDeviceInfo(device) {
    document.getElementById('deviceName').textContent = device.device_name;
    document.getElementById('deviceType').textContent = device.device_type;
    document.getElementById('deviceSerial').textContent = device.serial_number || 'N/A';
    document.getElementById('deviceInfo').classList.remove('hidden');
}

// Hide device information
function hideDeviceInfo() {
    document.getElementById('deviceInfo').classList.add('hidden');
}

// Load checklist by device type
async function loadChecklist(deviceType) {
    try {
        console.log('Loading checklist for device type:', deviceType);
        const response = await fetch(`/api/checklist/${deviceType}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        checklistItems = await response.json();
        console.log('Checklist items loaded:', checklistItems);
        
        renderChecklist();
        document.getElementById('checklistSection').classList.remove('hidden');
    } catch (error) {
        console.error('Error loading checklist:', error);
        showNotification('Error loading checklist: ' + error.message, 'error');
    }
}

// Render checklist table
function renderChecklist() {
    const tbody = document.getElementById('checklistTableBody');
    tbody.innerHTML = '';
    
    if (checklistItems.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500" style="font-family: 'Poppins', sans-serif;">
                    Tidak ada checklist untuk device ini
                </td>
            </tr>
        `;
        return;
    }
    
    checklistItems.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        row.innerHTML = `
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    ${item.question}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" 
                       id="check_${item.checklist_id}" 
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                       data-checklist-id="${item.checklist_id}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <select id="status_${item.checklist_id}" 
                        class="text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                        style="font-family: 'Poppins', sans-serif;">
                    <option value="pending">Pending</option>
                    <option value="passed">Passed</option>
                    <option value="failed">Failed</option>
                </select>
            </td>
            <td class="px-6 py-4">
                <input type="text" 
                       id="notes_${item.checklist_id}" 
                       class="text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 w-full" 
                       placeholder="Catatan (opsional)"
                       style="font-family: 'Poppins', sans-serif;">
            </td>
        `;
        
        tbody.appendChild(row);
        
        // Add event listener for checkbox
        const checkbox = document.getElementById(`check_${item.checklist_id}`);
        checkbox.addEventListener('change', function() {
            const statusSelect = document.getElementById(`status_${item.checklist_id}`);
            if (this.checked) {
                statusSelect.value = 'passed';
            } else {
                statusSelect.value = 'pending';
            }
            updateOverallStatus();
        });

        // Add event listener for status select change
        const statusSelect = document.getElementById(`status_${item.checklist_id}`);
        statusSelect.addEventListener('change', function() {
            updateOverallStatus();
        });
    });

    // Update initial overall status after rendering
    updateOverallStatus();
}

// Check all items
function checkAllItems() {
    checklistItems.forEach(item => {
        const checkbox = document.getElementById(`check_${item.checklist_id}`);
        const statusSelect = document.getElementById(`status_${item.checklist_id}`);
        
        checkbox.checked = true;
        statusSelect.value = 'passed';
    });
    updateOverallStatus();
}

// Uncheck all items
function uncheckAllItems() {
    checklistItems.forEach(item => {
        const checkbox = document.getElementById(`check_${item.checklist_id}`);
        const statusSelect = document.getElementById(`status_${item.checklist_id}`);
        
        checkbox.checked = false;
        statusSelect.value = 'pending';
    });
    updateOverallStatus();
}

// Reset checklist
function resetChecklist() {
    uncheckAllItems();
}

// Update overall status percentage
function updateOverallStatus() {
    const totalItems = checklistItems.length;
    if (totalItems === 0) {
        const statusEl = document.getElementById('overallStatus');
        if (statusEl) statusEl.textContent = 'Status: 0%';
        return;
    }
    let passedCount = 0;
    checklistItems.forEach(item => {
        const statusSelect = document.getElementById(`status_${item.checklist_id}`);
        if (statusSelect && statusSelect.value === 'passed') {
            passedCount += 1;
        }
    });
    const percent = Math.round((passedCount / totalItems) * 100);
    const statusEl = document.getElementById('overallStatus');
    if (statusEl) {
        statusEl.textContent = `Status: ${percent}%`;
    }
}

// Save check results
async function saveCheckResults() {
    if (!selectedDevice) {
        showNotification('Pilih device terlebih dahulu', 'error');
        return;
    }
    
    const results = [];
    checklistItems.forEach(item => {
        const status = document.getElementById(`status_${item.checklist_id}`).value;
        const notes = document.getElementById(`notes_${item.checklist_id}`).value;
        
        results.push({
            checklist_id: item.checklist_id,
            status: status,
            notes: notes
        });
    });
    
    // Include area/regional information in the saved data
    const saveData = {
        device_id: selectedDevice.device_id,
        checklist_results: results,
        area_regional_info: currentAreaRegionalInfo,
        operational_pic: {
            name: '{{ Auth::user()->name ?? "Unknown User" }}',
            id: '{{ Auth::user()->id ?? null }}',
            checked_at: new Date().toISOString()
        }
    };
    
    try {
        const response = await fetch('/api/device-check-results/multiple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(saveData)
        });
        
        if (response.ok) {
            showSuccessModal();
        } else {
            throw new Error('Failed to save results');
        }
    } catch (error) {
        console.error('Error saving results:', error);
        showNotification('Error saving results', 'error');
    }
}

// Reset selections
function resetRoomSelection() {
    document.getElementById('roomSelect').innerHTML = '<option value="">Pilih Ruangan</option>';
    document.getElementById('roomSelect').disabled = true;
    
    // Reset category and device selections
    document.getElementById('categorySelect').innerHTML = '<option value="">Semua Kategori</option>';
    document.getElementById('categorySelect').disabled = true;
    document.getElementById('categoryDebug').textContent = '';
    document.getElementById('deviceSelect').innerHTML = '<option value="">Pilih Device</option>';
    document.getElementById('deviceSelect').disabled = true;
    document.getElementById('deviceDebug').textContent = '';
    document.getElementById('loadChecklistBtn').disabled = true;
    hideDeviceInfo();
    document.getElementById('checklistSection').classList.add('hidden');
    hideAreaRegionalInfo();
    selectedDevice = null;
    window.availableDevices = [];
    deviceCategories = [];
    currentAreaRegionalInfo = null;
}

function resetDeviceSelection() {
    // Don't reset category select if devices are loaded
    if (!window.availableDevices || window.availableDevices.length === 0) {
        document.getElementById('categorySelect').innerHTML = '<option value="">Semua Kategori</option>';
        document.getElementById('categorySelect').disabled = true;
    }
    
    document.getElementById('deviceSelect').innerHTML = '<option value="">Pilih Device</option>';
    document.getElementById('deviceSelect').disabled = true;
    document.getElementById('loadChecklistBtn').disabled = true;
    hideDeviceInfo();
    document.getElementById('checklistSection').classList.add('hidden');
    selectedDevice = null;
}

// Show success modal
function showSuccessModal() {
    document.getElementById('successModal').classList.remove('hidden');
}

// Close success modal
function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    // Reset form
    resetChecklist();
    document.getElementById('checklistSection').classList.add('hidden');
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}
</script>
@endsection