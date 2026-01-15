<x-layouts.app :title="__('Doctors')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Success Message --}}
        @if(session('success'))
            <div id="flash-message"
                 class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-6">

                {{-- ADD NEW DOCTOR --}}
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        Add New Doctor
                    </h2>

                    <form action="{{ route('doctors.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Doctor Specialization
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       placeholder="e.g. Cardiologist"
                                       required
                                       class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                                              focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                              dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Description
                                </label>
                                <textarea name="description" rows="1"
                                          placeholder="e.g. Heart / Circulation"
                                          class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                                                 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                                 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white
                                           transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                Add Doctor
                            </button>
                        </div>
                    </form>
                </div>

                {{-- DOCTOR LIST --}}
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        Doctor List
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                        #
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                        Doctor Specialization
                                    </th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                        Description
                                    </th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($doctors as $doctor)
                                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                        <td class="px-4 py-3 text-center text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-4 py-3 text-left text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $doctor->name }}
                                        </td>

                                        <td class="px-4 py-3 text-left text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $doctor->description ?? '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- EDIT --}}
                                                <button
                                                    onclick="editDoctor({{ $doctor->id }}, '{{ $doctor->name }}', '{{ addslashes($doctor->description) }}')"
                                                    class="rounded-md border border-blue-500 px-3 py-1 text-xs font-medium
                                                           text-blue-600 transition
                                                           hover:bg-blue-50 hover:text-blue-700
                                                           dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-900/30">
                                                    Edit
                                                </button>

                                                {{-- DELETE --}}
                                                <form action="{{ route('doctors.destroy', $doctor) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Delete this doctor permanently?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="rounded-md border border-red-500 px-3 py-1 text-xs font-medium
                                                               text-red-600 transition
                                                               hover:bg-red-50 hover:text-red-700
                                                               dark:border-red-400 dark:text-red-400 dark:hover:bg-red-900/30">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-4 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No doctors found. Add your first doctor above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editDoctorModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

        <div class="w-full max-w-2xl rounded-xl border border-neutral-200 bg-white p-6
                    dark:border-neutral-700 dark:bg-neutral-800">

            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Edit Doctor
            </h2>

            <form id="editDoctorForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Doctor Specialization
                        </label>
                        <input id="edit_doctor_name" name="name" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                                      dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Description
                        </label>
                        <textarea id="edit_description" name="description" rows="3"
                                  class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                                         dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                            class="rounded-lg border border-neutral-300 px-4 py-2 text-sm
                                   dark:border-neutral-600 dark:text-neutral-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white
                                   hover:bg-blue-700">
                        Update Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editDoctor(id, name, description) {
            document.getElementById('editDoctorModal').classList.remove('hidden');
            document.getElementById('editDoctorModal').classList.add('flex');
            document.getElementById('editDoctorForm').action = `/doctors/${id}`;
            document.getElementById('edit_doctor_name').value = name;
            document.getElementById('edit_description').value = description || '';
        }

        function closeEditModal() {
            document.getElementById('editDoctorModal').classList.add('hidden');
            document.getElementById('editDoctorModal').classList.remove('flex');
        }
    </script>
</x-layouts.app>
