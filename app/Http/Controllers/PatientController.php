<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('doctor')->oldest()->get(); // Eager load the associated doctor
        $doctors = Doctor::all();
        $activeDoctors = Doctor::count();


        return view('dashboard', compact('patients','doctors','activeDoctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:patients,email',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        Patient::create($validated);

        return redirect()->back()->with('success', 'Patient added successfully.');
    }
    
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $patient->update($validated);

        return redirect()->back()->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->back()->with('success', 'Patient deleted successfully.');
    }
}
