<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-4">
            {{-- Bagian Logo Inisial Nama --}}
            <div class="flex items-center justify-center w-12 h-12 text-xl font-bold text-white rounded-full bg-primary-600">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            
            {{-- Bagian Teks Sapaan --}}
            <div>
                <h2 class="text-xl font-bold tracking-tight sm:text-2xl">
                    Selamat Datang, {{ ucwords(auth()->user()->name) }}! 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Selamat bekerja di Dhiarfa Akrilik. Gunakan menu di samping untuk memantau kehadiran dan mengunduh slip gaji Anda bulan ini.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>