@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4 px-4 sm:py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit Car</h1>
        
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->has('airtable') || $errors->has('images'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Error!</p>
                        @if($errors->has('airtable'))
                            <p class="text-sm">{{ $errors->first('airtable') }}</p>
                        @endif
                        @if($errors->has('images'))
                            <p class="text-sm">{{ $errors->first('images') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <form action="{{ route('admin.cars.update', $id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-4 sm:p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Car Name</label>
                    <input id="name" name="name" type="text" class="karsource-input" placeholder="Enter car name" required value="{{ old('name', $car['fields']['Name'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <label for="condition" class="block text-sm font-semibold text-gray-700 mb-2">Condition</label>
                    <select id="condition" name="condition" class="karsource-select" required>
                        <option value="">Select Condition</option>
                        <option value="Local Used" @if((old('condition', $car['fields']['Condition'] ?? '')) == 'Local Used') selected @endif>Local Used</option>
                        <option value="Foreign Used" @if((old('condition', $car['fields']['Condition'] ?? '')) == 'Foreign Used') selected @endif>Foreign Used</option>
                        <option value="Brand New" @if((old('condition', $car['fields']['Condition'] ?? '')) == 'Brand New') selected @endif>Brand New</option>
                    </select>
                    <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                </div>
                <div>
                    <label for="mileage" class="block text-sm font-semibold text-gray-700 mb-2">Mileage (km)</label>
                    <input id="mileage" name="mileage" type="text" class="karsource-input number-input" placeholder="e.g., 45,000" required value="{{ old('mileage', $car['fields']['Mileage'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('mileage')" class="mt-2" />
                </div>
                <div>
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                    <input id="year" name="year" type="number" class="karsource-input" placeholder="e.g., 2020" min="1990" max="{{ date('Y') + 1 }}" required value="{{ old('year', $car['fields']['Year'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>
                <div>
                    <label for="transmission" class="block text-sm font-semibold text-gray-700 mb-2">Transmission</label>
                    <select id="transmission" name="transmission" class="karsource-select" required>
                        <option value="">Select Transmission</option>
                        <option value="Automatic Transmission" @if((old('transmission', $car['fields']['Transmission'] ?? '')) == 'Automatic Transmission') selected @endif>Automatic</option>
                        <option value="Manual Transmission" @if((old('transmission', $car['fields']['Transmission'] ?? '')) == 'Manual Transmission') selected @endif>Manual</option>
                    </select>
                    <x-input-error :messages="$errors->get('transmission')" class="mt-2" />
                </div>
                <div>
                    <label for="fuel" class="block text-sm font-semibold text-gray-700 mb-2">Fuel Type</label>
                    <select id="fuel" name="fuel" class="karsource-select" required>
                        <option value="">Select Fuel Type</option>
                        <option value="hybrid" @if(strtolower(old('fuel', $car['fields']['Fuel Type'] ?? '')) == 'hybrid') selected @endif>Hybrid</option>
                        <option value="petrol" @if(strtolower(old('fuel', $car['fields']['Fuel Type'] ?? '')) == 'petrol') selected @endif>Petrol</option>
                        <option value="electric" @if(strtolower(old('fuel', $car['fields']['Fuel Type'] ?? '')) == 'electric') selected @endif>Electric</option>
                    </select>
                    <x-input-error :messages="$errors->get('fuel')" class="mt-2" />
                </div>
                <div>
                    <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                    <input id="color" name="color" type="text" class="karsource-input" placeholder="e.g., Red, Blue, White" required value="{{ old('color', $car['fields']['Color'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('color')" class="mt-2" />
                </div>
                <div>
                    <label for="engine_size" class="block text-sm font-semibold text-gray-700 mb-2">Engine Size (L)</label>
                    <input id="engine_size" name="engine_size" type="number" step="0.1" class="karsource-input" placeholder="e.g., 2.0" required value="{{ old('engine_size', $car['fields']['Engine Size'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('engine_size')" class="mt-2" />
                </div>
                <div>
                    <label for="horsepower" class="block text-sm font-semibold text-gray-700 mb-2">Horsepower</label>
                    <input id="horsepower" name="horsepower" type="number" class="karsource-input" placeholder="e.g., 200" required value="{{ old('horsepower', $car['fields']['Horsepower'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('horsepower')" class="mt-2" />
                </div>
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                    <input id="location" name="location" type="text" class="karsource-input" placeholder="e.g., Lagos, Abuja" required value="{{ old('location', $car['fields']['Location'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>
                <div>
                    <label for="registered_city" class="block text-sm font-semibold text-gray-700 mb-2">Registered City</label>
                    <input id="registered_city" name="registered_city" type="text" class="karsource-input" placeholder="e.g., Lagos" required value="{{ old('registered_city', $car['fields']['Registered City'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('registered_city')" class="mt-2" />
                </div>
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (₦)</label>
                    <input id="price" name="price" type="text" class="karsource-input number-input" placeholder="e.g., 5,500,000" required value="{{ old('price', $car['fields']['Price'] ?? '') }}" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>
            </div>
            <div>
                <label for="additional_info" class="block text-sm font-semibold text-gray-700 mb-2">Additional Information</label>
                <textarea id="additional_info" name="additional_info" class="karsource-textarea" rows="4" placeholder="Any additional details about the car...">{{ old('additional_info', $car['fields']['Additional Info'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('additional_info')" class="mt-2" />
            </div>
            
            <!-- Current Images Display -->
            @if(!empty($car['fields']['Images']))
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                        @foreach($car['fields']['Images'] as $img)
                            <div class="relative group">
                                <img src="{{ is_array($img) ? $img['url'] : $img }}" alt="Car Image" class="w-full h-24 object-cover rounded-lg border-2 border-gray-200" />
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Current Images Display -->
            @if(!empty($car['fields']['Images']))
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($car['fields']['Images'] as $index => $image)
                    <div class="relative border-2 rounded-lg overflow-hidden {{ $index === 0 ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <div class="aspect-square relative">
                            <img src="{{ $image['url'] }}" alt="Car Image {{ $index + 1 }}" class="w-full h-full object-cover">
                            @if($index === 0)
                            <div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">MAIN</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Visual Upload Interface for New Images -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Replace Images (Optional)</label>
                
                <!-- Upload Area -->
                <div id="uploadArea" class="karsource-file-upload cursor-pointer" onclick="document.getElementById('images').click()">
                    <input id="images" name="images[]" type="file" class="hidden" accept="image/*" multiple />
                    <div id="uploadHelper" class="file-upload-helper text-center">
                        <i class="fas fa-refresh text-4xl text-blue-600 mb-3"></i>
                        <p class="text-gray-700 font-medium text-lg">Upload New Images</p>
                        <p class="text-sm text-gray-500 mb-2">Drag and drop or click to replace current images</p>
                        <p class="text-xs text-gray-400">First new image will be the main display image</p>
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current images</p>
                    </div>
                </div>
                
                <!-- Image Preview Grid -->
                <div id="imagePreviewGrid" class="hidden mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">New Images Preview</h3>
                        <button type="button" id="clearImages" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Clear All
                        </button>
                    </div>
                    <div id="previewContainer" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="text-sm font-medium">New images will completely replace all current images</span>
                        </div>
                    </div>
                </div>
                
                <x-input-error :messages="$errors->get('images')" class="mt-2" />
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">Update Car</button>
                <a href="{{ route('admin.cars.index') }}" class="text-center sm:text-left py-3 px-6 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Number formatting for price and mileage
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    
    function unformatNumber(str) {
        return str.replace(/,/g, '');
    }
    
    // Handle number inputs with comma formatting
    document.querySelectorAll('.number-input').forEach(input => {
        // Format existing value
        if (input.value) {
            input.value = formatNumber(unformatNumber(input.value));
        }
        
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            let cursorPosition = e.target.selectionStart;
            let unformatted = unformatNumber(value);
            
            // Only allow numbers
            if (!/^\d*$/.test(unformatted)) {
                unformatted = unformatted.replace(/\D/g, '');
            }
            
            let formatted = unformatted ? formatNumber(unformatted) : '';
            let lengthDiff = formatted.length - value.length;
            
            e.target.value = formatted;
            
            // Adjust cursor position
            e.target.setSelectionRange(cursorPosition + lengthDiff, cursorPosition + lengthDiff);
        });
        
        // Remove commas before form submission
        input.form.addEventListener('submit', function() {
            input.value = unformatNumber(input.value);
        });
    });
    
    // Visual Upload Interface
    let selectedFiles = [];
    let mainImageIndex = 0;
    
    const fileInput = document.getElementById('images');
    const uploadArea = document.getElementById('uploadArea');
    const uploadHelper = document.getElementById('uploadHelper');
    const previewGrid = document.getElementById('imagePreviewGrid');
    const previewContainer = document.getElementById('previewContainer');
    const clearButton = document.getElementById('clearImages');
    
    // File input change handler
    if (fileInput) {
        fileInput.addEventListener('change', handleFiles);
    }
    
    // Clear button handler
    if (clearButton) {
        clearButton.addEventListener('click', clearAllImages);
    }
    
    function handleFiles(e) {
        const files = Array.from(e.target.files);
        selectedFiles = files;
        mainImageIndex = 0; // First image is main by default
        displayPreviews();
    }
    
    function displayPreviews() {
        if (selectedFiles.length === 0) {
            if (previewGrid) previewGrid.classList.add('hidden');
            if (uploadHelper) uploadHelper.classList.remove('hidden');
            return;
        }
        
        if (uploadHelper) uploadHelper.classList.add('hidden');
        if (previewGrid) previewGrid.classList.remove('hidden');
        if (previewContainer) previewContainer.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = createPreviewDiv(e.target.result, file.name, index);
                if (previewContainer) previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }
    
    function createPreviewDiv(src, filename, index) {
        const div = document.createElement('div');
        div.className = 'relative group border-2 rounded-lg overflow-hidden transition-all duration-200 hover:shadow-lg';
        div.classList.add(index === mainImageIndex ? 'border-blue-500 bg-blue-50' : 'border-gray-200');
        
        div.innerHTML = `
            <div class="aspect-square relative">
                <img src="${src}" alt="${filename}" class="w-full h-full object-cover">
                ${index === mainImageIndex ? 
                    '<div class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">MAIN</div>' : 
                    '<button type="button" class="absolute top-2 left-2 bg-white bg-opacity-90 hover:bg-blue-600 hover:text-white text-gray-700 px-2 py-1 rounded text-xs font-medium transition-colors" onclick="setAsMain(' + index + ')">Set as Main</button>'
                }
                <button type="button" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs transition-colors" onclick="removeImage(' + index + ')">
                    <i class="fas fa-times"></i>
                </button>
                <div class="absolute bottom-2 left-2 right-2">
                    <div class="bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded truncate">
                        ${filename}
                    </div>
                </div>
            </div>
        `;
        
        return div;
    }
    
    window.setAsMain = function(index) {
        mainImageIndex = index;
        reorderFiles();
        displayPreviews();
    };
    
    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        if (mainImageIndex >= index && mainImageIndex > 0) {
            mainImageIndex--;
        }
        updateFileInput();
        displayPreviews();
    };
    
    function reorderFiles() {
        // Move main image to first position
        if (mainImageIndex > 0) {
            const mainFile = selectedFiles[mainImageIndex];
            selectedFiles.splice(mainImageIndex, 1);
            selectedFiles.unshift(mainFile);
            mainImageIndex = 0;
        }
        updateFileInput();
    }
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        if (fileInput) fileInput.files = dt.files;
    }
    
    function clearAllImages() {
        selectedFiles = [];
        mainImageIndex = 0;
        if (fileInput) fileInput.value = '';
        displayPreviews();
    }
});
</script>
@endsection 