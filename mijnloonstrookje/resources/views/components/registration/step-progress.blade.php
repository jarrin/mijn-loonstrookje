@props([
    'currentStep' => 1,
    'totalSteps' => 3,
    'steps' => [],
    'showPaymentStep' => true
])

@php
    // Default steps if not provided
    $defaultSteps = [
        ['label' => 'Maak account', 'number' => 1],
        ['label' => 'Verifieer & beveilig', 'number' => 2],
    ];
    
    if ($showPaymentStep) {
        $defaultSteps[] = ['label' => 'Betalen', 'number' => 3];
    }
    
    $steps = count($steps) > 0 ? $steps : $defaultSteps;
@endphp

<div class="flex items-center justify-center mb-10">
    @foreach($steps as $index => $step)
        @php
            $stepNumber = $step['number'] ?? ($index + 1);
            $isCompleted = $currentStep > $stepNumber;
            $isActive = $currentStep == $stepNumber;
            $isUpcoming = $currentStep < $stepNumber;
        @endphp
        
        <!-- Step -->
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center
                {{ $isCompleted ? 'bg-blue-500 text-white' : '' }}
                {{ $isActive ? 'bg-blue-500 text-white ring-4 ring-blue-100' : '' }}
                {{ $isUpcoming ? 'bg-white border-2 border-gray-200 text-gray-400' : '' }}">
                @if($isCompleted)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @else
                    {{ $stepNumber }}
                @endif
            </div>
            <p class="mt-2 text-sm 
                {{ $isCompleted ? 'font-medium text-gray-700' : '' }}
                {{ $isActive ? 'font-semibold text-blue-500' : '' }}
                {{ $isUpcoming ? 'font-medium text-gray-400' : '' }}">
                {{ $step['label'] }}
            </p>
        </div>
        
        <!-- Connector line (except for last step) -->
        @if($index < count($steps) - 1)
            <div class="w-24 h-0.5 mx-2 -mt-6 {{ $currentStep > $stepNumber ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
        @endif
    @endforeach
</div>
