@extends('layouts.app')

@section('title', 'Device Check Results')

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
                            Memantau dan mengelola hasil pengecekan device
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('device-check.page') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Check
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Passed</p>
                        <p class="text-2xl font-bold text-green-600" id="passedCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Failed</p>
                        <p class="text-2xl font-bold text-red-600" id="failedCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Pending</p>
                        <p class="text-2xl font-bold text-amber-600" id="pendingCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2zm8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Total Checks</p>
                        <p class="text-2xl font-bold text-blue-600" id="totalCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Search</label>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search device, user..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Status</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">All Status</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div>
                        <label for="deviceTypeFilter" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Device Type</label>
                        <select id="deviceTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">All Types</option>
                        </select>
                    </div>
                    <div>
                        <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Date Range</label>
                        <input type="date" id="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <button onclick="clearFilters()" class="text-sm text-gray-600 hover:text-gray-800" style="font-family: 'Poppins', sans-serif;">
                        Clear Filters
                    </button>
                    <button onclick="exportData()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    Device Check Results
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="resultsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Device</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Checked By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="resultsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700" style="font-family: 'Poppins', sans-serif;">
                        Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalResults">0</span> results
                    </div>
                    <div class="flex space-x-2" id="pagination">
                        <!-- Pagination will be generated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;" id="modalTitle">
                    Add New Check Result
                </h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="checkResultForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="device_id" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Device</label>
                        <select id="device_id" name="device_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Select Device</option>
                        </select>
                    </div>
                    <div>
                        <label for="checklist_id" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Checklist Item</label>
                        <select id="checklist_id" name="checklist_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Select Checklist Item</option>
                        </select>
                    </div>
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Checked By</label>
                        <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Select User</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Status</label>
                        <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Select Status</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" placeholder="Additional notes..."></textarea>
                    </div>
                    <div>
                        <label for="checked_at" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Check Date</label>
                        <input type="datetime-local" id="checked_at" name="checked_at" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    Check Result Details
                </h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="detailContent" class="space-y-4">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
let checkResults = [];
let filteredResults = [];
let currentPage = 1;
const itemsPerPage = 10;
let editingId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadCheckResults();
    loadDropdownData();
    setupEventListeners();
});

// Load check results from API (latest session per device)
async function loadCheckResults() {
    try {
        const response = await fetch('/api/device-check-sessions');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        checkResults = await response.json();
        filteredResults = [...checkResults];
        updateStats();
        renderTable();
        updatePagination();
    } catch (error) {
        console.error('Error loading check results:', error);
        showNotification('Error loading data: ' + error.message, 'error');
    }
}

// Load dropdown data
async function loadDropdownData() {
    try {
        // Load devices
        const devicesResponse = await fetch('/api/devices');
        const devices = await devicesResponse.json();
        populateSelect('device_id', devices, 'device_id', 'device_name');

        // Load checklist items
        const checklistResponse = await fetch('/api/checklist-items');
        const checklistItems = await checklistResponse.json();
        populateSelect('checklist_id', checklistItems, 'checklist_id', 'question');

        // Load users
        const usersResponse = await fetch('/api/users');
        const users = await usersResponse.json();
        populateSelect('user_id', users, 'user_id', 'full_name');

        // Populate device type filter
        const deviceTypes = [...new Set(devices.map(d => d.device_type))];
        populateSelect('deviceTypeFilter', deviceTypes.map(type => ({value: type, label: type})), 'value', 'label');
    } catch (error) {
        console.error('Error loading dropdown data:', error);
    }
}

// Populate select element
function populateSelect(selectId, data, valueField, textField) {
    const select = document.getElementById(selectId);
    const currentValue = select.value;
    
    // Keep existing options if it's a filter
    if (!selectId.includes('Filter')) {
        select.innerHTML = '<option value="">Select...</option>';
    }
    
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item[valueField];
        option.textContent = item[textField];
        select.appendChild(option);
    });
    
    select.value = currentValue;
}

