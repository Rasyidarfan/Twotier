<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 6 ujian dengan masing-masing 10 soal
     */
    public function run(): void
    {
        $bahasaArab = Subject::where('code', 'BA')->first();

        // Ujian 1: Kelas X Semester Gasal
        $this->createExamKelasXGasal($bahasaArab->id);
        
        // Ujian 2: Kelas X Semester Genap
        $this->createExamKelasXGenap($bahasaArab->id);
        
        // Ujian 3: Kelas XI Semester Gasal
        $this->createExamKelasXIGasal($bahasaArab->id);
        
        // Ujian 4: Kelas XI Semester Genap
        $this->createExamKelasXIGenap($bahasaArab->id);
        
        // Ujian 5: Kelas XII Semester Gasal
        $this->createExamKelasXIIGasal($bahasaArab->id);
        
        // Ujian 6: Kelas XII Semester Genap
        $this->createExamKelasXIIGenap($bahasaArab->id);
    }

    private function createExamKelasXGasal($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Tengah Semester Gasal - Bahasa Arab Kelas X',
            'description' => 'Ujian semester gasal untuk kelas X mencakup materi dasar gramatikal bahasa Arab: pembagian kata, bilangan, kata ganti, dan bentuk kata.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'X',
            'semester' => 'gasal',
            'duration_minutes' => 45,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 2, // Ustadz Ahmad Fadhil
        ]);
    }

    private function createExamKelasXGenap($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Akhir Semester Genap - Bahasa Arab Kelas X',
            'description' => 'Ujian semester genap untuk kelas X mencakup materi kata kerja, kata tanya, keterangan tempat dan waktu, serta fi\'il amr.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'X',
            'semester' => 'genap',
            'duration_minutes' => 45,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 2, // Ustadz Ahmad Fadhil
        ]);
    }

    private function createExamKelasXIGasal($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Tengah Semester Gasal - Bahasa Arab Kelas XI',
            'description' => 'Ujian semester gasal untuk kelas XI mencakup materi bilangan kompleks, huruf jar dan athf, isim nakirah dan ma\'rifah, serta tarkib idhafy.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'XI',
            'semester' => 'gasal',
            'duration_minutes' => 60,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 3, // Ustadzah Siti Khadijah
        ]);
    }

    private function createExamKelasXIGenap($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Akhir Semester Genap - Bahasa Arab Kelas XI',
            'description' => 'Ujian semester genap untuk kelas XI mencakup materi tasrif fi\'il mudhari\' dan madhi, fi\'il mudzakkar dan muannats, serta tasrif berdasarkan subjek.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'XI',
            'semester' => 'genap',
            'duration_minutes' => 60,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 3, // Ustadzah Siti Khadijah
        ]);
    }

    private function createExamKelasXIIGasal($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Tengah Semester Gasal - Bahasa Arab Kelas XII',
            'description' => 'Ujian semester gasal untuk kelas XII mencakup materi an-na\'t, al-idhafah, fi\'il mabni lil ma\'lum dan majhul, serta ism at-tafdhil.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'XII',
            'semester' => 'gasal',
            'duration_minutes' => 90,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 4, // Ustadz Muhammad Ridwan
        ]);
    }

    private function createExamKelasXIIGenap($subjectId)
    {
        Exam::create([
            'title' => 'Ujian Akhir Semester Genap - Bahasa Arab Kelas XII',
            'description' => 'Ujian semester genap untuk kelas XII (persiapan kelulusan) mencakup materi al-asma\' al-khamsah, al-af\'al al-khamsah, i\'rab fi\'il mudhari\', dan tarkib-tarkib lanjutan.',
            'code' => $this->generateExamCode(),
            'subject_id' => $subjectId,
            'grade' => 'XII',
            'semester' => 'genap',
            'duration_minutes' => 90,
            'status' => 'draft',
            'shuffle_questions' => false,
            'show_result_immediately' => false,
            'auto_regenerate_code' => true,
            'created_by' => 4, // Ustadz Muhammad Ridwan
        ]);
    }

    private function generateExamCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (Exam::where('code', $code)->exists());

        return $code;
    }
}
