@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                            Kelola User
                        </h1>
                        <p class="mt-2 text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                            Mengelola user dan hak akses
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="openAddModal()" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah User
                        </button>
                        <button onclick="exportData()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200" style="font-family: 'Poppins', sans-serif;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Admin -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Total Admin</p>
                        <p class="text-2xl font-bold text-red-600" id="adminCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>

            <!-- Total PIC GA -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Total PIC GA</p>
                        <p class="text-2xl font-bold text-yellow-600" id="gaCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>

            <!-- Total PIC Operasional -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600" style="font-family: 'Poppins', sans-serif;">Total PIC Operasional</p>
                        <p class="text-2xl font-bold text-green-600" id="operationalCount" style="font-family: 'Telkomsel Batik Sans', sans-serif;">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Pencarian</label>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari username, nama lengkap..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="roleFilter" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Role</label>
                        <select id="roleFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                            style="font-family: 'Poppins', sans-serif;">
                            
                            <option value="">Select Role</option>

                            @if(auth()->user()->isAdmin())
                                <option value="{{ \App\Models\User::ROLE_ADMIN }}">Admin</option>
                                <option value="{{ \App\Models\User::ROLE_PIC_GA }}">PIC General Affair (GA)</option>
                                <option value="{{ \App\Models\User::ROLE_PIC_OPERATIONAL }}">PIC Operasional</option>
                            @endif
                            @if(auth()->user()->isGA())
                                <option value="{{ \App\Models\User::ROLE_PIC_OPERATIONAL }}">PIC Operasional</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Sort By</label>
                        <select id="sortBy" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="created_at">Terbaru</option>
                            <option value="full_name">Nama A-Z</option>
                            <option value="username">Username A-Z</option>
                            <option value="role">Role</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <button onclick="clearFilters()" class="text-sm text-gray-600 hover:text-gray-800" style="font-family: 'Poppins', sans-serif;">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                    System Users
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                Username
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                Tanggal Dibuat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
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
                    Tambahkan User Baru
                </h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="userForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Username</label>
                        <input type="text" id="username" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" placeholder="Masukkan username">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" placeholder="Masukkan email">
                    </div>
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Nama Lengkap</label>
                        <input type="text" id="full_name" name="full_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">Role</label>
                        <select id="role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="PIC General Affair (GA)">PIC General Affair (GA)</option>
                            <option value="PIC Operasional">PIC Operasional</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                            Password <span id="passwordRequired">(Required)</span>
                        </label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;" placeholder="Masukkan password">
                        <p class="text-xs text-gray-500 mt-1" id="passwordHelp">Minimum 6 characters</p>
                    </div>
                </div>
                <div id="regional-select-container" class="hidden">
                    <label for="regional" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Poppins', sans-serif;">
                        Regional Assignment <span class="text-red-500">*</span>
                    </label>
                    <select id="regional" name="regional_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" style="font-family: 'Poppins', sans-serif;">
                        <option value="">Select Regional</option>
                    </select>
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
                    User Details
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

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
let users = [];
let filteredUsers = [];
let currentPage = 1;
const itemsPerPage = 10;
let editingId = null;

// Get CSRF token
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    setupEventListeners();
});

