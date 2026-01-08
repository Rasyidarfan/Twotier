<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exams')->delete();
        
        \DB::table('exams')->insert(array (
            0 => 
            array (
                'id' => 3,
                'title' => 'Uji Coba Skala Besar Kelas 12 Semester Ganjil',
                'description' => NULL,
                'code' => '0A6DNJ',
                'current_code' => 'RZLMPX',
                'subject_id' => 1,
                'grade' => 'XII',
                'semester' => 'gasal',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 3,
                'created_at' => '2025-09-23 15:42:20',
                'updated_at' => '2026-01-08 11:03:37',
                'code_generated_at' => '2025-11-22 11:04:25',
                'auto_regenerate_code' => 1,
            ),
            1 => 
            array (
                'id' => 4,
                'title' => 'Uji Coba Skala Besar Kelas 11 Semester Ganjil',
                'description' => NULL,
                'code' => '0TF64Q',
                'current_code' => 'YIKNVQ',
                'subject_id' => 1,
                'grade' => 'XI',
                'semester' => 'gasal',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 3,
                'created_at' => '2025-09-23 15:42:20',
                'updated_at' => '2026-01-08 11:04:05',
                'code_generated_at' => '2025-11-22 09:28:19',
                'auto_regenerate_code' => 1,
            ),
            2 => 
            array (
                'id' => 5,
                'title' => 'Ujian Tengah Semester Gasal - Bahasa Arab Kelas XII',
                'description' => 'Ujian semester gasal untuk kelas XII mencakup materi an-na\'t, al-idhafah, fi\'il mabni lil ma\'lum dan majhul, serta ism at-tafdhil.',
                'code' => 'EPAYJN',
                'current_code' => NULL,
                'subject_id' => 1,
                'grade' => 'XII',
                'semester' => 'gasal',
                'duration_minutes' => 90,
                'status' => 'draft',
                'shuffle_questions' => 0,
                'show_result_immediately' => 0,
                'created_by' => 4,
                'created_at' => '2025-09-23 15:42:20',
                'updated_at' => '2025-09-23 15:42:20',
                'code_generated_at' => NULL,
                'auto_regenerate_code' => 1,
            ),
            3 => 
            array (
                'id' => 6,
                'title' => 'Ujian Akhir Semester Genap - Bahasa Arab Kelas XII',
            'description' => 'Ujian semester genap untuk kelas XII (persiapan kelulusan) mencakup materi al-asma\' al-khamsah, al-af\'al al-khamsah, i\'rab fi\'il mudhari\', dan tarkib-tarkib lanjutan.',
                'code' => 'JTN95B',
                'current_code' => NULL,
                'subject_id' => 1,
                'grade' => 'XII',
                'semester' => 'genap',
                'duration_minutes' => 90,
                'status' => 'draft',
                'shuffle_questions' => 0,
                'show_result_immediately' => 0,
                'created_by' => 4,
                'created_at' => '2025-09-23 15:42:20',
                'updated_at' => '2025-09-23 15:42:20',
                'code_generated_at' => NULL,
                'auto_regenerate_code' => 1,
            ),
            4 => 
            array (
                'id' => 8,
                'title' => 'Bahasa Arab X Gasal',
                'description' => NULL,
                'code' => '2N6BRG',
                'current_code' => 'ZSXBX8',
                'subject_id' => 1,
                'grade' => 'X',
                'semester' => 'gasal',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 2,
                'created_at' => '2025-10-02 19:45:19',
                'updated_at' => '2025-10-02 20:04:03',
                'code_generated_at' => '2025-10-02 20:02:38',
                'auto_regenerate_code' => 1,
            ),
            5 => 
            array (
                'id' => 9,
                'title' => 'Uji Coba Skala Besar Kelas 10 Semester Ganjil',
                'description' => NULL,
                'code' => 'ZJXASN',
                'current_code' => 'QIHTDZ',
                'subject_id' => 1,
                'grade' => 'X',
                'semester' => 'gasal',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 3,
                'created_at' => '2025-11-13 10:15:22',
                'updated_at' => '2026-01-08 11:03:09',
                'code_generated_at' => '2025-11-13 10:40:43',
                'auto_regenerate_code' => 1,
            ),
            6 => 
            array (
                'id' => 10,
                'title' => 'Uji Coba Skala Besar Kelas 11 Semester Genap',
                'description' => NULL,
                'code' => 'FEMAYR',
                'current_code' => 'LJBFOY',
                'subject_id' => 1,
                'grade' => 'XI',
                'semester' => 'genap',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 3,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2026-01-08 11:01:04',
                'code_generated_at' => '2025-11-29 09:45:54',
                'auto_regenerate_code' => 1,
            ),
            7 => 
            array (
                'id' => 11,
                'title' => 'Uji Coba Skala Besar Kelas 10 Semester Genap',
                'description' => NULL,
                'code' => 'MRJLFX',
                'current_code' => 'GTOEQC',
                'subject_id' => 1,
                'grade' => 'X',
                'semester' => 'genap',
                'duration_minutes' => 60,
                'status' => 'finished',
                'shuffle_questions' => 1,
                'show_result_immediately' => 1,
                'created_by' => 3,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2026-01-08 11:00:41',
                'code_generated_at' => '2025-11-29 08:48:23',
                'auto_regenerate_code' => 1,
            ),
        ));
        
        
    }
}