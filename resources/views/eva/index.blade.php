<x-layouts.app :title="__('Earned Value Analysis')">
    <div class="max-w-xl mx-auto mt-10 space-y-6">

        <form action="{{ route('eva.calculate') }}" method="POST">
            @csrf

            <x-currency
                name="bac"
                label="Total Budget Proyek (BAC)"
                prefix="Rp"
                thousands="."
                decimal=","
                placeholder="Contoh: 50.000.000"
                required
            />

            <x-currency
                name="ac"
                label="Actual Cost (AC)"
                prefix="Rp"
                thousands="."
                decimal=","
                placeholder="Contoh: 28.000.000"
                required
            />

           <x-input
    name="progress"
    label="Progress Proyek Saat Ini (%)"
    type="text"
    inputmode="decimal"
    step="0.01"
    placeholder="Contoh: 25.39"
    required
/>

            <x-button type="submit" positive label="Hitung EVA" class="mt-4" />
        </form>
    </div>
    <x-errors />
</x-layouts.app>
