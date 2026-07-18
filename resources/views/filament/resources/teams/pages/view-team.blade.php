<x-filament-panels::page>

    <x-filament::section>

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold">
                    {{ $this->record->emp_name }}
                </h2>

                <p class="text-gray-500">
                    {{ \App\Models\Employee::designationOptions()[$this->record->designation] }}
                </p>
            </div>

            <div class="text-right">
                <div><strong>ID:</strong> {{ $this->record->emp_id }}</div>
                <div><strong>Mobile:</strong> {{ $this->record->mobile }}</div>
                <div><strong>Email:</strong> {{ $this->record->email }}</div>
            </div>

        </div>

    </x-filament::section>

    <div class="mt-6">
        {{ $this->table }}
    </div>

</x-filament-panels::page>