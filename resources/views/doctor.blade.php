<x-layouts.app :title="__('Doctors')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Success Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add New Doctor Form Section --}}
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-6 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Doctor</h2>
            
            <form action="{{ route('doctors.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-1">
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Doctor Specialization</label>
                    <input type="text" name="name" placeholder="e.g. Dr. Juan Dela Cruz | Cardiologist" required 
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                </div>

                <div class="md:col-span-1">
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Description</label>
                    <input type="text" name="description" placeholder="e.g. Heart / Circulation" required 
                           class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        Add Doctor
                    </button>
                </div>
            </form>
        </div>

        {{-- Colorful Doctor Grid Section --}}
        <div class="mt-4">
            <h2 class="mb-6 text-xl font-bold text-neutral-900 dark:text-neutral-100 px-2">Medical Staff Directory</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($doctors as $doctor)
                    @php
                        // Assigning different colors based on loop index for variety
                        $colors = [
                            'from-blue-500 to-cyan-400', 
                            'from-purple-500 to-indigo-400', 
                            'from-emerald-500 to-teal-400', 
                            'from-orange-500 to-amber-400', 
                            'from-rose-500 to-pink-400'
                        ];
                        $bgGradient = $colors[$loop->index % count($colors)];
                    @endphp

                    <div class="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-white transition-all hover:shadow-xl dark:border-neutral-700 dark:bg-neutral-900/50">
                        {{-- Top Decorative Bar --}}
                        <div class="h-2 w-full bg-gradient-to-r {{ $bgGradient }}"></div>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br {{ $bgGradient }} text-lg font-bold text-white shadow-lg shadow-blue-500/20">
                                    {{ strtoupper(substr($doctor->name, 4, 1)) }} {{-- Takes first letter after "Dr. " --}}
                                </div>
                                <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-500 border border-emerald-500/20">
                                    In-Clinic
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-100 group-hover:text-blue-500 transition-colors">
                                {{ $doctor->name }}
                            </h3>
                            
                            <p class="mt-1 text-sm font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-tight">
                                {{ $doctor->description }}
                            </p>

                            <div class="mt-6 flex items-center gap-3 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                                <button onclick="editDoctor({{ $doctor->id }}, '{{ $doctor->name }}', '{{ $doctor->description }}')" 
                                        class="flex-1 rounded-lg border border-neutral-200 py-2 text-xs font-semibold text-neutral-600 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800">
                                    Edit Profile
                                </button>
                                
                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to remove this doctor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-lg bg-red-500/10 py-2 text-xs font-semibold text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 dark:bg-neutral-800 dark:text-neutral-600 mb-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <p class="text-neutral-500">No medical staff found. Add your first doctor above.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Edit Doctor Modal --}}
    <div id="editDoctorModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-4 text-lg font-bold text-neutral-900 dark:text-neutral-100">Edit Doctor Information</h2>
            <form id="editDoctorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium">Doctor Name / Specialization</label>
                        <input id="editDoctorName" name="name" class="w-full rounded-lg bg-neutral-50 border border-neutral-300 px-4 py-2 dark:bg-neutral-700 dark:border-neutral-600">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Description</label>
                        <input id="editDoctorDescription" name="description" class="w-full rounded-lg bg-neutral-50 border border-neutral-300 px-4 py-2 dark:bg-neutral-700 dark:border-neutral-600">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditDoctorModal()" class="px-4 py-2 text-neutral-500 hover:text-neutral-700">Cancel</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editDoctor(id, name, description) {
            document.getElementById('editDoctorModal').classList.remove('hidden');
            document.getElementById('editDoctorForm').action = `/doctors/${id}`;
            document.getElementById('editDoctorName').value = name;
            document.getElementById('editDoctorDescription').value = description;
        }

        function closeEditDoctorModal() {
            document.getElementById('editDoctorModal').classList.add('hidden');
        }

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