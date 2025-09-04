@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview sistem pengecekan device Telkomsel')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-telkomsel font-bold mb-2">Selamat Datang, {{ auth()->user()->full_name ?? auth()->user()->username ?? 'User' }}!</h2>
                <p class="text-white/90">Hari ini adalah {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                <p class="text-white/80 text-sm mt-1">Mari mulai pengecekan device untuk memastikan semua sistem berjalan dengan baik</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.92C16.16 26.74 20 22.55 20 17V7l-10-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200 mb-6 w-full md:w-1/4">
        <div class="flex items-end space-x-2">
            <div class="flex-1">
                <label for="dateFilter" class="block text-sm font-medium text-gray-700">
                    Filter Tanggal
                </label>
                <input type="date" id="dateFilter" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <button id="resetDateFilter" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Devices -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Device</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalDevices">-</p>
                </div>
            </div>
        </div>

        <!-- Pending Devices -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Device Pending</p>
                    <p class="text-2xl font-bold text-yellow-600" id="pendingDevicesCount">-</p>
                </div>
            </div>
        </div>

        <!-- Passed Devices -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Device Passed</p>
                    <p class="text-2xl font-bold text-green-600" id="passedDevices">-</p>
                </div>
            </div>
        </div>

        <!-- Failed Devices -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-red-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Device Failed</p>
                    <p class="text-2xl font-bold text-red-600" id="failedDevices">-</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Aktivitas Terbaru</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="recentActivities">
                    <!-- Activities will be loaded here dynamically -->
                </div>
            </div>
        </div>

        <!-- Quick Actions & Device Status Chart -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('device-check.page') }}" class="w-full bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white rounded-lg p-4 flex items-center space-x-3 hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Mulai Pengecekan</span>
                    </a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isGA())
                    <a href="{{ route('devices.index') }}" class="w-full bg-gray-100 text-gray-700 rounded-lg p-4 flex items-center space-x-3 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">Kelola Device</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Device Status Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Status Device</h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <canvas id="deviceStatusChart" width="300" height="300"></canvas>
                    </div>
                    <!-- Legend -->
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-gray-700">Pending</span>
                            </div>
                            <span class="font-medium text-gray-900" id="pendingLegend">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-700">Passed</span>
                            </div>
                            <span class="font-medium text-gray-900" id="passedLegend">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-gray-700">Failed</span>
                            </div>
                            <span class="font-medium text-gray-900" id="failedLegend">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                                <span class="text-gray-700">Maintenance</span>
                            </div>
                            <span class="font-medium text-gray-900" id="maintenanceLegend">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Dashboard data
    let dashboardData = {
        stats: {},
        activities: []
    };

    // Chart instance
    let deviceStatusChart = null;

    // Load dashboard data
    async function loadDashboardData() {
        try {
            const selectedDate = document.getElementById('dateFilter').value;
            let statsUrl = '/api/dashboard/stats';

            if (selectedDate) {
                statsUrl += `?date=${selectedDate}`;
            }

            const statsResponse = await fetch(statsUrl);
            if (statsResponse.ok) {
                dashboardData.stats = await statsResponse.json();
                updateStats();
                updateChart();
            }

            const activitiesResponse = await fetch('/api/dashboard/activities?limit=5');
            if (activitiesResponse.ok) {
                dashboardData.activities = await activitiesResponse.json();
                updateActivities();
            }
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    function updateStats() {
        const stats = dashboardData.stats;
        document.getElementById('totalDevices').textContent = stats.total_devices || 0;
        document.getElementById('pendingDevicesCount').textContent = stats.pending_devices || 0;
        document.getElementById('passedDevices').textContent = stats.passed_devices || 0;
        document.getElementById('failedDevices').textContent = stats.failed_devices || 0;
    }

    function updateChart() {
        const stats = dashboardData.stats;
        const ctx = document.getElementById('deviceStatusChart').getContext('2d');
        
        const pendingCount = stats.pending_devices || 0;
        const passedCount = stats.passed_devices || 0;
        const failedCount = stats.failed_devices || 0;
        const maintenanceCount = stats.maintenance_devices || 0;
        
        document.getElementById('pendingLegend').textContent = pendingCount;
        document.getElementById('passedLegend').textContent = passedCount;
        document.getElementById('failedLegend').textContent = failedCount;
        document.getElementById('maintenanceLegend').textContent = maintenanceCount;

        if (deviceStatusChart) {
            deviceStatusChart.destroy();
        }

        deviceStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Passed', 'Failed', 'Maintenance'],
                datasets: [{
                    data: [pendingCount, passedCount, failedCount, maintenanceCount],
                    backgroundColor: [
                        '#EAB308', 
                        '#22C55E', 
                        '#EF4444', 
                        '#6B7280'  
                    ],
                    borderColor: [
                        '#EAB308',
                        '#22C55E',
                        '#EF4444',
                        '#6B7280'
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    function formatTimeAgo(dateString) {
        try {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            // Jika kurang dari 1 menit
            if (diffInSeconds < 60) {
                return 'baru saja';
            }
            
            // Jika kurang dari 1 jam
            if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} menit yang lalu`;
            }
            
            // Jika kurang dari 1 hari
            if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} jam yang lalu`;
            }
            
            // Jika kurang dari 1 minggu
            if (diffInSeconds < 604800) {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} hari yang lalu`;
            }
            
            // Jika lebih dari 1 minggu, tampilkan tanggal
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'waktu tidak diketahui';
        }
    }

    // Update activities dengan penanganan timezone yang lebih baik
    function updateActivities() {
        const container = document.getElementById('recentActivities');
        const activities = dashboardData.activities;

        if (activities.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <p>Tidak ada aktivitas terbaru</p>
                </div>
            `;
            return;
        }

        container.innerHTML = activities.map(activity => {
            const icon = getActivityIcon(activity.type);
            const timeAgo = formatTimeAgo(activity.created_at);
            
            return `
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        ${icon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">${activity.description}</p>
                        <p class="text-xs text-gray-500 mt-1">${timeAgo} • ${activity.user_name}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Fungsi untuk mendapatkan icon berdasarkan tipe aktivitas
    function getActivityIcon(type) {
        const icons = {
            'device_check': `
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            `,
            'device_added': `
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            `,
            'floor_added': `
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            `,
            'room_added': `
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
            `,
            'building_added': `
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            `,
            'regional_added': `
                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            `,
            'area_added': `
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            `,
            'default': `
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            `
        };
        
        return icons[type] || icons.default;
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardData();
        
        const dateFilter = document.getElementById('dateFilter');
        const resetDateFilter = document.getElementById('resetDateFilter');

        dateFilter.addEventListener('change', loadDashboardData);

        resetDateFilter.addEventListener('click', () => {
            dateFilter.value = ''; 
            loadDashboardData();   
        });
    
    });
</script>
@endpush