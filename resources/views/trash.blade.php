<x-layouts.app :title="__('Trash')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Success Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-lg bg-orange-100 p-4 text-orange-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="relative overflow-hidden rounded-xl p-6 bg-gradient-to-r from-orange-500 to-rose-500 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Trashed Records</p>
                        <h3 class="mt-2 text-3xl font-bold">{{ $patients->count() }}</h3>
                    </div>
                    <div class="rounded-full bg-white/20 p-3 ring-1 ring-white/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-center rounded-xl border-2 border-dashed border-neutral-300 dark:border-neutral-700 p-6">
                <p class="text-sm text-neutral-500 text-center uppercase tracking-widest font-bold italic">
                    Records in trash are removed from main reports but kept for 30 days.
                </p>
            </div>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-rose-200 bg-white dark:border-rose-900/30 dark:bg-neutral-800">
            <div class="flex flex-col p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Deleted Patients Repository</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead>
                            <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-neutral-500 dark:text-neutral-400">Photo</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Deleted Date</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse($patients as $patient)
                                <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $loop->iteration }}</td>
                                    
                                    {{-- Patient Photo --}}
                                    <td class="px-4 py-3">
                                        @if($patient->photo)
                                            <img src="{{ Storage::url($patient->photo) }}" 
                                                 class="h-10 w-10 rounded-full object-cover ring-2 ring-rose-100 dark:ring-rose-900/50">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-700 text-xs font-semibold text-neutral-400">
                                                {{ strtoupper(substr($patient->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="font-medium text-neutral-900 dark:text-neutral-100">{{ $patient->name }}</div>
                                        <div class="text-xs text-neutral-500">{{ $patient->email }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400 whitespace-nowrap">
                                        {{ $patient->deleted_at->format('M d, Y • h:i A') }}
                                    </td>
                                    
                                    {{-- Actions Column --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-3">
                                            <form action="{{ route('patients.restore', $patient->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-1 rounded-lg bg-teal-500/10 px-3 py-1.5 text-xs font-bold text-teal-600 hover:bg-teal-500 hover:text-white transition-all">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                    Restore
                                                </button>
                                            </form>

                                            <form action="{{ route('patients.force-delete', $patient->id) }}" method="POST" onsubmit="return confirm('WARNING: This action cannot be undone. Delete forever?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-500 hover:text-white transition-all">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Permanently Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="h-16 w-16 bg-neutral-100 dark:bg-neutral-700 rounded-full flex items-center justify-center mb-4 text-neutral-400">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                            <p class="text-neutral-500 font-medium">Trash is empty. Your data is safe!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide success message
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('flash-message');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }
        });
    </script>
</x-layouts.app>