@extends('layouts.app')

@section('title', 'Checklist Items Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                            Checklist Items
                        </h1>
                        <p class="mt-2 text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Manage checklist questions for device inspections
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('checklist-items.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Checklist Item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800" style="font-family: 'Poppins', sans-serif;">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v4a2 2 0 002 2h2m0-6h6a2 2 0 012 2v4a2 2 0 01-2 2h-6m0-6v6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Total Items</p>
                        <p class="text-2xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">{{ $checklistItems->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Device Types</p>
                        <p class="text-2xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">{{ $checklistItems->unique('device_type')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Check Results</p>
                        <p class="text-2xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">{{ $checklistItems->sum(fn($item) => $item->checkResults->count()) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('checklist-items.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="sm:w-64">
                            <label for="device_type" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Filter by Device Type</label>
                            <select name="device_type" id="device_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" style="font-family: 'Poppins', sans-serif;">
                                <option value="">All Device Types</option>
                                @foreach($checklistItems->unique('device_type')->pluck('device_type') as $deviceType)
                                    <option value="{{ $deviceType }}" {{ request('device_type') == $deviceType ? 'selected' : '' }}>
                                        {{ $deviceType }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                                Filter
                            </button>
                        </div>
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Cari Items</label>
                            <div class="relative">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by question or device type..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" style="font-family: 'Poppins', sans-serif;">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Checklist Items Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    Checklist Items
                </h2>
            </div>
            
            @if($checklistItems->isEmpty())
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v4a2 2 0 002 2h2m0-6h6a2 2 0 012 2v4a2 2 0 01-2 2h-6m0-6v6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif;">No checklist items</h3>
                    <p class="mt-1 text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">Get started by creating a new checklist item.</p>
                    <div class="mt-6">
                        <a href="{{ route('checklist-items.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Checklist Item
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Device Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Check Results</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" style="font-family: 'Poppins', sans-serif;">
                            @php
                                $filteredItems = $checklistItems;
                                
                                if (request('search')) {
                                    $filteredItems = $filteredItems->filter(function($item) {
                                        return stripos($item->question, request('search')) !== false ||
                                               stripos($item->device_type, request('search')) !== false;
                                    });
                                }
                                
                                if (request('device_type')) {
                                    $filteredItems = $filteredItems->filter(function($item) {
                                        return $item->device_type === request('device_type');
                                    });
                                }
                            @endphp
                            
                            @forelse($filteredItems as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->device_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-900 truncate max-w-md" title="{{ $item->question }}">
                                            {{ $item->question }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->checkResults->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $item->checkResults->count() }} results
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                        <button 
                                            class="text-blue-600 hover:text-blue-900"
                                            onclick="viewItem({{ $item->checklist_id }})"
                                            title="View Details"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            class="text-indigo-600 hover:text-indigo-900"
                                            onclick="editItem({{ $item->checklist_id }})"
                                            title="Edit"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="deleteItem({{ $item->checklist_id }}, '{{ $item->question }}')"
                                            title="Delete"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No checklist items found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Checklist Item Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Device Type</label>
                    <p id="viewDeviceType" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Question</label>
                    <p id="viewQuestion" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Check Results</label>
                    <p id="viewResults" class="mt-1 text-sm text-gray-900"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Checklist Item</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editForm" onsubmit="updateItem(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editItemId">
                <div class="space-y-4">
                    <div>
                        <label for="editDeviceType" class="block text-sm font-medium text-gray-700">Device Type</label>
                        <select id="editDeviceType" name="device_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500" required>
                            <option value="">Select Device Type</option>
                            <option value="Computer">Computer</option>
                            <option value="Smartboard">Smartboard</option>
                            <option value="SmartTV">SmartTV</option>
                            <option value="AC">AC</option>
                        </select>
                    </div>
                    <div>
                        <label for="editQuestion" class="block text-sm font-medium text-gray-700">Question</label>
                        <textarea id="editQuestion" name="question" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Delete Checklist Item</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="text-sm text-gray-700 mb-4" style="font-family: 'Poppins', sans-serif;">
                <p>Are you sure you want to delete this checklist item?</p>
                <p class="font-medium" id="delete-item-question"></p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                    <svg id="delete-spinner" class="hidden animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v16a8 8 0 01-8-8z"></path>
                    </svg>
                    <span id="delete-text">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function viewItem(id) {
    fetch(`/api/checklist-items/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewDeviceType').textContent = data.device_type;
            document.getElementById('viewQuestion').textContent = data.question;
            document.getElementById('viewResults').textContent = `${data.check_results_count || 0} check results`;
            document.getElementById('viewModal').classList.remove('hidden');
        });
}

function editItem(id) {
    fetch(`/api/checklist-items/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editItemId').value = id;
            document.getElementById('editDeviceType').value = data.device_type;
            document.getElementById('editQuestion').value = data.question;
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function updateItem(e) {
    e.preventDefault();
    const id = document.getElementById('editItemId').value;
    const formData = new FormData(e.target);

    fetch(`/api/checklist-items/${id}`, { // Perbaiki URL
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            device_type: formData.get('device_type'),
            question: formData.get('question')
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.errors) {
            throw new Error(Object.values(data.errors).join('\n'));
        }
        closeEditModal();
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Failed to update checklist item');
    });
}

// Fixed JavaScript untuk delete checklist item
function deleteItem(id, question) {
    // Set data untuk modal konfirmasi delete
    document.getElementById('delete-item-question').textContent = question;
    document.getElementById('confirm-delete-btn').setAttribute('data-item-id', id);
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Fixed confirm delete handler dengan error handling yang lebih baik
document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
    const id = this.getAttribute('data-item-id');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');

    // Validasi ID
    if (!id) {
        alert('ID checklist item tidak valid');
        return;
    }

    // Show loading state
    this.disabled = true;
    deleteText.classList.add('hidden');
    deleteSpinner.classList.remove('hidden');

    try {
        // PERBAIKAN: Gunakan route yang benar sesuai dengan web.php
        const response = await fetch(`/checklist-items/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        // PERBAIKAN: Cek status response terlebih dahulu
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // PERBAIKAN: Tutup modal dulu sebelum reload
            closeDeleteModal();
            
            // Optional: Tampilkan notifikasi sukses sebelum reload
            showNotification('Checklist item berhasil dihapus', 'success');
            
            // Delay sedikit untuk menampilkan notifikasi
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || 'Gagal menghapus checklist item');
        }
    } catch (error) {
        console.error('Delete error:', error);
        
        // Reset button state
        this.disabled = false;
        deleteText.classList.remove('hidden');
        deleteSpinner.classList.add('hidden');
        
        // Tampilkan error yang lebih informatif
        let errorMessage = 'Terjadi kesalahan saat menghapus checklist item';
        if (error.message && error.message !== 'Failed to fetch') {
            errorMessage = error.message;
        }
        
        showNotification(errorMessage, 'error');
    }
});

// Close delete modal function
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    
    // Reset button state
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');
    
    confirmBtn.disabled = false;
    deleteText.classList.remove('hidden');
    deleteSpinner.classList.add('hidden');
    confirmBtn.removeAttribute('data-item-id');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'viewModal') {
        closeViewModal();
    }
    if (e.target.id === 'editModal') {
        closeEditModal();
    }
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});
</script>