@php
    $status = $status ?? null;

    $steps = [
        ['key' => 'sfl', 'label' => 'SFL'],
        ['key' => 'underwriting', 'label' => 'Underwriting'],
        ['key' => 'approved', 'label' => 'Approved'],
        ['key' => 'sanctioned', 'label' => 'Sanctioned'],
    ];

    $currentStep = match ($status) {
        'sfl' => 1,
        'underwriting' => 2,
        'approved' => 3,
        'sanctioned' => 4,
        default => 0,
    };

    $progress = match ($currentStep) {
        1 => 0,
        2 => 33,
        3 => 66,
        4 => 100,
        default => 0,
    };

    $statusLabel = $status
        ? ucwords(str_replace('_', ' ', $status))
        : 'Pending';

    $badgeClasses = match ($status) {
        'sanctioned' => 'bg-green-100 text-green-700',
        'approved' => 'bg-blue-100 text-blue-700',
        'underwriting' => 'bg-yellow-100 text-yellow-700',
        'sfl' => 'bg-gray-100 text-gray-700',
        'not_approved' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-600',
    };
@endphp

<div class="sticky top-4 z-50 rounded-xl border border-gray-200 bg-white shadow-lg">

    <div class="flex items-center justify-between border-b px-6 py-4">

        <div>
            <h2 class="text-lg font-bold">
                Customer Loan Journey
            </h2>

            <p class="text-sm text-gray-500">
                Current Stage : {{ $statusLabel }}
            </p>
        </div>

        <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $badgeClasses }}">
            {{ $statusLabel }}
        </span>

    </div>

    <div class="p-8">

        {{-- Progress Bar --}}
        <div class="relative mb-10">

            <div class="h-2 rounded-full bg-gray-200">

                <div
                    class="h-2 rounded-full bg-primary-600 transition-all duration-700"
                    style="width: {{ $progress }}%;"
                ></div>

            </div>

        </div>

        {{-- Steps --}}
        <div class="grid grid-cols-4 gap-4">

            @foreach($steps as $step)

                @php
                    $active = $currentStep >= array_search($step['key'], array_column($steps,'key')) + 1;
                    $current = $currentStep == array_search($step['key'], array_column($steps,'key')) + 1;
                @endphp

                <div class="text-center">

                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full border-4 font-bold

                        {{ $active ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 bg-white text-gray-500' }}

                        {{ $current ? 'ring-4 ring-primary-100' : '' }}
                        "
                    >

                        @if($active)

                            ✓

                        @else

                            {{ array_search($step['key'], array_column($steps,'key')) + 1 }}

                        @endif

                    </div>

                    <div class="mt-3 text-sm font-semibold">

                        {{ $step['label'] }}

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    <div class="grid grid-cols-4 border-t bg-gray-50">

        <div class="p-4 text-center">
            <div class="text-xs text-gray-500">Current</div>
            <div class="font-semibold">{{ $statusLabel }}</div>
        </div>

        <div class="p-4 text-center">
            <div class="text-xs text-gray-500">Progress</div>
            <div class="font-semibold">{{ $progress }}%</div>
        </div>

        <div class="p-4 text-center">
            <div class="text-xs text-gray-500">Completed</div>
            <div class="font-semibold">{{ $currentStep }}/4</div>
        </div>

        <div class="p-4 text-center">
            <div class="text-xs text-gray-500">Status</div>
            <div class="font-semibold">
                {{ $status == 'sanctioned' ? 'Completed' : 'In Progress' }}
            </div>
        </div>

    </div>

</div>