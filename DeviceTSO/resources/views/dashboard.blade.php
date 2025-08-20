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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDevices ?? 24 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">↑ 2 device</span>
                    <span class="text-gray-600 ml-2">dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Checked Today -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dicek Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $checkedToday ?? 18 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $checkedTodayPercentage ?? 75 }}%</span>
                    <span class="text-gray-600 ml-2">dari total device</span>
                </div>
            </div>
        </div>

        <!-- Issues Found -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-red-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Issues Ditemukan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $issuesFound ?? 3 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-red-600 font-medium">↑ 1 issue</span>
                    <span class="text-gray-600 ml-2">dari kemarin</span>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-telkomsel-yellow/20 rounded-lg p-3">
                    <svg class="w-6 h-6 text-telkomsel-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tingkat Penyelesaian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $completionRate ?? 92 }}%</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">↑ 5%</span>
                    <span class="text-gray-600 ml-2">dari minggu lalu</span>
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
                    <a href="{{ route('reports.index') }}" class="text-telkomsel-red hover:text-telkomsel-dark-red text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($activity['type'] == 'check')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @elseif($activity['type'] == 'issue')
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }} • {{ $activity['user'] }}</p>
                        </div>
                    </div>
                    @empty
                    <!-- Sample Data -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Smart Board di Meeting Room A telah dicek dan berfungsi normal</p>
                            <p class="text-xs text-gray-500 mt-1">2 menit yang lalu • {{ auth()->user()->full_name ?? auth()->user()->username ?? 'User' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Issue ditemukan pada LED Diorama Lantai 2 - tidak menyala</p>
                            <p class="text-xs text-gray-500 mt-1">15 menit yang lalu • Ahmad Fadli</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Tablet Meeting Room B berhasil di-update dan berfungsi normal</p>
                            <p class="text-xs text-gray-500 mt-1">1 jam yang lalu • Siti Nurhaliza</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Pengecekan rutin Smart TV Lobby telah selesai dilakukan</p>
                            <p class="text-xs text-gray-500 mt-1">2 jam yang lalu • Budi Santoso</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Device Status -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('check.index') }}" class="w-full bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red text-white rounded-lg p-4 flex items-center space-x-3 hover:from-telkomsel-dark-red hover:to-telkomsel-red transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Mulai Pengecekan</span>
                    </a>
                    
                    <a href="{{ route('reports.index') }}" class="w-full bg-gray-100 text-gray-700 rounded-lg p-4 flex items-center space-x-3 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="font-medium">Lihat Laporan</span>
                    </a>
                    
                    @if(auth()->user()->isAdmin())
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

            <!-- Device Status Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Status Device</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Normal -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-700">Normal</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $normalDevices ?? 21 }}</span>
                        </div>
                        
                        <!-- Warning -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-700">Warning</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $warningDevices ?? 0 }}</span>
                        </div>
                        
                        <!-- Error -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-700">Error</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $errorDevices ?? 3 }}</span>
                        </div>
                        
                        <!-- Not Checked -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-sm text-gray-700">Belum Dicek</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $notCheckedDevices ?? 6 }}</span>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress Hari Ini</span>
                            <span>{{ $checkedTodayPercentage ?? 75 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-telkomsel-red to-telkomsel-dark-red h-2 rounded-full" style="width: {{ $checkedTodayPercentage ?? 75 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-telkomsel font-semibold text-gray-900">Issues Terbaru</h3>
                <a href="{{ route('reports.index') }}?filter=issues" class="text-telkomsel-red hover:text-telkomsel-dark-red text-sm font-medium">
                    Lihat Semua Issues →
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentIssues ?? [] as $issue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $issue['device'] }}</div>
                            <div class="text-sm text-gray-500">{{ $issue['device_type'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $issue['location'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $issue['description'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Error
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $issue['time'] }}</td>
                    </tr>
                    @empty
                    <!-- Sample Data -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">LED Diorama #003</div>
                            <div class="text-sm text-gray-500">LED Display</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lantai 2 - Lobby</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Display tidak menyala</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Error
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 menit yang lalu</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Smart Board #005</div>
                            <div class="text-sm text-gray-500">Interactive Display</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lantai 3 - Meeting Room C</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Touch screen tidak responsif</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Error
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 jam yang lalu</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Tablet Meeting #007</div>
                            <div class="text-sm text-gray-500">Android Tablet</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lantai 1 - Meeting Room A</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Aplikasi sering crash</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Error
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 jam yang lalu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh dashboard every 5 minutes
    setInterval(function() {
        // You can add AJAX calls here to refresh specific parts of the dashboard
        console.log('Dashboard auto refresh...');
    }, 300000);

    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dashboard components
        console.log('Dashboard loaded');
    });
</script>
@endpush