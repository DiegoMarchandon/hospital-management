<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dashboard Controller
 * 
 * Handles the display of role-based dashboards for authenticated users.
 * Routes users to their respective dashboard based on their role (admin, doctor, patient).
 * 
 * @method void middleware(string $middleware)
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is handled in routes
    }

    /**
     * Show dashboard based on user role
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('doctor')) {
            return $this->doctorDashboard();
        } elseif ($user->hasRole('patient')) {
            return $this->patientDashboard();
        }

        return redirect('/');
    }

    /**
     * Admin dashboard - overview of hospital management
     *
     * @return \Illuminate\View\View
     */
    private function adminDashboard(): View
    {
        $stats = [
            'doctors' => Doctor::count(),
            'patients' => Patient::count(),
            'appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
        ];

        $recent_appointments = Appointment::with(['doctor', 'patient', 'schedule'])
            ->latest()
            ->take(10)
            ->get();

        $doctors = Doctor::with('specialty')->get();
        $patients = Patient::get();

        return view('dashboard.admin', [
            'stats' => $stats,
            'recent_appointments' => $recent_appointments,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    /**
     * Doctor dashboard - schedule and appointments
     *
     * @return \Illuminate\View\View
     */
    private function doctorDashboard(): View
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        
        if (!$doctor) {
            // If logged in doctor doesn't have a profile, create a minimal view or redirect
            return view('dashboard.doctor', [
                'doctor' => null,
                'upcoming_appointments' => collect(),
                'schedule' => collect(),
                'stats' => ['total_appointments' => 0, 'pending_appointments' => 0, 'completed_appointments' => 0],
            ]);
        }

        $upcoming_appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', '!=', 'cancelled')
            ->with(['patient', 'schedule'])
            ->orderBy('appointment_date')
            ->get();

        $schedule = $doctor->schedules()->get();

        $stats = [
            'total_appointments' => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->count(),
            'completed_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('dashboard.doctor', [
            'doctor' => $doctor,
            'upcoming_appointments' => $upcoming_appointments,
            'schedule' => $schedule,
            'stats' => $stats,
        ]);
    }

    /**
     * Patient dashboard - appointments and medical records
     *
     * @return \Illuminate\View\View
     */
    private function patientDashboard(): View
    {
        $patient = Patient::where('email', Auth::user()->email)->first();

        if (!$patient) {
            // If logged in patient doesn't have a profile, show empty dashboard
            return view('dashboard.patient', [
                'patient' => null,
                'appointments' => collect(),
                'medical_records' => collect(),
                'stats' => ['total_appointments' => 0, 'pending_appointments' => 0, 'completed_appointments' => 0, 'medical_records' => 0],
            ]);
        }

        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.specialty', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $medical_records = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor'])
            ->latest()
            ->get();

        $stats = [
            'total_appointments' => $appointments->count(),
            'pending_appointments' => $appointments->where('status', 'pending')->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'medical_records' => $medical_records->count(),
        ];

        return view('dashboard.patient', [
            'patient' => $patient,
            'appointments' => $appointments,
            'medical_records' => $medical_records,
            'stats' => $stats,
        ]);
    }
}
