@php
    $rawStatus = $get('journey_status');

    $status = is_string($rawStatus) ? $rawStatus : null;

    $statusLabel = $status
        ? ucwords(str_replace('_', ' ', $status))
        : '';

    $steps = [
        ['key' => 'sfl', 'label' => 'SFL', 'number' => 1],
        ['key' => 'underwriting', 'label' => 'Underwriting', 'number' => 2],
        ['key' => 'approved', 'label' => 'Approved', 'number' => 3],
        ['key' => 'sanctioned', 'label' => 'Sanctioned', 'number' => 4],
    ];

    $currentStep = match ($status) {
        'sfl' => 1,
        'underwriting' => 2,
        'approved' => 3,
        'sanctioned' => 4,
        default => 0,
    };

    $isRejected = $status === 'not_approved';

    $reasonText = match ($status) {
        'sfl' => 'Customer file is currently at SFL stage.',
        'underwriting' => 'File is under bank / underwriting review.',
        'approved' => 'Loan is approved and ready for sanction processing.',
        'sanctioned' => 'Loan has been sanctioned successfully.',
        'not_approved' => 'Loan was not approved.',
        default => 'Select a journey status to see progress.',
    };

    $progressWidth = match ($currentStep) {
        1 => '12%',
        2 => '42%',
        3 => '72%',
        4 => '100%',
        default => '0%',
    };

    $badgeClasses = match ($status) {
        'not_approved' => 'bg-red-100 text-red-700',
        'sanctioned' => 'bg-green-100 text-green-700',
        'approved' => 'bg-blue-100 text-blue-700',
        'underwriting' => 'bg-yellow-100 text-yellow-700',
        'sfl' => 'bg-gray-100 text-gray-700',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Customer Loan Journey</h3>
            <p class="text-sm text-gray-500">{{ $reasonText }}</p>
        </div>

        @if ($status)
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                {{ $statusLabel }}
            </span>
        @endif
    </div>

    @if ($isRejected)
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                    !
                </div>

                <div>
                    <div class="font-semibold text-red-700">Journey Stopped</div>
                    <div class="text-sm text-red-600">
                        Customer loan journey is marked as <strong>Not Approved</strong>.
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="relative pt-2">
            <div class="absolute left-0 right-0 top-7 h-1 rounded bg-gray-200"></div>

            <!-- <div
                class="absolute left-0 top-7 h-1 rounded bg-primary-600 transition-all duration-300"
                style="width: {{ $progressWidth }};"
            ></div> -->

            <div class="relative grid grid-cols-4 gap-4">
                @foreach ($steps as $step)
                    @php
                        $done = $currentStep >= $step['number'];
                    @endphp

                    <div class="flex flex-col items-center text-center">
                        <div
                            class="z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-bold {{ $done ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 bg-white text-gray-500' }}"
                        >
                            {{ $step['number'] }}
                        </div>

                        <div class="mt-3 text-sm font-medium {{ $done ? 'text-gray-900' : 'text-gray-500' }}">
                            {{ $step['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>