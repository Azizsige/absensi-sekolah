<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveRequestController extends Controller
{
    public function index()
    {
        // Tarik data izin, urutkan dari yang terbaru, sekalian join data siswa & kelas
        $leaveRequests = LeaveRequest::with(['student.classroom.major'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/LeaveRequest/Index', [
            'leaveRequests' => $leaveRequests
        ]);
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        // Validasi inputan status dari frontend
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // 1. Update status di tabel leave_requests jadi 'approved' atau 'rejected'
        $leaveRequest->update(['status' => $validated['status']]);

        // 2. MAGIC AUTOMATION: Kalau disetujui, otomatis isi absen!
        if ($validated['status'] === 'approved') {

            // Ubah string tanggal jadi object Carbon
            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);

            // Bikin rentang hari (misal: 12 Okt sampai 14 Okt)
            $period = CarbonPeriod::create($startDate, $endDate);

            // Looping per hari di dalam rentang tersebut
            foreach ($period as $date) {
                // updateOrCreate ngecek: Kalau tanggal ini udah ada absen, timpa aja. Kalau belum, bikin baru.
                Attendance::updateOrCreate(
                    [
                        'student_id' => $leaveRequest->student_id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => $leaveRequest->type, // Otomatis ngisi 'leave' atau 'sick'
                        // check_in & check_out biarin null karena dia nggak masuk
                    ]
                );
            }
        }

        // Kembali ke halaman sebelumnya tanpa refresh berkat Inertia
        return redirect()->back();
    }
}
