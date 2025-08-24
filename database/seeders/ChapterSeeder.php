<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run()
    {
        $bahasaArab = Subject::where('code', 'BA')->first();

        $chapters = [
            // Kelas X Gasal
            ['name' => 'الضمائر (Dhama\'ir)', 'grade' => 'X', 'semester' => 'gasal', 'order' => 1],
            ['name' => 'الأسماء المعرفة والنكرة', 'grade' => 'X', 'semester' => 'gasal', 'order' => 2],
            ['name' => 'الأفعال الأساسية', 'grade' => 'X', 'semester' => 'gasal', 'order' => 3],
            
            // Kelas X Genap
            ['name' => 'التركيب الإضافي', 'grade' => 'X', 'semester' => 'genap', 'order' => 1],
            ['name' => 'المحادثة اليومية', 'grade' => 'X', 'semester' => 'genap', 'order' => 2],
            
            // Kelas XI Gasal
            ['name' => 'الفعل الماضي والمضارع', 'grade' => 'XI', 'semester' => 'gasal', 'order' => 1],
            ['name' => 'الجملة الاسمية والفعلية', 'grade' => 'XI', 'semester' => 'gasal', 'order' => 2],
            
            // Kelas XI Genap
            ['name' => 'النحو المتقدم', 'grade' => 'XI', 'semester' => 'genap', 'order' => 1],
            ['name' => 'القراءة والفهم', 'grade' => 'XI', 'semester' => 'genap', 'order' => 2],
            
            // Kelas XII Gasal
            ['name' => 'التحليل النحوي', 'grade' => 'XII', 'semester' => 'gasal', 'order' => 1],
            ['name' => 'الكتابة المتقدمة', 'grade' => 'XII', 'semester' => 'gasal', 'order' => 2],
            
            // Kelas XII Genap
            ['name' => 'المراجعة الشاملة', 'grade' => 'XII', 'semester' => 'genap', 'order' => 1],
            ['name' => 'التطبيق العملي', 'grade' => 'XII', 'semester' => 'genap', 'order' => 2],
        ];

        foreach ($chapters as $chapter) {
            Chapter::create([
                'subject_id' => $bahasaArab->id,
                'name' => $chapter['name'],
                'grade' => $chapter['grade'],
                'semester' => $chapter['semester'],
                'description' => 'Bab ' . $chapter['name'],
                'order' => $chapter['order'],
                'is_active' => true,
            ]);
        }
    }
}
