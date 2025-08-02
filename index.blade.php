@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-car mr-3 text-blue-600"></i>
                            Car Management
                        </h1>
                        <p class="text-gray-600 mt-1">Manage your car inventory and listings</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('admin.cars.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center shadow-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Car
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
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

        <!-- Cars Grid -->
        @if(isset($cars['records']) && count($cars['records']) > 0)
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-car text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Cars</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($cars['records']) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Brand New</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ collect($cars['records'])->where('fields.Condition', 'Brand New')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-globe text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Foreign Used</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ collect($cars['records'])->where('fields.Condition', 'Foreign Used')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-2xl text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Local Used</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ collect($cars['records'])->where('fields.Condition', 'Local Used')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Car Condition Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                        Inventory by Condition
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Distribution of car conditions in inventory</p>
                    <div class="relative h-64">
                        <canvas id="conditionChart"></canvas>
                    </div>
                </div>

                <!-- Price Range Analysis -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                        Price Range Distribution
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Car inventory by price categories</p>
                    <div class="relative h-64">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>

                <!-- Year Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                        Cars by Manufacturing Year
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Inventory distribution across model years</p>
                    <div class="relative h-64">
                        <canvas id="yearChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cars List -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">All Listings</h2>
                </div>
                
                <div class="grid gap-6 p-6">
                    @foreach($cars['records'] as $car)
                        <div class="border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                    <!-- Car Info -->
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $car['fields']['Name'] ?? 'Unnamed Car' }}</h3>
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Year</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ $car['fields']['Year'] ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Mileage</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ isset($car['fields']['Mileage']) ? number_format($car['fields']['Mileage']) . ' km' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Transmission</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ $car['fields']['Transmission'] ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Fuel Type</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ $car['fields']['Fuel Type'] ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Engine Size</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ isset($car['fields']['Engine Size']) ? $car['fields']['Engine Size'] . 'L' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Horsepower</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ isset($car['fields']['Horsepower']) ? $car['fields']['Horsepower'] . ' HP' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                                        <p class="text-sm font-medium text-gray-900">{{ $car['fields']['Location'] ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Price</p>
                                                        <p class="text-2xl font-bold text-blue-600">₦{{ number_format($car['fields']['Price'] ?? 0) }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                                            @if($car['fields']['Condition'] === 'Brand New') bg-green-100 text-green-800
                                                            @elseif($car['fields']['Condition'] === 'Foreign Used') bg-blue-100 text-blue-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ $car['fields']['Condition'] ?? 'Unknown' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Car Image -->
                                            @if(isset($car['fields']['Images']) && count($car['fields']['Images']) > 0)
                                                <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0">
                                                    <img src="{{ $car['fields']['Images'][0]['url'] }}" 
                                                         alt="{{ $car['fields']['Name'] ?? 'Car Image' }}" 
                                                         class="w-full sm:w-32 h-24 object-cover rounded-lg">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col sm:flex-row lg:flex-col gap-3">
                                        <a href="{{ route('admin.cars.edit', $car['id']) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-md text-sm font-medium transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.cars.destroy', $car['id']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md text-sm font-medium transition-colors" 
                                                    onclick="return confirm('Are you sure you want to delete this car listing?')">
                                                <i class="fas fa-trash mr-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                        <a href="{{ route('cars.show', $car['id']) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white hover:bg-gray-700 rounded-md text-sm font-medium transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View
                                        </a>
                                    </div>
                                </div>
                                
                                @if(isset($car['fields']['Additional Info']) && $car['fields']['Additional Info'])
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Additional Information</p>
                                        <p class="text-sm text-gray-700">{{ $car['fields']['Additional Info'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-car text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No cars found</h3>
                        <p class="text-gray-600 mb-6">Get started by adding your first car listing to your inventory.</p>
                        <a href="{{ route('admin.cars.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Your First Car
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from Laravel
    const carsData = @json($cars['records'] ?? []);
    
    // KarSource brand colors
    const colors = {
        primary: '#2b4c7e',
        light: '#4a90e2',
        accent: '#f39c12',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        purple: '#8b5cf6'
    };

    // Handle empty data gracefully
    if (carsData.length === 0) {
        ['conditionChart', 'priceChart', 'yearChart'].forEach(chartId => {
            const canvas = document.getElementById(chartId);
            const ctx = canvas.getContext('2d');
            const parent = canvas.parentElement;
            
            // Hide canvas and show empty state
            canvas.style.display = 'none';
            parent.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                    <p class="text-sm">No data available</p>
                    <p class="text-xs">Add cars to see analytics</p>
                </div>
            `;
        });
        return;
    }

    // 1. Condition Distribution Chart (Doughnut)
    const conditionData = {};
    carsData.forEach(car => {
        const condition = car.fields.Condition || 'Unknown';
        conditionData[condition] = (conditionData[condition] || 0) + 1;
    });

    new Chart(document.getElementById('conditionChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(conditionData),
            datasets: [{
                data: Object.values(conditionData),
                backgroundColor: [colors.success, colors.primary, colors.warning, colors.danger],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { size: 12, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const total = Object.values(conditionData).reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return `${context.label}: ${context.raw} cars (${percentage}%)`;
                        }
                    }
                }
            },
            interaction: {
                intersect: false
            }
        }
    });

    // 2. Smart Dynamic Price Range Distribution
    function createIntelligentPriceRanges(cars) {
        const prices = cars.map(car => parseInt(car.fields.Price) || 0).filter(p => p > 0).sort((a, b) => a - b);
        
        if (prices.length === 0) {
            return { ranges: {}, labels: [], colors: [] };
        }

        const minPrice = prices[0];
        const maxPrice = prices[prices.length - 1];
        const totalCars = prices.length;

        // Target: 5-6 ranges with balanced distribution
        const targetRanges = Math.min(6, Math.max(4, Math.ceil(totalCars / 2)));
        
        // Create percentile-based breakpoints
        const percentiles = [];
        for (let i = 1; i < targetRanges; i++) {
            const percentile = i / targetRanges;
            const index = Math.floor(percentile * prices.length);
            percentiles.push(prices[index]);
        }

        // Round breakpoints to business-friendly numbers (₦5M increments)
        const roundToBusinessFriendly = (price) => {
            if (price < 10000000) return Math.round(price / 2500000) * 2500000; // ₦2.5M increments below ₦10M
            if (price < 30000000) return Math.round(price / 5000000) * 5000000;  // ₦5M increments
            if (price < 100000000) return Math.round(price / 10000000) * 10000000; // ₦10M increments
            return Math.round(price / 25000000) * 25000000; // ₦25M increments for ultra-luxury
        };

        const breakpoints = percentiles.map(roundToBusinessFriendly);
        
        // Remove duplicates and ensure proper ordering
        const uniqueBreakpoints = [...new Set([minPrice, ...breakpoints, maxPrice])].sort((a, b) => a - b);

        // Generate clean range labels and separate market context
        const getCleanLabel = (lower, upper, isLast) => {
            const lowerM = lower / 1000000;
            const upperM = upper / 1000000;
            
            if (isLast) {
                return `Above ₦${lowerM}M`;
            }
            return `₦${lowerM}M - ₦${upperM}M`;
        };

        const getMarketContext = (lower, upper, isLast) => {
            const lowerM = lower / 1000000;
            const upperM = upper / 1000000;
            
            if (isLast) {
                if (lowerM >= 80) return 'Ultra-Exotic';
                if (lowerM >= 50) return 'Ultra-Luxury';
                if (lowerM >= 30) return 'High-End Luxury';
                return 'Premium Plus';
            }
            
            if (upperM <= 15) return 'Budget-Premium';
            else if (upperM <= 25) return 'Premium Entry';
            else if (upperM <= 40) return 'Luxury';
            else if (upperM <= 60) return 'High-End';
            else return 'Ultra-Luxury';
        };

        // Create ranges object with clean labels and market context
        const ranges = {};
        const labels = [];
        const marketContexts = {};
        const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#6366f1'];

        for (let i = 0; i < uniqueBreakpoints.length - 1; i++) {
            const lower = uniqueBreakpoints[i];
            const upper = uniqueBreakpoints[i + 1];
            const isLast = i === uniqueBreakpoints.length - 2;
            const cleanLabel = getCleanLabel(lower, upper, isLast);
            const marketContext = getMarketContext(lower, upper, isLast);
            
            labels.push(cleanLabel);
            ranges[cleanLabel] = 0;
            marketContexts[cleanLabel] = marketContext;
        }

        // Count cars in each range
        prices.forEach(price => {
            for (let i = 0; i < uniqueBreakpoints.length - 1; i++) {
                const lower = uniqueBreakpoints[i];
                const upper = uniqueBreakpoints[i + 1];
                const isLast = i === uniqueBreakpoints.length - 2;
                
                if ((isLast && price >= lower) || (price >= lower && price < upper)) {
                    const cleanLabel = getCleanLabel(lower, upper, isLast);
                    ranges[cleanLabel]++;
                    break;
                }
            }
        });

        return { ranges, labels, marketContexts, colors: colors.slice(0, labels.length) };
    }

    const priceRangeData = createIntelligentPriceRanges(carsData);
    const priceRanges = priceRangeData.ranges;

    // Handle empty price data
    if (Object.keys(priceRanges).length === 0) {
        const priceCanvas = document.getElementById('priceChart');
        const priceParent = priceCanvas.parentElement;
        priceCanvas.style.display = 'none';
        priceParent.innerHTML = `
            <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-2"></i>
                <p class="text-sm">No price data available</p>
                <p class="text-xs">Add cars with prices to see distribution</p>
            </div>
        `;
    } else {
        // Create dynamic gradient colors for each range
        const ctx2 = document.getElementById('priceChart').getContext('2d');
    const gradients = priceRangeData.colors.map((color, index) => {
        const gradient = ctx2.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, color);
        
        // Create darker shade for gradient end
        const rgb = {
            r: parseInt(color.slice(1, 3), 16),
            g: parseInt(color.slice(3, 5), 16),
            b: parseInt(color.slice(5, 7), 16)
        };
        const darkerColor = `rgb(${Math.max(0, rgb.r - 40)}, ${Math.max(0, rgb.g - 40)}, ${Math.max(0, rgb.b - 40)})`;
        gradient.addColorStop(1, darkerColor);
        
        return gradient;
    });

    new Chart(document.getElementById('priceChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(priceRanges),
            datasets: [{
                label: 'Cars',
                data: Object.values(priceRanges),
                backgroundColor: gradients,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#4a90e2',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return context[0].label; // Clean price range
                        },
                        label: function(context) {
                            const total = Object.values(priceRanges).reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                            const segment = priceRangeData.marketContexts[context.label] || 'Market Segment';
                            return [
                                `${context.raw} cars (${percentage}%)`,
                                `Segment: ${segment}`
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: { size: 12 }
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                y: {
                    ticks: {
                        font: { size: 12, weight: 'bold' }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    } // End of price chart else block

    // 3. Year Distribution (Enhanced Area Chart)
    const yearData = {};
    carsData.forEach(car => {
        const year = car.fields.Year || 'Unknown';
        yearData[year] = (yearData[year] || 0) + 1;
    });

    // Sort years and handle unknown
    const knownYears = Object.keys(yearData)
        .filter(year => year !== 'Unknown' && !isNaN(year))
        .sort();
    
    // Fill missing years for better visualization
    if (knownYears.length > 1) {
        const minYear = parseInt(knownYears[0]);
        const maxYear = parseInt(knownYears[knownYears.length - 1]);
        for (let year = minYear; year <= maxYear; year++) {
            if (!yearData[year.toString()]) {
                yearData[year.toString()] = 0;
            }
        }
    }
    
    const finalYears = Object.keys(yearData)
        .filter(year => year !== 'Unknown' && !isNaN(year))
        .sort()
        .concat(yearData['Unknown'] ? ['Unknown'] : []);

    // Create gradient for area fill
    const ctx3 = document.getElementById('yearChart').getContext('2d');
    const areaGradient = ctx3.createLinearGradient(0, 0, 0, 200);
    areaGradient.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
    areaGradient.addColorStop(1, 'rgba(139, 92, 246, 0.05)');

    new Chart(document.getElementById('yearChart'), {
        type: 'line',
        data: {
            labels: finalYears,
            datasets: [{
                label: 'Cars',
                data: finalYears.map(year => yearData[year] || 0),
                borderColor: colors.purple,
                backgroundColor: areaGradient,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: colors.purple,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: colors.purple,
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const total = finalYears.reduce((sum, year) => sum + (yearData[year] || 0), 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return `${context.raw} cars (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: '#f3f4f6',
                        borderDash: [2, 4]
                    },
                    ticks: {
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: { size: 12 }
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: colors.accent
                }
            }
        }
    });
});
</script>

@endsection 