// Setup event listeners
function setupEventListeners() {
    // Search input
    document.getElementById('searchInput').addEventListener('input', debounce(filterResults, 300));
    
    // Filter selects
    document.getElementById('statusFilter').addEventListener('change', filterResults);
    document.getElementById('deviceTypeFilter').addEventListener('change', filterResults);
    document.getElementById('dateFilter').addEventListener('change', filterResults);
    
    // Form submission
    document.getElementById('checkResultForm').addEventListener('submit', handleFormSubmit);
}

// Filter results
function filterResults() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const deviceTypeFilter = document.getElementById('deviceTypeFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    filteredResults = checkResults.filter(result => {
        const matchesSearch = !searchTerm || 
            (result.device_name && result.device_name.toLowerCase().includes(searchTerm)) ||
            (result.checked_by && result.checked_by.toLowerCase().includes(searchTerm)) ||
            (result.room_name && result.room_name.toLowerCase().includes(searchTerm)) ||
            (result.floor_name && result.floor_name.toLowerCase().includes(searchTerm));

        const passed = result.passed_count || 0;
        const failed = result.failed_count || 0;
        const pending = result.pending_count || 0;
        const sessionStatus = failed > 0 ? 'failed' : (pending > 0 ? 'pending' : 'passed');

        const matchesStatus = !statusFilter || sessionStatus === statusFilter;
        const matchesDeviceType = !deviceTypeFilter || result.device_type === deviceTypeFilter;
        
        const matchesDate = !dateFilter || 
            (result.checked_at && result.checked_at.startsWith(dateFilter));
        
        return matchesSearch && matchesStatus && matchesDeviceType && matchesDate;
    });
    
    currentPage = 1;
    renderTable();
    updatePagination();
}

// Update statistics
function updateStats() {
    let passed = 0;
    let failed = 0;
    let pending = 0;

    checkResults.forEach(r => {
        const f = r.failed_count || 0;
        const p = r.pending_count || 0;
        if (f > 0) {
            failed += 1;
        } else if (p > 0) {
            pending += 1;
        } else {
            passed += 1;
        }
    });

    document.getElementById('passedCount').textContent = passed;
    document.getElementById('failedCount').textContent = failed;
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('totalCount').textContent = checkResults.length;
}

// Render table
function renderTable() {
    const tbody = document.getElementById('resultsTableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageResults = filteredResults.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (pageResults.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500" style="font-family: 'Poppins', sans-serif;">
                    No results found
                </td>
            </tr>
        `;
        return;
    }
    
    pageResults.forEach(result => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        const passed = result.passed_count || 0;
        const failed = result.failed_count || 0;
        const pending = result.pending_count || 0;
        const sessionStatus = failed > 0 ? 'failed' : (pending > 0 ? 'pending' : 'passed');
        const statusBadge = getStatusBadge(sessionStatus);
        const formattedDate = formatDate(result.checked_at);
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    ${result.device_name || 'N/A'}
                </div>
                <div class="text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">
                    ${result.device_type || 'N/A'} • ${result.serial_number || 'N/A'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    ${result.room_name || 'N/A'}
                </div>
                <div class="text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">
                    ${result.floor_name || 'N/A'}
                </div>
            </td>
            
            <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    ${result.checked_by || 'N/A'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">
                ${formattedDate}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button type="button" onclick="viewDetailSession(${result.device_id}, '${result.checked_at}')" class="text-blue-600 hover:text-blue-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Update showing info
    const showingFrom = filteredResults.length === 0 ? 0 : startIndex + 1;
    const showingTo = Math.min(endIndex, filteredResults.length);
    document.getElementById('showingFrom').textContent = showingFrom;
    document.getElementById('showingTo').textContent = showingTo;
    document.getElementById('totalResults').textContent = filteredResults.length;
}

// Get status badge HTML
function getStatusBadge(status) {
    const badges = {
        'passed': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Passed</span>',
        'failed': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>',
        'pending': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>'
    };
    return badges[status] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>';
}

// Format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
}

// Update pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredResults.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = `px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 ${currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''}`;
    prevButton.innerHTML = 'Previous';
    prevButton.disabled = currentPage === 1;
    prevButton.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
            updatePagination();
        }
    };
    pagination.appendChild(prevButton);
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            const pageButton = document.createElement('button');
            pageButton.className = `px-3 py-2 text-sm font-medium ${i === currentPage ? 'text-red-600 bg-red-50 border-red-500' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'} border-t border-b`;
            pageButton.textContent = i;
            pageButton.onclick = () => {
                currentPage = i;
                renderTable();
                updatePagination();
            };
            pagination.appendChild(pageButton);
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border-t border-b border-gray-300';
            ellipsis.textContent = '...';
            pagination.appendChild(ellipsis);
        }
    }
    
    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = `px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}`;
    nextButton.innerHTML = 'Next';
    nextButton.disabled = currentPage === totalPages;
    nextButton.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
            updatePagination();
        }
    };
    pagination.appendChild(nextButton);
}

