<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChaptersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('chapters')->delete();
        
        \DB::table('chapters')->insert(array (
            0 => 
            array (
                'id' => 14,
                'subject_id' => 1,
                'name' => 'أقسام الكلمة',
                'grade' => 'X',
                'semester' => 'gasal',
                'description' => 'Isim, Fi\'il, Hurf',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 19:45:08',
                'updated_at' => '2025-09-28 19:45:08',
            ),
            1 => 
            array (
                'id' => 15,
                'subject_id' => 1,
                'name' => 'الأرقام/الأعداد',
                'grade' => 'X',
                'semester' => 'gasal',
                'description' => 'Angka 1-100',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 19:46:05',
                'updated_at' => '2025-09-28 19:46:05',
            ),
            2 => 
            array (
                'id' => 16,
                'subject_id' => 1,
                'name' => 'الضمائر',
                'grade' => 'X',
                'semester' => 'gasal',
                'description' => 'Kata Ganti',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 19:46:40',
                'updated_at' => '2025-09-28 19:46:40',
            ),
            3 => 
            array (
                'id' => 17,
                'subject_id' => 1,
                'name' => 'مفرد، مثنى، جمع',
                'grade' => 'X',
                'semester' => 'gasal',
                'description' => 'Mufrad, Mutsanna, Jama\'',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 19:47:16',
                'updated_at' => '2025-09-28 20:56:16',
            ),
            4 => 
            array (
                'id' => 18,
                'subject_id' => 1,
                'name' => 'أنواع الفعل',
                'grade' => 'X',
                'semester' => 'genap',
                'description' => 'Madhi, Mudhori\', Amr',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 20:58:14',
                'updated_at' => '2025-09-28 20:58:14',
            ),
            5 => 
            array (
                'id' => 19,
                'subject_id' => 1,
                'name' => 'المذكر والمؤنث',
                'grade' => 'X',
                'semester' => 'genap',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 20:59:09',
                'updated_at' => '2025-09-29 11:52:24',
            ),
            6 => 
            array (
                'id' => 20,
                'subject_id' => 1,
                'name' => 'أدوات الاستفهام',
                'grade' => 'X',
                'semester' => 'genap',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 20:59:57',
                'updated_at' => '2025-09-28 20:59:57',
            ),
            7 => 
            array (
                'id' => 21,
                'subject_id' => 1,
                'name' => 'الظروف',
                'grade' => 'X',
                'semester' => 'genap',
                'description' => 'Dhorof Zaman dan Makan',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-28 21:00:35',
                'updated_at' => '2025-09-28 21:00:35',
            ),
            8 => 
            array (
                'id' => 22,
                'subject_id' => 1,
                'name' => 'أعداد المِئات والآلاف ومِلْيُون',
                'grade' => 'XI',
                'semester' => 'gasal',
                'description' => 'Bilangan Ratusan, Ribuan, Jutaan',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 14:28:24',
                'updated_at' => '2025-09-30 19:25:46',
            ),
            9 => 
            array (
                'id' => 23,
                'subject_id' => 1,
                'name' => 'حروف الجر',
                'grade' => 'XI',
                'semester' => 'gasal',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 14:29:06',
                'updated_at' => '2025-09-29 14:29:06',
            ),
            10 => 
            array (
                'id' => 24,
                'subject_id' => 1,
                'name' => 'حروف العطف',
                'grade' => 'XI',
                'semester' => 'gasal',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 14:29:45',
                'updated_at' => '2025-09-29 14:29:45',
            ),
            11 => 
            array (
                'id' => 25,
                'subject_id' => 1,
                'name' => 'النكرة والمعرفة',
                'grade' => 'XI',
                'semester' => 'gasal',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 14:30:26',
                'updated_at' => '2025-09-29 14:30:26',
            ),
            12 => 
            array (
                'id' => 26,
                'subject_id' => 1,
                'name' => 'تصريف الفعل الماضي',
                'grade' => 'XI',
                'semester' => 'genap',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 15:34:20',
                'updated_at' => '2025-09-29 15:34:20',
            ),
            13 => 
            array (
                'id' => 27,
                'subject_id' => 1,
                'name' => 'تصريف الفعل المضارع',
                'grade' => 'XI',
                'semester' => 'genap',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 15:35:14',
                'updated_at' => '2025-09-29 15:35:14',
            ),
            14 => 
            array (
                'id' => 28,
                'subject_id' => 1,
                'name' => 'الجملة الاسمية والفعلية',
                'grade' => 'XI',
                'semester' => 'genap',
                'description' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-29 15:36:54',
                'updated_at' => '2025-09-29 15:36:54',
            ),
        ));
        
        
    }
}