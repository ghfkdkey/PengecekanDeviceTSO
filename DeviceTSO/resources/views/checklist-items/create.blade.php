@extends('layouts.app')

@section('title', 'Tambah Checklist Item')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    .font-telkomsel {
        font-family: 'Telkomsel Batik Sans', sans-serif;
    }
    
    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 font-poppins">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                            <a href="{{ route('checklist-items.index') }}" class="hover:text-red-600 transition-colors">Checklist Items</a>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span class="text-gray-900">Tambah Item Baru</span>
                        </nav>
                        <h1 class="text-3xl font-bold text-gray-900 font-telkomsel">
                            Tambah Checklist Item
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Buat pertanyaan checklist baru untuk inspeksi perangkat
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('checklist-items.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-xl">
                <h2 class="text-lg font-semibold text-gray-900 font-telkomsel flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v4a2 2 0 002 2h2m0-6h6a2 2 0 012 2v4a2 2 0 01-2 2h-6m0-6v6"></path>
                    </svg>
                    Informasi Checklist Item
                </h2>
            </div>

            <form action="{{ route('checklist-items.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Device Type Selection -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="device_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Perangkat <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <select 
                                name="device_type" 
                                id="device_type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                required
                            >
                                <option value="">Pilih Jenis Perangkat</option>
                                <option value="">Pilih Tipe Device</option>
                                <option value="Computer" {{ old('device_type') == 'Computer' ? 'selected' : '' }}>Computer</option>
                                <option value="Smartboard" {{ old('device_type') == 'Smartboard' ? 'selected' : '' }}>Smartboard</option>
                                <option value="SmartTV" {{ old('device_type') == 'SmartTV' ? 'selected' : '' }}>SmartTV</option>
                                <option value="Digital_Signage" {{ old('device_type') == 'Digital_Signage' ? 'selected' : '' }}>Digital Signage</option>
                                <option value="VideoWall" {{ old('device_type') == 'VideoWall' ? 'selected' : '' }}>VideoWall</option>
                                <option value="Mini_PC" {{ old('device_type') == 'Mini_PC' ? 'selected' : '' }}>Mini PC</option>
                                <option value="Polycom" {{ old('device_type') == 'Polycom' ? 'selected' : '' }}>Polycom</option>
                                <option value="TV_Samsung_85" {{ old('device_type') == 'TV_Samsung_85' ? 'selected' : '' }}>TV Samsung 85</option>
                            </select>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tidak menemukan jenis perangkat? Ketik manual di bawah
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="custom_device_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Atau Ketik Manual
                        </label>
                        <input 
                            type="text" 
                            name="custom_device_type" 
                            id="custom_device_type" 
                            value="{{ old('custom_device_type') }}"
                            placeholder="Contoh: Smart TV, Tablet, dll."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                        >
                        <p class="mt-1 text-xs text-gray-500">Jika diisi, akan menimpa pilihan dropdown di atas</p>
                    </div>
                </div>

                <!-- Question Input -->
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-2">
                        Pertanyaan Checklist <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <textarea 
                            name="question" 
                            id="question" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                            placeholder="Contoh: Apakah kondisi fisik perangkat dalam keadaan baik (tidak retak, tidak berkarat)?"
                            required
                            maxlength="500"
                        >{{ old('question') }}</textarea>
                        <div class="flex justify-between items-center text-xs">
                            <div class="text-gray-500">
                                <span id="char-count">{{ strlen(old('question', '')) }}</span>/500 karakter
                            </div>
                            <div class="text-gray-500">
                                Gunakan kalimat tanya yang jelas dan spesifik
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question Examples -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Contoh Pertanyaan Berdasarkan Jenis Perangkat
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <p class="font-medium text-blue-800 mb-1">AC:</p>
                            <ul class="text-blue-700 space-y-0.5 ml-2">
                                <li>• Apakah filter AC bersih dari debu?</li>
                                <li>• Apakah AC mengeluarkan udara dingin dengan baik?</li>
                                <li>• Apakah tidak ada suara aneh saat AC beroperasi?</li>
                            </ul>
                        </div>
                        <div>
                            <p class="font-medium text-blue-800 mb-1">Komputer:</p>
                            <ul class="text-blue-700 space-y-0.5 ml-2">
                                <li>• Apakah komputer dapat menyala dengan normal?</li>
                                <li>• Apakah tidak ada blue screen atau error?</li>
                                <li>• Apakah semua port USB berfungsi dengan baik?</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Priority Level -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Tingkat Prioritas <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-red-500">
                            <input 
                                type="radio" 
                                name="priority" 
                                value="high" 
                                class="sr-only"
                                {{ old('priority') == 'high' ? 'checked' : '' }}
                            >
                            <div class="priority-indicator w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full hidden priority-dot"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">Tinggi</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Critical
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pengecekan wajib dan mendesak</p>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-red-500">
                            <input 
                                type="radio" 
                                name="priority" 
                                value="medium" 
                                class="sr-only"
                                {{ old('priority') == 'medium' || old('priority') == '' ? 'checked' : '' }}
                            >
                            <div class="priority-indicator w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full hidden priority-dot"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">Sedang</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Normal
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pengecekan rutin standar</p>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-red-500">
                            <input 
                                type="radio" 
                                name="priority" 
                                value="low" 
                                class="sr-only"
                                {{ old('priority') == 'low' ? 'checked' : '' }}
                            >
                            <div class="priority-indicator w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full hidden priority-dot"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">Rendah</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Optional
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pengecekan tambahan</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                        placeholder="Tambahkan catatan atau instruksi khusus untuk pengecekan ini..."
                        maxlength="255"
                    >{{ old('notes') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="notes-char-count">{{ strlen(old('notes', '')) }}</span>/255 karakter
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pastikan pertanyaan mudah dipahami dan dapat dijawab dengan jelas
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('checklist-items.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Checklist Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for question
    const questionTextarea = document.getElementById('question');
    const charCount = document.getElementById('char-count');
    
    questionTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 450) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    });

    // Character counter for notes
    const notesTextarea = document.getElementById('notes');
    const notesCharCount = document.getElementById('notes-char-count');
    
    notesTextarea.addEventListener('input', function() {
        const length = this.value.length;
        notesCharCount.textContent = length;
        
        if (length > 230) {
            notesCharCount.classList.add('text-red-500');
        } else {
            notesCharCount.classList.remove('text-red-500');
        }
    });

    // Device type handling
    const deviceTypeSelect = document.getElementById('device_type');
    const customDeviceTypeInput = document.getElementById('custom_device_type');
    
    customDeviceTypeInput.addEventListener('input', function() {
        if (this.value.trim()) {
            deviceTypeSelect.value = '';
            deviceTypeSelect.disabled = true;
            deviceTypeSelect.classList.add('bg-gray-100', 'text-gray-400');
        } else {
            deviceTypeSelect.disabled = false;
            deviceTypeSelect.classList.remove('bg-gray-100', 'text-gray-400');
        }
    });

    deviceTypeSelect.addEventListener('change', function() {
        if (this.value) {
            customDeviceTypeInput.value = '';
        }
    });

    // Priority radio buttons visual feedback
    const priorityRadios = document.querySelectorAll('input[name="priority"]');
    
    priorityRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all indicators
            document.querySelectorAll('.priority-indicator').forEach(indicator => {
                indicator.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
                indicator.classList.add('border-gray-300');
                indicator.querySelector('.priority-dot').classList.add('hidden');
            });
            
            // Highlight selected
            const indicator = this.closest('label').querySelector('.priority-indicator');
            const dot = indicator.querySelector('.priority-dot');
            
            if (this.value === 'high') {
                indicator.classList.remove('border-gray-300');
                indicator.classList.add('border-red-500');
            } else if (this.value === 'medium') {
                indicator.classList.remove('border-gray-300');
                indicator.classList.add('border-yellow-500');
            } else if (this.value === 'low') {
                indicator.classList.remove('border-gray-300');
                indicator.classList.add('border-green-500');
            }
            
            dot.classList.remove('hidden');
        });
    });

    // Initialize priority selection if there's old input
    const selectedPriority = document.querySelector('input[name="priority"]:checked');
    if (selectedPriority) {
        selectedPriority.dispatchEvent(new Event('change'));
    }

    // Form validation enhancement
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check device type
        const deviceType = deviceTypeSelect.value;
        const customDeviceType = customDeviceTypeInput.value.trim();
        
        if (!deviceType && !customDeviceType) {
            alert('Silakan pilih jenis perangkat atau ketik manual.');
            e.preventDefault();
            isValid = false;
        }
        
        // Check question
        if (!questionTextarea.value.trim()) {
            alert('Pertanyaan checklist harus diisi.');
            e.preventDefault();
            isValid = false;
        }
        
        // Check priority
        const selectedPriority = document.querySelector('input[name="priority"]:checked');
        if (!selectedPriority) {
            alert('Silakan pilih tingkat prioritas.');
            e.preventDefault();
            isValid = false;
        }
        
        if (isValid) {
            // Show loading state
            submitButton.disabled = true;
            const buttonText = submitButton.querySelector('span');
            buttonText.textContent = 'Menyimpan...';
            
            // Add spinner
            const spinner = document.createElement('svg');
            spinner.className = 'animate-spin -ml-1 mr-3 h-4 w-4 text-white';
            spinner.innerHTML = `
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            `;
            buttonText.parentNode.insertBefore(spinner, buttonText);
        }
    });

    // Auto-resize textareas
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    questionTextarea.addEventListener('input', function() {
        autoResize(this);
    });

    notesTextarea.addEventListener('input', function() {
        autoResize(this);
    });
});
</script>
@endpush
@endsection