function editResult(id) {
    const result = checkResults.find(r => r.result_id === id);
    if (!result) return;
    
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Edit Check Result';
    
    // Populate form
    document.getElementById('device_id').value = result.device_id;
    document.getElementById('checklist_id').value = result.checklist_id;
    document.getElementById('user_id').value = result.user_id;
    document.getElementById('status').value = result.status;
    document.getElementById('notes').value = result.notes || '';
    document.getElementById('checked_at').value = result.checked_at ? new Date(result.checked_at).toISOString().slice(0, 16) : '';
    
    document.getElementById('addModal').classList.remove('hidden');
}

async function viewDetailSession(deviceId, checkedAt) {
    try {
        const url = checkedAt 
            ? `/api/device-check-session-detail?device_id=${deviceId}&checked_at=${encodeURIComponent(checkedAt)}` 
            : `/api/device-check-session-detail?device_id=${deviceId}`;
        const response = await fetch(url);
        const items = await response.json();
        if (!Array.isArray(items) || items.length === 0) return;

        const first = items[0];
        const deviceType = first?.device?.device_type || '';

        // Ambil master checklist berdasarkan tipe device
        let checklist = [];
        if (deviceType) {
            try {
                const clRes = await fetch(`/api/checklist/${encodeURIComponent(deviceType)}`);
                checklist = await clRes.json();
            } catch (err) {
                console.error('Error loading master checklist', err);
            }
        }

        const detailContent = document.getElementById('detailContent');
        const header = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Device</label>
                    <p class="text-sm text-gray-900">${first.device?.device_name || 'N/A'}</p>
                    <p class="text-xs text-gray-500">${first.device?.device_type || 'N/A'} • ${first.device?.serial_number || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <p class="text-sm text-gray-900">${first.device?.room?.room_name || 'N/A'}</p>
                    <p class="text-xs text-gray-500">${first.device?.room?.floor?.floor_name || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Checked By</label>
                    <p class="text-sm text-gray-900">${first.user?.full_name || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check Date</label>
                    <p class="text-sm text-gray-900">${formatDate(first.checked_at)}</p>
                </div>
            </div>
        `;

        // Map hasil sesi by checklist_id
        const resultByChecklistId = {};
        items.forEach(r => { resultByChecklistId[r.checklist_id] = r; });

        // Buat rows berdasarkan master checklist (fallback ke items jika master kosong)
        const sourceList = (Array.isArray(checklist) && checklist.length > 0)
            ? checklist
            : items.map(i => i.checklistItem).filter(Boolean);

        const rows = sourceList.map(ci => {
            const cid = ci.checklist_id;
            const res = resultByChecklistId[cid] || null;
            const status = res?.status || 'pending';
            const notes = res?.notes || '';
            const question = ci.question || '';
            return `
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-900">${question}</td>
                    <td class="px-4 py-2">${getStatusBadge(status)}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">${notes}</td>
                </tr>
            `;
        }).join('');

        detailContent.innerHTML = header + `
            <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checklist</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">${rows}</tbody>
                </table>
            </div>
        `;
        document.getElementById('detailModal').classList.remove('hidden');
    } catch (e) {
        console.error('Error loading session detail', e);
        showNotification('Error loading session detail', 'error');
    }
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Handle form submission
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const url = editingId ? `/api/device-check-results/${editingId}` : '/api/device-check-results';
        const method = editingId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showNotification(editingId ? 'Check result updated successfully' : 'Check result added successfully', 'success');
            closeAddModal();
            loadCheckResults();
        } else {
            throw new Error('Failed to save check result');
        }
    } catch (error) {
        console.error('Error saving check result:', error);
        showNotification('Error saving check result', 'error');
    }
}

// Delete result
async function deleteResult(id) {
    if (!confirm('Are you sure you want to delete this check result?')) return;
    
    try {
        const response = await fetch(`/api/device-check-results/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('Check result deleted successfully', 'success');
            loadCheckResults();
        } else {
            throw new Error('Failed to delete check result');
        }
    } catch (error) {
        console.error('Error deleting check result:', error);
        showNotification('Error deleting check result', 'error');
    }
}

// Clear filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('deviceTypeFilter').value = '';
    document.getElementById('dateFilter').value = '';
    filterResults();
}

// Lazy-load SheetJS if not present
function loadXLSX() {
    return new Promise((resolve, reject) => {
        if (window.XLSX) return resolve();
        const script = document.createElement('script');
        script.src = 'https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Failed to load XLSX library'));
        document.head.appendChild(script);
    });
}

