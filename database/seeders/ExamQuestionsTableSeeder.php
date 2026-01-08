<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_questions')->delete();
        
        \DB::table('exam_questions')->insert(array (
            0 => 
            array (
                'id' => 41,
                'exam_id' => 8,
                'question_id' => 10,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            1 => 
            array (
                'id' => 42,
                'exam_id' => 8,
                'question_id' => 9,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            2 => 
            array (
                'id' => 43,
                'exam_id' => 8,
                'question_id' => 8,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            3 => 
            array (
                'id' => 44,
                'exam_id' => 8,
                'question_id' => 7,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            4 => 
            array (
                'id' => 45,
                'exam_id' => 8,
                'question_id' => 6,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            5 => 
            array (
                'id' => 46,
                'exam_id' => 8,
                'question_id' => 5,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            6 => 
            array (
                'id' => 47,
                'exam_id' => 8,
                'question_id' => 4,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            7 => 
            array (
                'id' => 48,
                'exam_id' => 8,
                'question_id' => 3,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            8 => 
            array (
                'id' => 49,
                'exam_id' => 8,
                'question_id' => 2,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            9 => 
            array (
                'id' => 50,
                'exam_id' => 8,
                'question_id' => 1,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2025-10-02 19:46:08',
                'updated_at' => '2025-10-02 19:46:08',
            ),
            10 => 
            array (
                'id' => 91,
                'exam_id' => 10,
                'question_id' => 40,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            11 => 
            array (
                'id' => 92,
                'exam_id' => 10,
                'question_id' => 39,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            12 => 
            array (
                'id' => 93,
                'exam_id' => 10,
                'question_id' => 38,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            13 => 
            array (
                'id' => 94,
                'exam_id' => 10,
                'question_id' => 37,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            14 => 
            array (
                'id' => 95,
                'exam_id' => 10,
                'question_id' => 36,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            15 => 
            array (
                'id' => 96,
                'exam_id' => 10,
                'question_id' => 35,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            16 => 
            array (
                'id' => 97,
                'exam_id' => 10,
                'question_id' => 34,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            17 => 
            array (
                'id' => 98,
                'exam_id' => 10,
                'question_id' => 33,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            18 => 
            array (
                'id' => 99,
                'exam_id' => 10,
                'question_id' => 32,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            19 => 
            array (
                'id' => 100,
                'exam_id' => 10,
                'question_id' => 31,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2025-11-26 08:41:13',
                'updated_at' => '2025-11-26 08:41:13',
            ),
            20 => 
            array (
                'id' => 101,
                'exam_id' => 11,
                'question_id' => 20,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            21 => 
            array (
                'id' => 102,
                'exam_id' => 11,
                'question_id' => 19,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            22 => 
            array (
                'id' => 103,
                'exam_id' => 11,
                'question_id' => 18,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            23 => 
            array (
                'id' => 104,
                'exam_id' => 11,
                'question_id' => 17,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            24 => 
            array (
                'id' => 105,
                'exam_id' => 11,
                'question_id' => 16,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            25 => 
            array (
                'id' => 106,
                'exam_id' => 11,
                'question_id' => 15,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            26 => 
            array (
                'id' => 107,
                'exam_id' => 11,
                'question_id' => 14,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            27 => 
            array (
                'id' => 108,
                'exam_id' => 11,
                'question_id' => 13,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            28 => 
            array (
                'id' => 109,
                'exam_id' => 11,
                'question_id' => 12,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            29 => 
            array (
                'id' => 110,
                'exam_id' => 11,
                'question_id' => 11,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2025-11-26 09:09:38',
                'updated_at' => '2025-11-26 09:09:38',
            ),
            30 => 
            array (
                'id' => 111,
                'exam_id' => 9,
                'question_id' => 10,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            31 => 
            array (
                'id' => 112,
                'exam_id' => 9,
                'question_id' => 9,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            32 => 
            array (
                'id' => 113,
                'exam_id' => 9,
                'question_id' => 8,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            33 => 
            array (
                'id' => 114,
                'exam_id' => 9,
                'question_id' => 7,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            34 => 
            array (
                'id' => 115,
                'exam_id' => 9,
                'question_id' => 6,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            35 => 
            array (
                'id' => 116,
                'exam_id' => 9,
                'question_id' => 5,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            36 => 
            array (
                'id' => 117,
                'exam_id' => 9,
                'question_id' => 4,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            37 => 
            array (
                'id' => 118,
                'exam_id' => 9,
                'question_id' => 3,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            38 => 
            array (
                'id' => 119,
                'exam_id' => 9,
                'question_id' => 2,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            39 => 
            array (
                'id' => 120,
                'exam_id' => 9,
                'question_id' => 1,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:09',
                'updated_at' => '2026-01-08 11:03:09',
            ),
            40 => 
            array (
                'id' => 121,
                'exam_id' => 3,
                'question_id' => 50,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            41 => 
            array (
                'id' => 122,
                'exam_id' => 3,
                'question_id' => 49,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            42 => 
            array (
                'id' => 123,
                'exam_id' => 3,
                'question_id' => 48,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            43 => 
            array (
                'id' => 124,
                'exam_id' => 3,
                'question_id' => 47,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            44 => 
            array (
                'id' => 125,
                'exam_id' => 3,
                'question_id' => 46,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            45 => 
            array (
                'id' => 126,
                'exam_id' => 3,
                'question_id' => 45,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            46 => 
            array (
                'id' => 127,
                'exam_id' => 3,
                'question_id' => 44,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            47 => 
            array (
                'id' => 128,
                'exam_id' => 3,
                'question_id' => 43,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            48 => 
            array (
                'id' => 129,
                'exam_id' => 3,
                'question_id' => 42,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            49 => 
            array (
                'id' => 130,
                'exam_id' => 3,
                'question_id' => 41,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2026-01-08 11:03:37',
                'updated_at' => '2026-01-08 11:03:37',
            ),
            50 => 
            array (
                'id' => 131,
                'exam_id' => 4,
                'question_id' => 30,
                'question_order' => 1,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            51 => 
            array (
                'id' => 132,
                'exam_id' => 4,
                'question_id' => 29,
                'question_order' => 2,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            52 => 
            array (
                'id' => 133,
                'exam_id' => 4,
                'question_id' => 28,
                'question_order' => 3,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            53 => 
            array (
                'id' => 134,
                'exam_id' => 4,
                'question_id' => 27,
                'question_order' => 4,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            54 => 
            array (
                'id' => 135,
                'exam_id' => 4,
                'question_id' => 26,
                'question_order' => 5,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            55 => 
            array (
                'id' => 136,
                'exam_id' => 4,
                'question_id' => 25,
                'question_order' => 6,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            56 => 
            array (
                'id' => 137,
                'exam_id' => 4,
                'question_id' => 24,
                'question_order' => 7,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            57 => 
            array (
                'id' => 138,
                'exam_id' => 4,
                'question_id' => 23,
                'question_order' => 8,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            58 => 
            array (
                'id' => 139,
                'exam_id' => 4,
                'question_id' => 22,
                'question_order' => 9,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
            59 => 
            array (
                'id' => 140,
                'exam_id' => 4,
                'question_id' => 21,
                'question_order' => 10,
                'points' => 10,
                'created_at' => '2026-01-08 11:04:05',
                'updated_at' => '2026-01-08 11:04:05',
            ),
        ));
        
        
    }
}