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

    <!-- Error Message -->
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800" style="font-family: 'Poppins', sans-serif;">
                            {{ session('error') }}
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
                                        <!-- View Button -->
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
                                        
                                        <!-- Edit Button -->
                                        <button 
                                            class="text-indigo-600 hover:text-indigo-900"
                                            onclick="editItem({{ $item->checklist_id }})"
                                            title="Edit"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Delete Button - Only show if no check results -->
                                        @if($item->checkResults->count() == 0)
                                            <button 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="deleteItemUrl('{{ route('checklist-items.destroy', $item->checklist_id) }}', '{{ addslashes($item->question) }}')"
                                                title="Delete"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @else
                                            <!-- Show disabled delete icon for items with results -->
                                            <span 
                                                class="text-gray-400 cursor-not-allowed"
                                                title="Tidak dapat dihapus - sudah ada hasil pengecekan"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </span>
                                        @endif
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
                            <option value="Digital_Signage">Digital Signage</option>
                            <option value="VideoWall">VideoWall</option>
                            <option value="Mini_PC">Mini PC</option>
                            <option value="Polycom">Polycom</option>
                            <option value="TV_Samsung_85">TV Samsung 85</option>
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
                <h3 class="text-lg font-semibold text-gray-900">Hapus Checklist Item</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="text-sm text-gray-700 mb-4" style="font-family: 'Poppins', sans-serif;">
                <div class="flex items-center mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.854-.833-2.624 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="text-yellow-800 text-sm">Perhatian! Tindakan ini tidak dapat dibatalkan.</span>
                </div>
                <p class="mb-2">Apakah Anda yakin ingin menghapus checklist item berikut?</p>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="font-medium text-gray-900" id="delete-item-question"></p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Batal
                </button>
                <button id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg id="delete-spinner" class="hidden animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v16a8 8 0 01-8-8z"></path>
                    </svg>
                    <span id="delete-text">Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Notification helper
function showNotification(message, type = 'success') {
    const bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const iconPath = type === 'success'
        ? 'M5 13l4 4L19 7'
        : 'M6 18L18 6M6 6l12 12';
    const container = document.createElement('div');
    container.className = `fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
    container.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}" />
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(container);
    setTimeout(() => {
        container.style.opacity = '0';
        container.style.transform = 'translateY(-10px)';
        setTimeout(() => container.remove(), 300);
    }, 3000);
}

// View item function
function viewItem(id) {
    fetch(`/api/checklist-items/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('viewDeviceType').textContent = data.device_type;
            document.getElementById('viewQuestion').textContent = data.question;
            document.getElementById('viewResults').textContent = `${data.check_results_count || 0} hasil pengecekan`;
            document.getElementById('viewModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal memuat detail checklist item', 'error');
        });
}

// Edit item function
function editItem(id) {
    fetch(`/api/checklist-items/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('editItemId').value = id;
            document.getElementById('editDeviceType').value = data.device_type;
            document.getElementById('editQuestion').value = data.question;
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal memuat data checklist item', 'error');
        });
}

// Delete item function
// Kode ini menggantikan fungsi deleteItemUrl dan event listener confirm-delete-btn yang ada sebelumnya
function deleteItemUrl(url, question) {
    document.getElementById('delete-item-question').textContent = question;
    document.getElementById('confirm-delete-btn').setAttribute('data-delete-url', url);
    document.getElementById('deleteModal').classList.remove('hidden');
}


// Close modal functions
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

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

// Update item function
function updateItem(e) {
    e.preventDefault();
    const id = document.getElementById('editItemId').value;
    const formData = new FormData(e.target);

    fetch(`/api/checklist-items/${id}`, {
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
        closeEditModal();
        showNotification('Checklist item berhasil diperbarui', 'success');
        setTimeout(() => window.location.reload(), 1000);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memperbarui checklist item', 'error');
    });
}

// Event listener yang diperbarui untuk menangani AJAX DELETE
document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
    const url = this.getAttribute('data-delete-url');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!url) {
        showNotification('URL hapus tidak valid', 'error');
        return;
    }

    // Tampilkan loading state
    this.disabled = true;
    deleteText.classList.add('hidden');
    deleteSpinner.classList.remove('hidden');

    try {
        const response = await fetch(url, {
            method: 'DELETE', // Menggunakan method DELETE
            headers: {
                'X-CSRF-TOKEN': csrfToken, // Menyertakan token CSRF
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            closeDeleteModal();
            showNotification(result.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(result.message || 'Gagal menghapus checklist item');
        }
    } catch (error) {
        console.error('Delete error:', error);
        
        // Reset button state
        this.disabled = false;
        deleteText.classList.remove('hidden');
        deleteSpinner.classList.add('hidden');
        
        showNotification(error.message, 'error');
    }
});
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

// Close modals with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeEditModal();
        closeDeleteModal();
    }
});
</script>