// Load users from API
async function loadUsers() {
    try {
        const response = await fetch('/api/users', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        users = data.users;
        currentUserRole = data.currentUserRole;
        currentUserRegional = data.currentUserRegional;
        
        // Filter users based on role
        if (currentUserRole === 'PIC General Affair (GA)') {
            // PIC GA can only see PIC Operational in their regional
            users = users.filter(user => 
                user.id === data.currentUserId || 
                (user.role === 'PIC Operasional' && user.regional_id === currentUserRegional)
            );
        }
        
        filteredUsers = [...users];
        updateStats();
        renderTable();
        updatePagination();
    } catch (error) {
        console.error('Error loading users:', error);
        showNotification('Error loading users: ' + error.message, 'error');
    }
}

// Load regionals from API
async function loadRegionals() {
    try {
        const response = await fetch('/api/regionals', {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load regionals');
        
        const regionals = await response.json();
        const regionalSelect = document.getElementById('regional');
        regionalSelect.innerHTML = '<option value="">Select Regional</option>';
        
        regionals.forEach(regional => {
            regionalSelect.innerHTML += `
                <option value="${regional.regional_id}">
                    ${regional.regional_name}
                </option>
            `;
        });
    } catch (error) {
        console.error('Error loading regionals:', error);
        showNotification('Error loading regionals', 'error');
    }
}

// Setup event listeners
function setupEventListeners() {
    // Search input
    document.getElementById('searchInput').addEventListener('input', debounce(filterUsers, 300));
    
    // Filter selects
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    document.getElementById('sortBy').addEventListener('change', filterUsers);
    
    // Form submission
    document.getElementById('userForm').addEventListener('submit', handleFormSubmit);
    
    // Role change handler
    document.getElementById('role').addEventListener('change', function() {
        const regionalContainer = document.getElementById('regional-select-container');
        const regionalSelect = document.getElementById('regional');
        
        if (this.value === 'PIC General Affair (GA)' || this.value === 'PIC Operasional') {
            regionalContainer.classList.remove('hidden');
            regionalSelect.required = true;
            loadRegionals();
        } else {
            regionalContainer.classList.add('hidden');
            regionalSelect.required = false;
            regionalSelect.value = '';
        }
    });
}

// Filter users
function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const sortBy = document.getElementById('sortBy').value;
    
    filteredUsers = users.filter(user => {
        const matchesSearch = !searchTerm || 
            (user.username && user.username.toLowerCase().includes(searchTerm)) ||
            (user.full_name && user.full_name.toLowerCase().includes(searchTerm));
        
        const matchesRole = !roleFilter || user.role === roleFilter;
        
        return matchesSearch && matchesRole;
    });
    
    // Sort users
    filteredUsers.sort((a, b) => {
        switch(sortBy) {
            case 'full_name':
                return (a.full_name || '').localeCompare(b.full_name || '');
            case 'username':
                return (a.username || '').localeCompare(b.username || '');
            case 'role':
                return (a.role || '').localeCompare(b.role || '');
            case 'created_at':
            default:
                return new Date(b.created_at || 0) - new Date(a.created_at || 0);
        }
    });
    
    currentPage = 1;
    renderTable();
    updatePagination();
}

// Update statistics
function updateStats() {
    const adminCount = users.filter(u => u.role === 'admin').length;
    const gaCount = users.filter(u => u.role === 'PIC General Affair (GA)').length;
    const operationalCount = users.filter(u => u.role === 'PIC Operasional').length;

    document.getElementById('adminCount').textContent = adminCount;
    document.getElementById('gaCount').textContent = gaCount;
    document.getElementById('operationalCount').textContent = operationalCount;
}

// Render table
function renderTable() {
    const tbody = document.getElementById('usersTableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageUsers = filteredUsers.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (pageUsers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500" style="font-family: 'Poppins', sans-serif;">
                    No users found
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">
                        ${user.regional ? user.regional.regional_name : 'N/A'}
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    pageUsers.forEach(user => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        const roleBadge = getRoleBadge(user.role);
        const formattedDate = formatDate(user.created_at);
        const avatar = getAvatarInitials(user.full_name);
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center text-white font-semibold text-sm" style="font-family: 'Telkomsel Batik Sans', sans-serif;">
                            ${avatar}
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif;">
                            ${user.full_name || 'N/A'}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    ${user.username || 'N/A'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${roleBadge}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="font-family: 'Poppins', sans-serif;">
                ${formattedDate}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewDetail(${user.id})" class="text-blue-600 hover:text-blue-900" title="View Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button onclick="editUser(${user.id})" class="text-indigo-600 hover:text-indigo-900" title="Edit User">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900" title="Delete User">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Update showing info
    const showingFrom = filteredUsers.length === 0 ? 0 : startIndex + 1;
    const showingTo = Math.min(endIndex, filteredUsers.length);
    document.getElementById('showingFrom').textContent = showingFrom;
    document.getElementById('showingTo').textContent = showingTo;
    document.getElementById('totalResults').textContent = filteredUsers.length;
}

// Get role badge HTML
function getRoleBadge(role) {
    switch (role) {
        case 'admin':
            return `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Admin
                </span>
            `;
        case 'PIC General Affair (GA)':
            return `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    PIC General Affair (GA)
                </span>
            `;
        case 'PIC Operasional':
            return `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    PIC Operasional
                </span>
            `;
        default:
            return `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    ${role}
                </span>
            `;
    }
}

// Get avatar initials
function getAvatarInitials(fullName) {
    if (!fullName) return '?';
    const names = fullName.split(' ');
    if (names.length >= 2) {
        return names[0].charAt(0).toUpperCase() + names[1].charAt(0).toUpperCase();
    }
    return fullName.charAt(0).toUpperCase();
}

// Format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
}

// Update pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
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

// Modal functions
function openAddModal() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Tambahkan User Baru';
    document.getElementById('userForm').reset();
    document.getElementById('passwordRequired').textContent = '(Required)';
    document.getElementById('passwordHelp').textContent = 'Minimum 6 characters';
    document.getElementById('password').required = true;
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

function editUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;
    
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Edit User';
    document.getElementById('passwordRequired').textContent = '(Optional)';
    document.getElementById('passwordHelp').textContent = 'Leave blank to keep current password';
    document.getElementById('password').required = false;
    
    // Populate form
    document.getElementById('username').value = user.username || '';
    document.getElementById('email').value = user.email || '';
    document.getElementById('full_name').value = user.full_name || '';
    document.getElementById('role').value = user.role || '';
    document.getElementById('password').value = '';
    
    // Handle regional selection
    const regionalContainer = document.getElementById('regional-select-container');
    const regionalSelect = document.getElementById('regional');
    
    if (user.role === 'PIC General Affair (GA)' || user.role === 'PIC Operasional') {
        regionalContainer.classList.remove('hidden');
        regionalSelect.required = true;
        loadRegionals().then(() => {
            if (user.regional_id) {
                regionalSelect.value = user.regional_id;
            }
        });
    } else {
        regionalContainer.classList.add('hidden');
        regionalSelect.required = false;
    }
    
    document.getElementById('addModal').classList.remove('hidden');
}

function viewDetail(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;
    
    const detailContent = document.getElementById('detailContent');
    detailContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <p class="text-sm text-gray-900">${user.full_name || 'N/A'}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <p class="text-sm text-gray-900">${user.username || 'N/A'}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-sm text-gray-900">${user.email || 'N/A'}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <p class="text-sm text-gray-900">${user.role || 'N/A'}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                <p class="text-sm text-gray-900">${formatDate(user.created_at)}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                <p class="text-sm text-gray-900">${formatDate(user.updated_at)}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Regional Assignment</label>
                <p class="text-sm text-gray-900">${user.regional ? user.regional.regional_name : 'N/A'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Handle form submission
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    if (data.role === 'admin') {
        delete data.regional_id;
    }
    
    // Remove empty password for edit
    if (editingId && !data.password) {
        delete data.password;
    }
    
    try {
        const url = editingId ? `/api/users/${editingId}` : '/api/users';
        const method = editingId ? 'PUT' : 'POST';
        
        const csrfToken = getCSRFToken();
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        console.log('Submitting form:', { url, method, data });
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        const responseData = await response.json();
        console.log('Response:', responseData);
        
        if (response.ok && responseData.success !== false) {
            showNotification(editingId ? 'User updated successfully' : 'User added successfully', 'success');
            closeAddModal();
            loadUsers();
        } else {
            throw new Error(responseData.message || 'Failed to save user');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showNotification('Error saving user: ' + error.message, 'error');
    }
}

// Delete user
async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    
    try {
        const csrfToken = getCSRFToken();
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(`/api/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const responseData = await response.json();
        
        if (response.ok && responseData.success !== false) {
            showNotification('User deleted successfully', 'success');
            loadUsers();
        } else {
            throw new Error(responseData.message || 'Failed to delete user');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        showNotification('Error deleting user: ' + error.message, 'error');
    }
}

// Clear filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('sortBy').value = 'created_at';
    filterUsers();
}

// Export data
function exportData() {
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Full Name,Username,Role,Created Date\n"
        + filteredUsers.map(user => [
            user.full_name || '',
            user.username || '',
            user.role || '',
            formatDate(user.created_at)
        ].join(",")).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `users_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
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