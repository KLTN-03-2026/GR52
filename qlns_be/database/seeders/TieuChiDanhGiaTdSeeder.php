<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TieuChiDanhGiaTdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['tieu_de' => 'Kiến thức chuyên môn',     'mo_ta' => 'Đánh giá mức độ nắm vững kiến thức kỹ thuật/nghiệp vụ liên quan vị trí ứng tuyển', 'diem_toi_da' => 30],
            ['tieu_de' => 'Kinh nghiệm thực tế',      'mo_ta' => 'Số năm và chất lượng kinh nghiệm làm việc trong lĩnh vực liên quan',                 'diem_toi_da' => 25],
            ['tieu_de' => 'Kỹ năng giao tiếp',        'mo_ta' => 'Khả năng trình bày, diễn đạt rõ ràng và giao tiếp hiệu quả trong phỏng vấn',         'diem_toi_da' => 15],
            ['tieu_de' => 'Thái độ và động lực',      'mo_ta' => 'Mức độ nhiệt tình, định hướng nghề nghiệp và sự phù hợp văn hoá công ty',             'diem_toi_da' => 15],
            ['tieu_de' => 'Khả năng giải quyết vấn đề', 'mo_ta' => 'Tư duy phân tích, xử lý tình huống và đưa ra giải pháp thực tế',                   'diem_toi_da' => 10],
            ['tieu_de' => 'Kỹ năng làm việc nhóm',   'mo_ta' => 'Khả năng hợp tác, chia sẻ và đóng góp trong môi trường làm việc tập thể',             'diem_toi_da' => 5],
            ['tieu_de' => 'Điểm AI phân tích CV',     'mo_ta' => 'Điểm do hệ thống AI tự động chấm dựa trên phân tích nội dung CV ứng viên',            'diem_toi_da' => 100],
            ['tieu_de' => 'Mức độ phù hợp vị trí',   'mo_ta' => 'Đánh giá tổng thể mức độ phù hợp của ứng viên với yêu cầu vị trí tuyển dụng',        'diem_toi_da' => 100],
        ];

        foreach ($data as $item) {
            DB::table('tieu_chi_danh_gia_tds')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
