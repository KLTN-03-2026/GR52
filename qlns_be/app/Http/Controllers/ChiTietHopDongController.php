<?php

namespace App\Http\Controllers;

use App\Models\ChiTietHopDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChiTietHopDongController extends Controller
{
    public function index(Request $request)
    {
        $query = ChiTietHopDong::with(['nhanVien', 'loaiHopDong']);
        if ($request->filled('nhan_vien')) {
            $query->where('id_nhan_vien', $request->nhan_vien);
        }
        return response()->json([
            'status' => true,
            'data' => $query->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->filled('id_nhan_vien') && $request->filled('id')) {
            $request->merge(['id_nhan_vien' => $request->id]);
        }

        $validated = $request->validate([
            'id_nhan_vien'     => 'required|exists:nhan_viens,id',
            'id_loai_hop_dong' => 'required|exists:loai_hop_dongs,id',
            'noi_dung'         => 'nullable|string',
            'ngay_ky'          => 'required|date',
            'ngay_bat_dau'     => 'required|date',
            'ngay_ket_thuc'    => 'nullable|date|after:ngay_bat_dau',
        ]);

        $hopDong = ChiTietHopDong::create($validated + ['trang_thai' => 'nhap']);
        $this->storePdf($hopDong);

        return response()->json([
            'status' => true,
            'message' => 'Tạo hợp đồng thành công.',
            'data' => $hopDong->load(['nhanVien', 'loaiHopDong']),
        ], 201);
    }

    public function show(ChiTietHopDong $chiTietHopDong)
    {
        return response()->json($chiTietHopDong->load(['nhanVien', 'loaiHopDong']));
    }

    public function update(Request $request, ChiTietHopDong $chiTietHopDong)
    {
        $chiTietHopDong->update($request->validate([
            'noi_dung'      => 'nullable|string',
            'id_loai_hop_dong' => 'nullable|exists:loai_hop_dongs,id',
            'ngay_ky' => 'nullable|date',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
        ]));
        $this->storePdf($chiTietHopDong);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật hợp đồng thành công.',
            'data' => $chiTietHopDong->fresh()->load(['nhanVien', 'loaiHopDong']),
        ]);
    }

    public function destroy(ChiTietHopDong $chiTietHopDong)
    {
        $chiTietHopDong->delete();
        return response()->json(['message' => 'Đã xóa hợp đồng']);
    }

    public function adminSign(Request $request, ChiTietHopDong $chiTietHopDong)
    {
        $validated = $request->validate([
            'chu_ky_admin' => 'required|string|max:100',
        ]);

        $chiTietHopDong->update([
            'chu_ky_admin' => $validated['chu_ky_admin'],
            'ngay_admin_ky' => now(),
            'trang_thai' => 'cho_nhan_vien_ky',
        ]);
        $this->storePdf($chiTietHopDong);

        return response()->json([
            'status' => true,
            'message' => 'Admin đã ký và gửi hợp đồng cho nhân viên.',
            'data' => $chiTietHopDong->fresh()->load(['nhanVien', 'loaiHopDong']),
        ]);
    }

    public function nhanVienSign(Request $request, ChiTietHopDong $chiTietHopDong)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || (int) $chiTietHopDong->id_nhan_vien !== (int) $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền ký hợp đồng này.',
            ], 403);
        }

        if (!$chiTietHopDong->chu_ky_admin) {
            return response()->json([
                'status' => false,
                'message' => 'Hợp đồng chưa được admin ký.',
            ], 422);
        }

        $validated = $request->validate([
            'chu_ky_nhan_vien' => 'required|string|max:100',
        ]);

        $chiTietHopDong->update([
            'chu_ky_nhan_vien' => $validated['chu_ky_nhan_vien'],
            'ngay_nhan_vien_ky' => now(),
            'trang_thai' => 'hoan_tat',
        ]);
        $this->storePdf($chiTietHopDong);

        return response()->json([
            'status' => true,
            'message' => 'Bạn đã ký xác nhận hợp đồng.',
            'data' => $chiTietHopDong->fresh()->load(['nhanVien', 'loaiHopDong']),
        ]);
    }

    public function exportPdf(Request $request, ChiTietHopDong $chiTietHopDong)
    {
        if ($request->is('api/nhan-vien/*')) {
            $user = Auth::guard('sanctum')->user();
            if (!$user || (int) $chiTietHopDong->id_nhan_vien !== (int) $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền xem hợp đồng này.',
                ], 403);
            }
        }

        $this->storePdf($chiTietHopDong);
        $chiTietHopDong->refresh();

        if (!$chiTietHopDong->file_pdf || !Storage::disk('public')->exists($chiTietHopDong->file_pdf)) {
            return response()->json([
                'status' => false,
                'message' => 'Không tạo được file PDF.',
            ], 500);
        }

        return Storage::disk('public')->download(
            $chiTietHopDong->file_pdf,
            'hop_dong_' . $chiTietHopDong->id . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    private function storePdf(ChiTietHopDong $hopDong): void
    {
        $hopDong->loadMissing(['nhanVien', 'loaiHopDong']);
        $path = 'hop_dongs/hop_dong_' . $hopDong->id . '.pdf';
        Storage::disk('public')->put($path, $this->makePdf($hopDong));
        $hopDong->forceFill(['file_pdf' => $path])->saveQuietly();
    }

    private function makePdf(ChiTietHopDong $hopDong): string
    {
        $lines = [
            'HOP DONG LAO DONG',
            'Loai hop dong: ' . optional($hopDong->loaiHopDong)->ten_hop_dong,
            'Nhan vien: ' . optional($hopDong->nhanVien)->ho_va_ten,
            'Email: ' . optional($hopDong->nhanVien)->email,
            'Ngay ky: ' . optional($hopDong->ngay_ky)->format('d/m/Y'),
            'Ngay bat dau: ' . optional($hopDong->ngay_bat_dau)->format('d/m/Y'),
            'Ngay ket thuc: ' . (optional($hopDong->ngay_ket_thuc)->format('d/m/Y') ?: 'Khong thoi han'),
            'Trang thai: ' . $this->statusText($hopDong->trang_thai),
            '',
            $this->normalizePdfText(strip_tags((string) $hopDong->noi_dung)),
            '',
            'Dai dien cong ty: ' . ($hopDong->chu_ky_admin ?: 'Chua ky'),
            'Ngay admin ky: ' . optional($hopDong->ngay_admin_ky)->format('d/m/Y H:i'),
            'Nhan vien xac nhan: ' . ($hopDong->chu_ky_nhan_vien ?: 'Chua ky'),
            'Ngay nhan vien ky: ' . optional($hopDong->ngay_nhan_vien_ky)->format('d/m/Y H:i'),
        ];

        return $this->simplePdf($lines);
    }

    private function simplePdf(array $lines): string
    {
        $objects = [];
        $content = "BT\n/F1 12 Tf\n50 800 Td\n14 TL\n";
        foreach ($lines as $line) {
            foreach (str_split($line, 90) ?: [''] as $chunk) {
                $content .= '(' . $this->escapePdfText($chunk) . ") Tj\nT*\n";
            }
        }
        $content .= "ET";

        $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>";
        $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[] = "<< /Length " . strlen($content) . " >>\nstream\n{$content}\nendstream";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[$index + 1] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n{$object}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        return $pdf . "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $this->normalizePdfText($text));
    }

    private function normalizePdfText(string $text): string
    {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
        return preg_replace('/[^\x20-\x7E]/', '', $converted ?: $text);
    }

    private function statusText(?string $status): string
    {
        return [
            'nhap' => 'Ban nhap',
            'cho_nhan_vien_ky' => 'Cho nhan vien ky',
            'hoan_tat' => 'Hoan tat',
        ][$status] ?? 'Ban nhap';
    }
}
