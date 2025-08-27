@extends('layouts.app')

@section('title', 'Manajemen Gedung')
@section('page-title', 'Manajemen Gedung')
@section('page-subtitle', 'Kelola data building untuk sistem pengecekan device')

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
        <div class="flex-1 flex flex-col lg:flex-row lg:items-center lg:space-x-4">
            <!-- Search Bar -->
            <div class="max-w-md mb-4 lg:mb-0">
                <div class="relative">
                    <input 
                        type="text" 
                        id="search-buildings" 
                        placeholder="Cari building..."
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
            <div class="max-w-md mb-4 lg:mb-0">
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
            
            <!-- Filter by Regional -->
            <div class="max-w-md">
                <select 
                    id="filter-regional"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red"
                >
                    <option value="">Semua Regional</option>
                    @foreach($regionals as $regional)
                        <option value="{{ $regional->regional_id }}" data-area-id="{{ $regional->area_id }}" {{ request('regional') == $regional->regional_id ? 'selected' : '' }}>
                            {{ $regional->regional_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <button 
                id="add-building-btn"
                class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Gedung</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Building</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-buildings">{{ $buildings->count() }}</p>
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
                <div class="bg-purple-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Regional Terdaftar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $regionals->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Lantai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $buildings->sum(function($building) { return $building->floors->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Buildings Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Daftar Gedung</h3>
        </div>
        
        <div id="buildings-container" class="p-6">
            @if($buildings->count() > 0)
                <div id="buildings-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($buildings as $building)
                    <div class="building-card bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-200 border border-gray-200 hover:border-telkomsel-red/30" 
                         data-building-id="{{ $building->building_id }}" 
                         data-regional-id="{{ $building->regional_id }}"
                         data-area-id="{{ $building->regional->area_id ?? '' }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-telkomsel-blue rounded-lg p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $building->building_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $building->building_code }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button 
                                    class="edit-building-btn text-gray-600 hover:text-telkomsel-red p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-building-id="{{ $building->building_id }}"
                                    data-building-name="{{ $building->building_name }}"
                                    data-building-code="{{ $building->building_code }}"
                                    data-regional-id="{{ $building->regional_id }}"
                                    data-area-id="{{ $building->regional->area_id ?? '' }}"
                                    title="Edit Building"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    class="delete-building-btn text-gray-600 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-building-id="{{ $building->building_id }}"
                                    data-building-name="{{ $building->building_name }}"
                                    title="Hapus Building"
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
                                <span class="font-semibold text-telkomsel-blue text-sm">
                                    {{ $building->regional->area->area_name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Regional</span>
                                <span class="font-semibold text-gray-900 text-sm">
                                    {{ $building->regional->regional_name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Lantai</span>
                                <span class="font-semibold text-gray-900">{{ $building->floors->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('floors.index') }}?building={{ $building->building_id }}" class="w-full bg-telkomsel-gray text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center block text-sm font-medium">
                                Lihat Lantai
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Building</h3>
                    <p class="text-gray-600 mb-4">Mulai dengan menambahkan building pertama untuk sistem Anda.</p>
                    <button 
                        class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                        onclick="document.getElementById('add-building-btn').click()"
                    >
                        Tambah Gedung Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Building Modal -->
<div id="building-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95" id="modal-content">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-telkomsel font-semibold text-gray-900">Tambah Gedung</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="building-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="building-id" name="building_id">
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
                <label for="regional-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Regional <span class="text-red-500">*</span>
                </label>
                <select 
                    id="regional-select" 
                    name="regional_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    disabled
                >
                    <option value="">Pilih Regional</option>
                </select>
                <div id="regional-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div>
                <label for="building-code" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Building <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="building-code" 
                    name="building_code" 
                    placeholder="Contoh: BLD001, JKT-001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="50"
                >
                <div id="building-code-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div>
                <label for="building-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Building <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="building-name" 
                    name="building_name" 
                    placeholder="Contoh: Gedung Telkomsel Jakarta, Tower A"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-telkomsel-red focus:border-telkomsel-red transition-colors"
                    required
                    maxlength="100"
                >
                <div id="building-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
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
                Apakah Anda yakin ingin menghapus building "<span id="delete-building-name" class="font-semibold"></span>"?
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const buildingModal = document.getElementById('building-modal');
    const deleteModal = document.getElementById('delete-modal');
    const modalContent = document.getElementById('modal-content');
    const buildingForm = document.getElementById('building-form');
    
    // Buttons and inputs
    const addBuildingBtn = document.getElementById('add-building-btn');
    const closeBtns = document.querySelectorAll('#close-modal, #cancel-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const areaSelect = document.getElementById('area-select');
    const regionalSelect = document.getElementById('regional-select');
    const filterArea = document.getElementById('filter-area');
    const filterRegional = document.getElementById('filter-regional');
    const searchInput = document.getElementById('search-buildings');
    
    // Regional data for dynamic filtering
    const regionalsData = @json($regionals);
    
    // Initialize page
    initializeFilters();
    bindEventListeners();
    
    function initializeFilters() {
        // Set initial regional options based on selected area
        updateRegionalOptions();
        
        // Apply initial filters if any
        applyFilters();
    }
    
    function bindEventListeners() {
        // Add building button
        addBuildingBtn.addEventListener('click', () => openAddModal());
        
        // Close modal buttons
        closeBtns.forEach(btn => {
            btn.addEventListener('click', closeModal);
        });
        
        // Cancel delete button
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);
        
        // Area selection change
        areaSelect.addEventListener('change', handleAreaChange);
        filterArea.addEventListener('change', handleFilterAreaChange);
        
        // Regional filter change
        filterRegional.addEventListener('change', applyFilters);
        
        // Search functionality
        searchInput.addEventListener('input', debounce(applyFilters, 300));
        
        // Edit building buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-building-btn')) {
                const btn = e.target.closest('.edit-building-btn');
                openEditModal(btn);
            }
        });
        
        // Delete building buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-building-btn')) {
                const btn = e.target.closest('.delete-building-btn');
                openDeleteModal(btn);
            }
        });
        
        // Form submission
        buildingForm.addEventListener('submit', handleFormSubmit);
        
        // Delete confirmation
        document.getElementById('confirm-delete-btn').addEventListener('click', handleDelete);
        
        // Modal backdrop click
        buildingModal.addEventListener('click', function(e) {
            if (e.target === buildingModal) {
                closeModal();
            }
        });
        
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
        
        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });
    }
    
    function openAddModal() {
        resetForm();
        document.getElementById('modal-title').textContent = 'Tambah Gedung';
        document.getElementById('submit-text').textContent = 'Simpan';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('building-id').value = '';
        showModal();
    }
    
    function openEditModal(btn) {
        resetForm();
        
        const buildingId = btn.getAttribute('data-building-id');
        const buildingName = btn.getAttribute('data-building-name');
        const buildingCode = btn.getAttribute('data-building-code');
        const regionalId = btn.getAttribute('data-regional-id');
        const areaId = btn.getAttribute('data-area-id');
        
        document.getElementById('modal-title').textContent = 'Edit Building';
        document.getElementById('submit-text').textContent = 'Update';
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('building-id').value = buildingId;
        document.getElementById('building-name').value = buildingName;
        document.getElementById('building-code').value = buildingCode;
        
        // Set area and regional
        if (areaId) {
            areaSelect.value = areaId;
            updateRegionalOptions(areaId);
            setTimeout(() => {
                if (regionalId) {
                    regionalSelect.value = regionalId;
                }
            }, 100);
        }
        
        showModal();
    }
    
    function openDeleteModal(btn) {
        const buildingId = btn.getAttribute('data-building-id');
        const buildingName = btn.getAttribute('data-building-name');
        
        document.getElementById('delete-building-name').textContent = buildingName;
        document.getElementById('confirm-delete-btn').setAttribute('data-building-id', buildingId);
        
        deleteModal.classList.remove('hidden');
        setTimeout(() => {
            deleteModal.classList.add('opacity-100');
        }, 10);
    }
    
    function showModal() {
        buildingModal.classList.remove('hidden');
        setTimeout(() => {
            buildingModal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        // Focus first input
        document.getElementById('area-select').focus();
    }
    
    function closeModal() {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        buildingModal.classList.remove('opacity-100');
        
        setTimeout(() => {
            buildingModal.classList.add('hidden');
            resetForm();
        }, 300);
    }
    
    function closeDeleteModal() {
        deleteModal.classList.remove('opacity-100');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
        }, 300);
    }
    
    function resetForm() {
        buildingForm.reset();
        clearErrors();
        regionalSelect.disabled = true;
        regionalSelect.innerHTML = '<option value="">Pilih Regional</option>';
        
        // Reset loading states
        document.getElementById('loading-spinner').classList.add('hidden');
        document.getElementById('submit-text').classList.remove('hidden');
        document.getElementById('submit-btn').disabled = false;
    }
    
    function clearErrors() {
        const errorElements = document.querySelectorAll('.text-red-500');
        errorElements.forEach(el => {
            if (el.id.includes('-error')) {
                el.classList.add('hidden');
                el.textContent = '';
            }
        });
        
        // Remove error styling from inputs
        const inputs = buildingForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'ring-red-500');
            input.classList.add('border-gray-300');
        });
    }
    
    function handleAreaChange() {
        const areaId = areaSelect.value;
        updateRegionalOptions(areaId);
    }
    
    function handleFilterAreaChange() {
        const areaId = filterArea.value;
        updateFilterRegionalOptions(areaId);
        applyFilters();
    }
    
    function updateRegionalOptions(selectedAreaId = null) {
        const areaId = selectedAreaId || areaSelect.value;
        
        regionalSelect.innerHTML = '<option value="">Pilih Regional</option>';
        
        if (areaId) {
            const filteredRegionals = regionalsData.filter(regional => 
                regional.area_id == areaId
            );
            
            filteredRegionals.forEach(regional => {
                const option = document.createElement('option');
                option.value = regional.regional_id;
                option.textContent = regional.regional_name;
                regionalSelect.appendChild(option);
            });
            
            regionalSelect.disabled = false;
        } else {
            regionalSelect.disabled = true;
        }
    }
    
    function updateFilterRegionalOptions(selectedAreaId = null) {
        const areaId = selectedAreaId || filterArea.value;
        const currentRegional = filterRegional.value;
        
        // Save all options first
        const allRegionals = Array.from(filterRegional.options).slice(1); // Skip first "Semua Regional" option
        
        // Clear current options except first
        filterRegional.innerHTML = '<option value="">Semua Regional</option>';
        
        if (areaId) {
            // Add only regionals from selected area
            allRegionals.forEach(option => {
                if (option.getAttribute('data-area-id') == areaId) {
                    filterRegional.appendChild(option.cloneNode(true));
                }
            });
        } else {
            // Add all regionals back
            allRegionals.forEach(option => {
                filterRegional.appendChild(option.cloneNode(true));
            });
        }
        
        // Restore selection if still valid
        if (currentRegional && filterRegional.querySelector(`option[value="${currentRegional}"]`)) {
            filterRegional.value = currentRegional;
        } else {
            filterRegional.value = '';
        }
    }
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedArea = filterArea.value;
        const selectedRegional = filterRegional.value;
        
        const buildingCards = document.querySelectorAll('.building-card');
        let visibleCount = 0;
        
        buildingCards.forEach(card => {
            const buildingName = card.querySelector('h4').textContent.toLowerCase();
            const buildingCode = card.querySelector('p').textContent.toLowerCase();
            const cardAreaId = card.getAttribute('data-area-id');
            const cardRegionalId = card.getAttribute('data-regional-id');
            
            let shouldShow = true;
            
            // Apply search filter
            if (searchTerm && !buildingName.includes(searchTerm) && !buildingCode.includes(searchTerm)) {
                shouldShow = false;
            }
            
            // Apply area filter
            if (selectedArea && cardAreaId !== selectedArea) {
                shouldShow = false;
            }
            
            // Apply regional filter
            if (selectedRegional && cardRegionalId !== selectedRegional) {
                shouldShow = false;
            }
            
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update total count
        document.getElementById('total-buildings').textContent = visibleCount;
        
        // Show/hide empty state
        const emptyState = document.getElementById('empty-state');
        const buildingsGrid = document.getElementById('buildings-grid');
        
        if (visibleCount === 0) {
            if (buildingsGrid) buildingsGrid.style.display = 'none';
            if (!emptyState) {
                createEmptyState();
            } else {
                emptyState.style.display = 'block';
            }
        } else {
            if (buildingsGrid) buildingsGrid.style.display = 'grid';
            if (emptyState) emptyState.style.display = 'none';
        }
    }
    
    function createEmptyState() {
        const container = document.getElementById('buildings-container');
        const emptyStateHtml = `
            <div id="empty-state-filtered" class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Building Ditemukan</h3>
                <p class="text-gray-600 mb-4">Coba ubah kriteria pencarian atau filter Anda.</p>
                <button 
                    class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white px-6 py-2 rounded-lg hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all duration-200"
                    onclick="clearFilters()"
                >
                    Clear Filters
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', emptyStateHtml);
    }
    
    window.clearFilters = function() {
        searchInput.value = '';
        filterArea.value = '';
        filterRegional.value = '';
        updateFilterRegionalOptions();
        applyFilters();
    };
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(buildingForm);
        const buildingId = formData.get('building_id');
        const method = formData.get('_method');
        
        // Show loading state
        showLoadingState();
        clearErrors();
        
        try {
            const url = buildingId ? 
                `/buildings/${buildingId}` : 
                '/buildings';
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Success
                showNotification(result.message || 'Building berhasil disimpan!', 'success');
                closeModal();
                
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Validation errors
                if (result.errors) {
                    showValidationErrors(result.errors);
                } else {
                    showNotification(result.message || 'Terjadi kesalahan!', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan jaringan!', 'error');
        } finally {
            hideLoadingState();
        }
    }
    
    async function handleDelete() {
        const buildingId = document.getElementById('confirm-delete-btn').getAttribute('data-building-id');
        
        // Show loading state
        document.getElementById('delete-spinner').classList.remove('hidden');
        document.getElementById('delete-text').classList.add('hidden');
        document.getElementById('confirm-delete-btn').disabled = true;
        
        try {
            const response = await fetch(`/buildings/${buildingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showNotification(result.message || 'Building berhasil dihapus!', 'success');
                closeDeleteModal();
                
                // Remove card from DOM
                const card = document.querySelector(`[data-building-id="${buildingId}"]`);
                if (card) {
                    card.remove();
                }
                
                // Update counts and reapply filters
                applyFilters();
            } else {
                showNotification(result.message || 'Terjadi kesalahan!', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan jaringan!', 'error');
        } finally {
            // Hide loading state
            document.getElementById('delete-spinner').classList.add('hidden');
            document.getElementById('delete-text').classList.remove('hidden');
            document.getElementById('confirm-delete-btn').disabled = false;
        }
    }
    
    function showLoadingState() {
        document.getElementById('loading-spinner').classList.remove('hidden');
        document.getElementById('submit-text').classList.add('hidden');
        document.getElementById('submit-btn').disabled = true;
    }
    
    function hideLoadingState() {
        document.getElementById('loading-spinner').classList.add('hidden');
        document.getElementById('submit-text').classList.remove('hidden');
        document.getElementById('submit-btn').disabled = false;
    }
    
    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${field.replace('_', '-')}-error`);
            const inputElement = document.getElementById(field.replace('_', '-')) || 
                                document.querySelector(`[name="${field}"]`);
            
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
            
            if (inputElement) {
                inputElement.classList.add('border-red-500', 'ring-red-500');
                inputElement.classList.remove('border-gray-300');
            }
        });
    }
    
    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
            type === 'success' 
                ? 'bg-green-50 border border-green-200 text-green-800' 
                : 'bg-red-50 border border-red-200 text-red-800'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" viewBox="0 0 20 20" fill="currentColor">
                    ${type === 'success' 
                        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                    }
                </svg>
                <p class="font-medium">${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
    
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
});
</script>
@endpush

@endsection