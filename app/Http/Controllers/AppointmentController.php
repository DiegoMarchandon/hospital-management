<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Appointment Controller
 * 
 * Handles appointment booking and cancellation for patients.
 */
class AppointmentController extends Controller
{
    /**
     * Show appointment booking form
     *
     * @return View
     */
    public function showBookForm(): View
    {
        $doctors = Doctor::with('specialty')->get();
        $schedules = Schedule::with('doctor')->get();
        
        return view('appointments.book', [
            'doctors' => $doctors,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Book a new appointment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function book(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:500',
        ]);

        $patient = Patient::where('email', Auth::user()->email)->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient profile not found.');
        }

        try {
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $patient->id,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'] ?? null,
                'status' => 'pending',
            ]);

            return redirect()->route('dashboard')->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }

    /**
     * Cancel an appointment
     *
     * @param Appointment $appointment
     * @return RedirectResponse
     */
    public function cancel(Appointment $appointment): RedirectResponse
    {
        // Verify ownership
        $patient = Patient::where('email', Auth::user()->email)->first();

        if (!$patient || $appointment->patient_id !== $patient->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        try {
            $appointment->update(['status' => 'cancelled']);
            return redirect()->route('dashboard')->with('success', 'Appointment cancelled successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel appointment: ' . $e->getMessage());
        }
    }

    /**
     * Confirm an appointment (doctor only)
     *
     * @param Appointment $appointment
     * @return RedirectResponse
     */
    public function confirm(Appointment $appointment): RedirectResponse
    {
        // Verify doctor ownership
        $doctor = Doctor::where('email', Auth::user()->email)->first();

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        try {
            $appointment->update(['status' => 'confirmed']);
            return redirect()->route('dashboard')->with('success', 'Appointment confirmed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to confirm appointment: ' . $e->getMessage());
        }
    }

    /**
     * Complete an appointment (doctor only)
     *
     * @param Appointment $appointment
     * @return RedirectResponse
     */
    public function complete(Appointment $appointment): RedirectResponse
    {
        // Verify doctor ownership
        $doctor = Doctor::where('email', Auth::user()->email)->first();

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        try {
            $appointment->update(['status' => 'completed']);
            return redirect()->route('dashboard')->with('success', 'Appointment marked as completed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to complete appointment: ' . $e->getMessage());
        }
    }