// Export data to XLSX including all checklist questions per session
async function exportData() {
    try {
        await loadXLSX();

        const rows = [];

        for (const result of filteredResults) {
            const deviceId = result.device_id;
            const checkedAt = result.checked_at || '';
            const url = checkedAt
                ? `/api/device-check-session-detail?device_id=${deviceId}&checked_at=${encodeURIComponent(checkedAt)}`
                : `/api/device-check-session-detail?device_id=${deviceId}`;

            try {
                const resp = await fetch(url);
                const items = await resp.json();
                if (!Array.isArray(items) || items.length === 0) continue;

                const first = items[0];
                const device = first.device || {};
                const room = device.room || {};
                const floor = room.floor || {};
                const user = first.user || {};
                const deviceType = device.device_type || '';

                let checklist = [];
                if (deviceType) {
                    try {
                        const clRes = await fetch(`/api/checklist/${encodeURIComponent(deviceType)}`);
                        checklist = await clRes.json();
                    } catch (_) {}
                }

                const resultByChecklistId = {};
                items.forEach(r => { resultByChecklistId[r.checklist_id] = r; });

                const sourceList = (Array.isArray(checklist) && checklist.length > 0)
                    ? checklist
                    : items.map(i => i.checklistItem).filter(Boolean);

                sourceList.forEach(ci => {
                    const cid = ci.checklist_id;
                    const res = resultByChecklistId[cid] || null;
                    const status = res && res.status ? res.status : 'pending';
                    const notes = res && res.notes ? res.notes : '';

                    rows.push({
                        'Device': device.device_name || result.device_name || '',
                        'Device Type': deviceType || result.device_type || '',
                        'Serial': device.serial_number || result.serial_number || '',
                        'Room': room.room_name || result.room_name || '',
                        'Floor': floor.floor_name || result.floor_name || '',
                        'Checked By': user.full_name || result.checked_by || '',
                        'Date': formatDate(first.checked_at || checkedAt),
                        'Checklist': ci.question || '',
                        'Status': status,
                        'Notes': notes
                    });
                });
            } catch (err) {
                console.error('Export: error loading session detail', err);
            }
        }

        if (rows.length === 0) {
            showNotification('No data to export', 'info');
            return;
        }

        const worksheet = XLSX.utils.json_to_sheet(rows, {
            header: ['Device', 'Device Type', 'Serial', 'Room', 'Floor', 'Checked By', 'Date', 'Checklist', 'Status', 'Notes']
        });
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Results');

        const filename = `device_check_results_${new Date().toISOString().split('T')[0]}.xlsx`;
        XLSX.writeFile(workbook, filename);
        showNotification('Exported to XLSX successfully', 'success');
    } catch (e) {
        console.error('Error exporting data', e);
        showNotification('Error exporting data', 'error');
    }
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

// Debounce function
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
</script>
@endsection