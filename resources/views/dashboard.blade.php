<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Success Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="relative overflow-hidden rounded-xl p-6
                        bg-gradient-to-r from-blue-500 to-cyan-500
                        text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">
                            Total Patients
                        </p>
                        <h3 class="mt-2 text-3xl font-bold">
                            {{ $patients->total() }}
                        </h3>
                    </div>
                    <div class="rounded-full bg-white/20 p-3 ring-1 ring-white/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-1a4 4 0 00-5-3.87M9 20H2v-1a4 4 0 015-3.87
                                M15 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl p-6
                        bg-gradient-to-r from-emerald-500 to-teal-500
                        text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">
                            Active Doctors
                        </p>
                        <h3 class="mt-2 text-3xl font-bold">
                            {{ $activeDoctors }}
                        </h3>
                    </div>
                    <div class="rounded-full bg-white/20 p-3 ring-1 ring-white/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v12m6-6H6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl p-6
                        bg-gradient-to-r from-purple-500 to-indigo-500
                        text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">
                            Hospital Rate
                        </p>
                        <h3 class="mt-2 text-3xl font-bold">
                            94%
                        </h3>
                    </div>
                    <div class="rounded-full bg-white/20 p-3 ring-1 ring-white/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 13h3l2-5 4 10 2-5h3" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col p-6 gap-6">

                <div class= "mb-4 flex justify-end">
                    <form method="GET" action="{{ route('patients.export') }}" class="inline">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="doctor_filter" value="{{ request('doctor_filter') }}">
                        <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                        
                        <button type="submit"
                                class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-5.618 2.337M12 2.944a11.955 11.955 0 01-5.618-.737M8.788 3a3.777 3.777 0 00-.688-.688M3.333 3a3.777 3.777 0 01-.688-.688M3.333,3 a3.777,3.777,0,0,1,.688-.688z"/>
                            </svg>
                            Export to PDF
                        </button>
                    </form>
                </div>
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Patient</h2>

                    
                    <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                        @csrf


                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="ex. Juan Dela Cruz" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Date of Birth</label>
                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth') ? \Carbon\Carbon::parse(old('date_of_birth'))->format('Y-m-d') : '' }}"
                                placeholder="Enter Date of Birth"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="ex. juandelacruz@gmail.com" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Phone</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="ex. 09123456789" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="ex. 123 Street., Brgy. Urban, Roxas City"  required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('address')
                                <p class="mt-1 text-xs text-red-600"> {{ $message }} </p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Doctor Specializations</label>
                            <select name="doctor_id" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 white:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                <option value="">Select a doctor specializations</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }} | {{ $doctor->description }} </option>
                                @endforeach
                            </select>
                            @error ('doctor_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Status</label>
                            <select name="status" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 white:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                <option value="pending" selected>Pending</option>
                                <option value="active">Active</option>
                                <option value="discharged">Discharged</option>
                            </select>
                            @error ('status')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Patient Photo (Optional)
                    </label>
                    <input
                        type="file"
                        name="photo"
                        accept="image/jpeg,image/png,image/jpg"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                            file:mr-4 file:rounded-md file:border-0 file:bg-blue-50
                            file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700
                            hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800
                            dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                    >
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        JPG, PNG or JPEG. Max 2MB.
                    </p>
                    @error('photo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white
                            transition-colors hover:bg-blue-700 focus:outline-none
                            focus:ring-2 focus:ring-blue-500/20"
                    >
                        Add Patient
                    </button>
                </div>
                </form>
                </div>

                <div class="rounded-lg border border-neutral-700 bg-neutral-900/50 p-6">
                    <h2 class="mb-4 text-lg font-semibold text-white">Search & Filter Patients</h2>
                    <form action="{{ route('dashboard') }}" method="GET" class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Search
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by name or email"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2
                                    text-sm focus:border-blue-500 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500/20
                                    dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                        </div>

                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Filter by Doctor
                            </label>
                            <select
                                name="doctor_filter"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2
                                    text-sm focus:border-blue-500 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500/20
                                    dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option
                                        value="{{ $doctor->id }}"
                                        {{ request('doctor_filter') == $doctor->id ? 'selected' : '' }}
                                    >
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Filter by Status
                            </label>
                            <select
                                name="status_filter"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2
                                    text-sm focus:border-blue-500 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500/20
                                    dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                                <option value="all">All Statuses</option>
                                <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="discharged" {{ request('status_filter') == 'discharged' ? 'selected' : '' }}>Discharged</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2 md:col-span-1">
                            <button
                                type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2
                                    text-sm font-medium text-white transition-colors
                                    hover:bg-blue-700"
                            >
                                Apply Filters
                            </button>
                            <a
                                href="{{ route('dashboard') }}"
                                class="rounded-lg border border-neutral-300 px-4 py-2
                                    text-sm font-medium text-neutral-700 transition-colors
                                    hover:bg-neutral-100 dark:border-neutral-600
                                    dark:text-neutral-300 dark:hover:bg-neutral-700"
                            >
                                Clear
                            </a>
                        </div>
                    </form>
                </div>


                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-lg font-semibold p-2 text-neutral-900 dark:text-neutral-100">Patient List</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Photo</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Status</th>
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
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ ($patients->currentPage() - 1) * $patients->perPage() + $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            @if($patient->photo)
                                                <img
                                                    src="{{ Storage::url($patient->photo) }}"
                                                    alt="{{ $patient->name }}"
                                                    class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900"
                                                >
                                            @else
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ strtoupper(substr($patient->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300 whitespace-nowrap">{{ $patient->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span @class([
                                                'px-3 py-1 rounded-full text-xs font-medium border',
                                                'bg-green-900/40 text-green-400 border-green-700' => $patient->status === 'active',
                                                'bg-yellow-900/40 text-yellow-400 border-yellow-700' => $patient->status === 'pending',
                                                'bg-gray-800 text-gray-400 border-gray-600' => $patient->status === 'discharged',
                                            ])>
                                                {{ ucfirst($patient->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300 whitespace-nowrap">{{ $patient->date_of_birth }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->email }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $patient->phone_number }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300 whitespace-nowrap">{{ $patient->address }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300 whitespace-nowrap">
                                             @if($patient->doctor)
                                                    <div class="font-medium">
                                                        {{ $patient->doctor->name }}
                                                    </div>
                                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                        {{ $patient->doctor->specialization ?? $patient->doctor->description }}
                                                    </div>
                                                @else
                                                    <span class="text-neutral-400">N/A</span>
                                                @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">
                                            <div class="flex items-center gap-2 whitespace-nowrap">

                                                <button
                                                    onclick="editPatient({{ $patient->id }},'{{ $patient->name }}','{{ $patient->date_of_birth }}','{{ $patient->email }}','{{ $patient->phone_number }}','{{ $patient->address }}',{{ $patient->doctor_id ?? 'null'}}, '{{ $patient->status }}', '{{ $patient->photo }}')"
                                                    class="rounded-md border border-blue-500/50 bg-blue-500/10 px-3 py-1 text-xs font-medium text-blue-400
                                                        transition-all hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600">
                                                    Edit
                                                </button>

                                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this patient?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="rounded-md border border-red-500/50 bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400
                                                            transition-all hover:bg-red-500 hover:text-white dark:hover:bg-red-600">
                                                        Discharge
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No patient found. Add your first patient above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- ADDED: Pagination links here --}}
                    <div class="mt-4 px-4">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT PATIENT MODAL --}}
    <div id="editPatientModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60">

        <div class="w-full max-w-2xl rounded-xl border border-neutral-200
                    bg-white p-6 shadow-xl
                    dark:border-neutral-700 dark:bg-neutral-800">

            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Edit Patient Profile
            </h2>

            <form id="editPatientForm" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1 text-sm font-medium">Name</label>
                    <input id="editName" name="name"
                        class="w-full rounded-lg bg-neutral-800 px-4 py-2 text-white border dark:bg-neutral-700">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">Date of Birth</label>
                    <input id="editDateOfBirth" type="date" name="date_of_birth"
                        class="w-full rounded-lg bg-neutral-800 px-4 py-2 text-white border dark:bg-neutral-700">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">Email</label>
                    <input id="editEmail" type="email" name="email"
                        class="w-full rounded-lg bg-neutral-800 px-4 py-2 text-white border dark:bg-neutral-700">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">Phone</label>
                    <input id="editPhoneNumber" name="phone_number"
                        class="w-full rounded-lg bg-neutral-800 px-4 py-2 text-white border dark:bg-neutral-700">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">Address</label>
                    <input id="editAddress" name="address"
                        class="w-full rounded-lg bg-neutral-800 px-4 py-2 text-white border dark:bg-neutral-700">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">Doctor</label>
                    <select id="editDoctorId" name="doctor_id"
                            class="w-full rounded-lg border px-4 py-2 dark:bg-neutral-700">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }} | {{ $doctor->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block mb-1 text-sm font-medium">Status</label>
                    <select id="editStatus" name="status"
                            class="w-full rounded-lg border px-4 py-2 dark:bg-neutral-700">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="discharged">Discharged</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Patient Photo</label>
                </div>

                <div id="currentPhotoPreview" class="md: flex items-center gap-3 rounded-lg border border-neutral-700 bg-nuetral-800 p-3">
                    <p class="text-sm text-neutral-400">
                        No Photo uploaded
                    </p>
                </div>

                <div class="flex items-center rounded-lg border border-neutral-700 bg-neutral-800 px-3 py-2">
                    <input
                    type="file"
                    id="editPhoto"
                    name="photo"
                    accept="image/jpeg,image/png,image/jpg"
                    class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm file:mr-4 file:rounded-mb file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400">
                </div>

                <div>
                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                    JPG, PNG or JPEG. Max 2MB.
                </p>

                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            onclick="closeEditPatientModal()"
                            class="rounded-lg border px-4 py-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white">
                        Update Patient
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function editPatient(id, name, date_of_birth, email, phone_number, address, doctorId, status, photo) {
            const modal = document.getElementById('editPatientModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            document.getElementById('editPatientForm').action = `/patients/${id}`;

            document.getElementById('editName').value = name;
            document.getElementById('editDateOfBirth').value = date_of_birth;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhoneNumber').value = phone_number;
            document.getElementById('editAddress').value = address;
            document.getElementById('editDoctorId').value = doctorId ?? '';
            document.getElementById('editStatus').value = status;

            const preview = document.getElementById('currentPhotoPreview');

            const hasPhoto =
                photo !== null &&
                photo !== '' &&
                photo !== 'null' &&
                photo !== undefined;

            if (hasPhoto) {
                preview.innerHTML = `
                <div class="flex justify-center">
                    <div class="flex items-center gap-4 rounded-lg border border-neutral-700 bg-neutral-800 px-6 py-4">
                        <img src="/storage/${photo}"
                            class="h-14 w-14 rounded-full object-cover ring-2 ring-blue-100">
                        <div>
                            <p class="text-sm text-white">Current Photo</p>
                            <p class="text-xs text-neutral-400">Upload new photo to replace</p>
                        </div>
                    </div>
                </div>
                `;
            } else {
                preview.innerHTML = `
                    <div class="rounded-lg border border-dashed border-neutral-600 bg-neutral-800 p-4 text-center">
                        <p class="text-sm text-neutral-400">No photo uploaded</p>
                    </div>
                `;
            }
        }


        function closeEditPatientModal() {
            document.getElementById('editPatientModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('flash-message');

        if (toast) {
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 3000); // 3 seconds
        }
    });
    </script>
</x-layouts.app>