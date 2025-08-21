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

            <form action="{{ route('checklist-items.store') }}" method="POST" class="p-6 space-y-6" id="checklistForm">
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

                <!-- Questions Container -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Pertanyaan Checklist <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="addQuestionBtn" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Pertanyaan
                        </button>
                    </div>
                    
                    <div id="questionsContainer" class="space-y-4">
                        <!-- Question 1 (Default) -->
                        <div class="question-item bg-gray-50 border border-gray-200 rounded-lg p-4" data-question-id="1">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-sm font-medium text-gray-900">Pertanyaan #1</h4>
                                <button type="button" class="remove-question-btn text-red-600 hover:text-red-800 transition-colors" style="display: none;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            <textarea 
                                name="questions[]" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                placeholder="Contoh: Apakah kondisi fisik perangkat dalam keadaan baik (tidak retak, tidak berkarat)?"
                                required
                                maxlength="500"
                                rows="3"
                            >{{ old('questions.0') }}</textarea>
                            <div class="flex justify-between items-center text-xs mt-2">
                                <div class="text-gray-500">
                                    <span class="char-count">0</span>/500 karakter
                                </div>
                                <div class="text-gray-500">
                                    Gunakan kalimat tanya yang jelas dan spesifik
                                </div>
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
    let questionCounter = 1;
    const questionsContainer = document.getElementById('questionsContainer');
    const addQuestionBtn = document.getElementById('addQuestionBtn');
    const form = document.getElementById('checklistForm');

    // Add new question
    addQuestionBtn.addEventListener('click', function() {
        questionCounter++;
        const questionItem = createQuestionItem(questionCounter);
        questionsContainer.appendChild(questionItem);
        updateQuestionNumbers();
        updateRemoveButtons();
    });

    // Create question item HTML
    function createQuestionItem(id) {
        const div = document.createElement('div');
        div.className = 'question-item bg-gray-50 border border-gray-200 rounded-lg p-4';
        div.setAttribute('data-question-id', id);
        
        div.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-900">Pertanyaan #${id}</h4>
                <button type="button" class="remove-question-btn text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <textarea 
                name="questions[]" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                placeholder="Contoh: Apakah kondisi fisik perangkat dalam keadaan baik (tidak retak, tidak berkarat)?"
                required
                maxlength="500"
                rows="3"
            ></textarea>
            <div class="flex justify-between items-center text-xs mt-2">
                <div class="text-gray-500">
                    <span class="char-count">0</span>/500 karakter
                </div>
                <div class="text-gray-500">
                    Gunakan kalimat tanya yang jelas dan spesifik
                </div>
            </div>
        `;

        // Add character counter functionality
        const textarea = div.querySelector('textarea');
        const charCount = div.querySelector('.char-count');
        
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length > 450) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // Add remove button functionality
        const removeBtn = div.querySelector('.remove-question-btn');
        removeBtn.addEventListener('click', function() {
            div.remove();
            updateQuestionNumbers();
            updateRemoveButtons();
        });

        return div;
    }

    // Update question numbers
    function updateQuestionNumbers() {
        const questionItems = questionsContainer.querySelectorAll('.question-item');
        questionItems.forEach((item, index) => {
            const title = item.querySelector('h4');
            title.textContent = `Pertanyaan #${index + 1}`;
            item.setAttribute('data-question-id', index + 1);
        });
    }

    // Update remove buttons visibility
    function updateRemoveButtons() {
        const questionItems = questionsContainer.querySelectorAll('.question-item');
        const removeButtons = questionsContainer.querySelectorAll('.remove-question-btn');
        
        if (questionItems.length === 1) {
            removeButtons.forEach(btn => btn.style.display = 'none');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'block');
        }
    }

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

    // Initialize character counter for first question
    const firstTextarea = questionsContainer.querySelector('textarea');
    const firstCharCount = questionsContainer.querySelector('.char-count');
    
    firstTextarea.addEventListener('input', function() {
        const length = this.value.length;
        firstCharCount.textContent = length;
        
        if (length > 450) {
            firstCharCount.classList.add('text-red-500');
        } else {
            firstCharCount.classList.remove('text-red-500');
        }
    });

    // Form validation
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
        
        // Check questions
        const questions = questionsContainer.querySelectorAll('textarea[name="questions[]"]');
        let hasValidQuestion = false;
        
        questions.forEach((textarea, index) => {
            if (textarea.value.trim()) {
                hasValidQuestion = true;
            }
        });
        
        if (!hasValidQuestion) {
            alert('Minimal satu pertanyaan checklist harus diisi.');
            e.preventDefault();
            isValid = false;
        }
        
        if (isValid) {
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            const buttonText = submitButton.innerHTML;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }
    });

    // Initialize
    updateRemoveButtons();
});
</script>
@endpush
@endsection