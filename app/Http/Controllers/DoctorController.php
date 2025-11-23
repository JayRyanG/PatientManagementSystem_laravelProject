<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::oldest()->get();

        return view('doctor', compact('doctors'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Doctor::create($validated);
        return redirect()->back()->with('success', 'Doctor added successfully.');
    }
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $doctor->update($validated);
        return redirect()->back()->with('success', 'Doctor updated successfully.');
    }
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->back()->with('success', 'Doctor deleted successfully.');
    }
}