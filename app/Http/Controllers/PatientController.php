<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('doctor');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('doctor_filter') && $request->doctor_filter !== 'all') {
            $query->where('doctor_id', $request->doctor_filter);
        }

        $patients = $query->latest()->get();
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
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:patients,email',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'doctor_id' => 'required|exists:doctors,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('patient_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Patient::create($validated);

        return redirect()->back()->with('success', 'Patient added successfully.');
    }

    /**
     * Update an existing patient
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'doctor_id' => 'required|exists:doctors,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
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
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->back()->with('success', 'Patient successfully moved   to trash.');
    }

    public function trash()
    {
        $patients = Patient::onlyTrashed()->with('doctor')->latest('deleted_at')->get();
        $doctors = Doctor::all();
        $activeDoctors = Doctor::count();

        return view('trash', compact('patients', 'doctors', 'activeDoctors'));
    }

    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->back()->with('success', 'Patient restored successfully.');
    }

    public function forcedelete($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        // Delete photo if exists
        if ($patient->photo) {
            Storage::disk('public')->delete($patient->photo);
        }
        $patient->forceDelete();

        return redirect()->back()->with('success', 'Patient permanently deleted successfully.');
    }

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

        if ($request->filled('doctor_filter') && $request->doctor_filter !== 'all') {
            $query->where('doctor_id', $request->doctor_filter);
        }

        $patients = $query->latest()->get();

        $filename = 'patients_export_' . date('Y-m-d_His') . '.pdf';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Patients Export</title>
            <style>
                body {
                    font-family: DejaVu Sans, Arial, sans-serif;
                    font-size: 11px;
                    color: #1f2937;
                    margin: 40px;
                    background-color: #ffffff;
                }

                h1 {
                    font-size: 24px;
                    text-align: center;
                    margin-bottom: 4px;
                    color: #1e3a8a;
                    letter-spacing: 0.5px;
                }

                .subtitle {
                    text-align: center;
                    font-size: 12px;
                    color: #64748b;
                    margin-bottom: 20px;
                }

                .header-line {
                    width: 60px;
                    height: 3px;
                    background-color: #3b82f6;
                    margin: 0 auto 25px auto;
                    border-radius: 2px;
                }

                .meta {
                    width: 100%;
                    margin-bottom: 20px;
                    font-size: 11px;
                    color: #374151;
                }

                .meta td {
                    padding: 4px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                thead th {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                    padding: 9px 6px;
                    text-align: left;
                    background-color: #eff6ff;
                    color: #1e3a8a;
                    border-bottom: 2px solid #bfdbfe;
                }

                tbody td {
                    padding: 9px 6px;
                    border-bottom: 1px solid #e5e7eb;
                    vertical-align: top;
                }

                tbody tr:nth-child(even) {
                    background-color: #f9fafb;
                }

                .doctor-name {
                    font-weight: bold;
                    color: #1e40af;
                }

                .doctor-desc {
                    font-size: 10px;
                    color: #64748b;
                }

                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #e5e7eb;
                    text-align: right;
                    font-size: 10px;
                    color: #64748b;
                }
            </style>
        </head>
        <body>

            <h1>Patients Report</h1>
            <div class="subtitle">CareSync Management System</div>

            <div class="meta">
                <div>Generated: ' . date('F d, Y • h:i A') . '</div>
                <div>Total Records: ' . $patients->count() . '</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Doctor</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>';


             $number = 1;
            foreach ($patients as $patient) {
                $html .= '<tr>
                    <td>' . $number++ . '</td>
                    <td>' . htmlspecialchars($patient->name) . '</td>
                    <td>' . (
                        $patient->date_of_birth
                            ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d')
                            : '—') . '</td>
                    <td>' . htmlspecialchars($patient->email) . '</td>
                    <td>' . htmlspecialchars($patient->phone_number) . '</td>
                    <td>' . htmlspecialchars($patient->address) . '</td>
                    <td>' . htmlspecialchars($patient->doctor ? $patient->doctor->name : '—') . '</td>
                    <td>' . $patient->created_at->format('Y-m-d') . '</td>
                </tr>';
            }

            $html .= '</tbody>
                </table>

                <div class="footer">
                    Total Patients: ' . $patients->count() . '
                </div>
            </body>
        </html>';

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('patients.pdf');

    }
}
