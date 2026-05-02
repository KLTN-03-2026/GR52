<?php

namespace App\Http\Controllers;

use App\Models\HoSoUngTuyen;
use App\Models\ViTriTuyenDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HoSoUngTuyenController extends Controller
{
    // Candidate applies for a job
    public function nopHoSo(Request $request, ViTriTuyenDung $viTriTuyenDung)
    {
        $ungVien = Auth::guard('ung_vien')->user();

        if (!$ungVien) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn cần đăng nhập để nộp hồ sơ.'
            ], 401);
        }

        // Check if candidate has already applied for this position
        $existingApplication = HoSoUngTuyen::where('ung_vien_id', $ungVien->id)
            ->where('vi_tri_tuyen_dung_id', $viTriTuyenDung->id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn đã nộp hồ sơ cho vị trí này rồi.'
            ], 409); // 409 Conflict
        }

        $request->validate([
            'file_cv'          => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'ghi_chu_ung_vien' => 'nullable|string|max:1000',
        ]);

        $cvPath = null;
        if ($request->hasFile('file_cv')) {
            $cvPath = $request->file('file_cv')->store('ung_tuyen_cvs', 'public');
        }

        $application = HoSoUngTuyen::create([
            'ung_vien_id'          => $ungVien->id,
            'vi_tri_tuyen_dung_id' => $viTriTuyenDung->id,
            'file_cv'              => $cvPath,
            'ghi_chu_ung_vien'     => $request->ghi_chu_ung_vien,
            'ngay_ung_tuyen'       => now(),
            'trang_thai'           => 0, // Đang chờ
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Nộp hồ sơ thành công!',
            'data'    => $application->load('viTriTuyenDung', 'ungVien')
        ], 201);
    }

    // Candidate views their own applications
    public function getUngVienHoSoUngTuyen(Request $request)
    {
        $ungVien = Auth::guard('ung_vien')->user();

        if (!$ungVien) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn cần đăng nhập để xem hồ sơ ứng tuyển.'
            ], 401);
        }

        $applications = HoSoUngTuyen::where('ung_vien_id', $ungVien->id)
            ->with('viTriTuyenDung.phongBan', 'viTriTuyenDung.chucVu')
            ->orderBy('ngay_ung_tuyen', 'desc')
            ->paginate(15);

        return response()->json($applications);
    }

    // HR views applications for a specific job
    public function getHoSoUngTuyenByViTri(Request $request, ViTriTuyenDung $viTriTuyenDung)
    {
        if (!Auth::guard('sanctum')->check()) { // Assuming 'sanctum' guard for HR/Admin
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $applications = HoSoUngTuyen::where('vi_tri_tuyen_dung_id', $viTriTuyenDung->id)
            ->with('ungVien')
            ->orderBy('ngay_ung_tuyen', 'desc')
            ->paginate(15);

        return response()->json($applications);
    }

    // HR views all applications (global management)
    public function getAllApplications(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $applications = HoSoUngTuyen::with([
            'viTriTuyenDung.phongBan',
            'viTriTuyenDung.chucVu',
            'ungVien'
        ])->orderBy('ngay_ung_tuyen', 'desc')->get();

        return response()->json([
            'status' => true,
            'data'   => $applications
        ]);
    }

    // HR downloads a CV
    public function downloadCv(HoSoUngTuyen $hoSoUngTuyen)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$hoSoUngTuyen->file_cv || !Storage::disk('public')->exists($hoSoUngTuyen->file_cv)) {
            return response()->json(['message' => 'Tệp CV không tồn tại.'], 404);
        }

        return response()->download(storage_path('app/public/' . $hoSoUngTuyen->file_cv), 'CV_' . $hoSoUngTuyen->ungVien->ho_ten . '_' . $hoSoUngTuyen->viTriTuyenDung->tieu_de . '.' . pathinfo($hoSoUngTuyen->file_cv, PATHINFO_EXTENSION));
    }

    // HR previews a CV (inline - for iframe display)
    public function previewCv(HoSoUngTuyen $hoSoUngTuyen)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$hoSoUngTuyen->file_cv || !Storage::disk('public')->exists($hoSoUngTuyen->file_cv)) {
            return response()->json(['message' => 'Tệp CV không tồn tại.'], 404);
        }

        $cvPath = storage_path('app/public/' . $hoSoUngTuyen->file_cv);
        $ext = strtolower(pathinfo($hoSoUngTuyen->file_cv, PATHINFO_EXTENSION));

        if (!file_exists($cvPath)) {
            return response()->json(['message' => 'Tệp CV không tồn tại.'], 404);
        }

        // Determine MIME type
        $mimeType = 'application/octet-stream';
        if ($ext === 'pdf') {
            $mimeType = 'application/pdf';
        } elseif ($ext === 'doc') {
            $mimeType = 'application/msword';
        } elseif ($ext === 'docx') {
            $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        return response()->file($cvPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="CV_' . $hoSoUngTuyen->ungVien->ho_ten . '.' . $ext . '"'
        ]);
    }

    // HR updates application status
    public function updateTrangThai(Request $request, HoSoUngTuyen $hoSoUngTuyen)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'trang_thai' => ['required', 'integer', Rule::in([0, 1, 2, 3])], // 0: Đang chờ, 1: Đã xem, 2: Đã duyệt, 3: Từ chối
        ]);

        $hoSoUngTuyen->update(['trang_thai' => $request->trang_thai]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật trạng thái hồ sơ thành công.',
            'data'    => $hoSoUngTuyen
        ]);
    }
}
