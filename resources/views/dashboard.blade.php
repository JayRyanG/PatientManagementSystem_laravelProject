<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">


        {{-- Success Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif


        <!-- Stats Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Patients</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $patients->count() }}</h3>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Active Doctors</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{$activeDoctors}}</h3>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Hospital Rate</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">94%</h3>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Management Section -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-6">
                <!-- Add New Patient Form -->
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Patient</h2>

                    
                    <form action="{{ route('patients.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
                        @csrf


                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter patient name" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Date of Birth</label>
                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('dateOfBirth') ? \Carbon\Carbon::parse(old('dateOfBirth'))->format('Y-m-d') : '' }}"
                                placeholder="Enter Date of Birth"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('dateOfBirth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Phone</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter phone number" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="Enter address"  required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('address')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        
                        {{-- NEW: DOCTOR DROPDOWN --}}
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Doctor Specializations</label>
                            <select name="doctor_id" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 white:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                <option value="">Select a doctor specializations</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                           @error ('doctor_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                Add Patient
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Patient List Table -->
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Patient List</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Date of Birth</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Email</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Phone</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Address</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Doctor Specialization</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($patients as $patient)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->name }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->date_of_birth }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->email }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->phone_number }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->address }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->doctor->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">
                                            <button onclick="editPatient({{ $patient->id }},'{{ $patient->name }}','{{ $patient->date_of_birth }}','{{ $patient->email }}','{{ $patient->phone_number }}','{{ $patient->address }}',{{ $patient->doctor_id ?? 'null' }})" class="text-blue-600 hover:shadow-lg hover:scale-100 transition-all">Edit</button>
                                            <span class="mx-2 text-neutral-400">|</span>
                                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline" onsubmit="return Showconfirm('Are you sure you want to delete this patient?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No patient found. Add your first patient above!
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

    {{-- EDIT PATIENT MODAL --}}
    <div id="editPatientModal" class="fixed inset-0 hidden flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-neutral-900">EDIT PATIENT PROFILE</h2>
            </div>
            <form id="editPatientForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Name</label>
                        <input type="text" name="name" id="editName" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="editDateOfBirth" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Email</label>
                        <input type="email" name="email" id="editEmail" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Phone</label>
                        <input type="tel" name="phone_number" id="editPhoneNumber" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Address</label>
                        <input type="text" name="address" id="editAddress" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div></div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-neutral-700">Doctor Specialization</label>
                        <select name="doctor_id" id="editDoctorId" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-black focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Select a doctor specialization</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700">Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function editPatient(id, name, dateOfBirth, email, phoneNumber, address, doctorId) {
            document.getElementById('editName').value = name;
            document.getElementById('editDateOfBirth').value = dateOfBirth;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhoneNumber').value = phoneNumber;
            document.getElementById('editAddress').value = address;
            document.getElementById('editDoctorId').value = doctorId;
    
            const form = document.getElementById('editPatientForm');
            form.action = `/patients/${id}`;
    
            document.getElementById('editPatientModal').classList.remove('hidden');
        }
    
        function closeEditModal() {
            document.getElementById('editPatientModal').classList.add('hidden');
        }
    
        function Showconfirm(message) {
            return window.confirm(message);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const flashMessage = document.getElementById('flash-message');
    
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
            
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
</x-layouts.app>
