<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Carbon\Carbon;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with search and filters.
     */
    public function index(Request $request)
    {
        $query = Patient::with('doctor');

        // Search by Name or Email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by Status (New Logic)
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        // Filter by Doctor
        if ($request->filled('doctor_filter') && $request->doctor_filter !== 'all') {
            $query->where('doctor_id', $request->doctor_filter);
        }

        // UPDATED: Changed ->get() to ->paginate(10)->withQueryString()
        $patients = $query->oldest()->paginate(10)->withQueryString();
        
        $doctors = Doctor::all();
        $activeDoctors = Doctor::count();

        return view('dashboard', compact('patients', 'doctors', 'activeDoctors'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email'         => 'required|email|unique:patients,email',
            'phone_number'  => 'required|string|max:15',
            'address'       => 'required|string|max:500',
            'doctor_id'     => 'required|exists:doctors,id',
            'status'        => 'required|in:active,pending,discharged', // Status Validation
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('patient_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Patient::create($validated);

        return redirect()->back()->with('success', 'Patient added successfully.');
    }

    /**
     * Update an existing patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email'         => 'required|email|unique:patients,email,' . $patient->id,
            'phone_number'  => 'required|string|max:15',
            'address'       => 'required|string|max:500',
            'doctor_id'     => 'required|exists:doctors,id',
            'status'        => 'required|in:active,pending,discharged', // Status Validation
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($patient->photo) {
                Storage::disk('public')->delete($patient->photo);
            }
            $photoPath = $request->file('photo')->store('patient_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $patient->update($validated);

        return redirect()->back()->with('success', 'Patient updated successfully.');
    }

    /**
     * Soft delete a patient (move to trash).
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->back()->with('success', 'Patient successfully moved to trash.');
    }

    /**
     * Display trashed patients.
     */
    public function trash()
    {
        $patients = Patient::onlyTrashed()->with('doctor')->latest('deleted_at')->get();
        $doctors = Doctor::all();
        $activeDoctors = Doctor::count();

        return view('trash', compact('patients', 'doctors', 'activeDoctors'));
    }

    /**
     * Restore a deleted patient.
     */
    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->back()->with('success', 'Patient restored successfully.');
    }

    /**
     * Permanently delete a patient.
     */
    public function forcedelete($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        if ($patient->photo) {
            Storage::disk('public')->delete($patient->photo);
        }
        $patient->forceDelete();

        return redirect()->back()->with('success', 'Patient permanently deleted successfully.');
    }

    /**
     * Export patients to PDF with status column.
     */
    public function export(Request $request)
    {
        $query = Patient::with('doctor');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        if ($request->filled('doctor_filter') && $request->doctor_filter !== 'all') {
            $query->where('doctor_id', $request->doctor_filter);
        }

        $patients = $query->oldest()->get();

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Patients Report</title>
            <style>
                body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1f2937; margin: 20px; }
                h1 { text-align: center; color: #1e3a8a; margin-bottom: 5px; }
                .subtitle { text-align: center; font-size: 12px; color: #64748b; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #eff6ff; color: #1e3a8a; padding: 8px; border: 1px solid #bfdbfe; text-align: left; }
                td { padding: 8px; border: 1px solid #e5e7eb; vertical-align: top; }
                .status-active { color: #059669; font-weight: bold; }
                .status-pending { color: #d97706; font-weight: bold; }
                .status-discharged { color: #6b7280; font-weight: bold; }
            </style>
        </head><body>
            <h1>Patients Report</h1>
            <div class="subtitle">CareSync Management System</div>
            <p><strong>Generated:</strong> ' . date('F d, Y h:i A') . ' | <strong>Total:</strong> ' . $patients->count() . '</p>
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Name</th><th>Status</th><th>DOB</th><th>Email</th><th>Phone</th><th>Doctor</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($patients as $index => $patient) {
            $statusClass = 'status-' . $patient->status;
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($patient->name) . '</td>
                <td class="' . $statusClass . '">' . ucfirst($patient->status) . '</td>
                <td>' . ($patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->format('Y-m-d') : '—') . '</td>
                <td>' . htmlspecialchars($patient->email) . '</td>
                <td>' . htmlspecialchars($patient->phone_number) . '</td>
                <td>' . htmlspecialchars($patient->doctor ? $patient->doctor->name : '—') . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('patients_report_' . date('Y-m-d') . '.pdf');
    }
}