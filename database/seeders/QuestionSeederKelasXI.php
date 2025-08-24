<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Chapter;

class QuestionSeederKelasXI extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 20 soal untuk Kelas XI (10 soal semester gasal + 10 soal semester genap)
     */
    public function run(): void
    {
        // Ujian 3: Kelas XI Semester Gasal - Bilangan dan Struktur Kalimat (10 soal)
        $this->createQuestionsForSemesterGasal();
        
        // Ujian 4: Kelas XI Semester Genap - Tasrif dan Gender (10 soal)
        $this->createQuestionsForSemesterGenap();
    }

    private function createQuestionsForSemesterGasal()
    {
        // Ambil chapter untuk kelas XI semester gasal
        $fiilChapter = Chapter::where('name', 'الفعل الماضي والمضارع')->where('grade', 'XI')->where('semester', 'gasal')->first();
        $jumlahChapter = Chapter::where('name', 'الجملة الاسمية والفعلية')->where('grade', 'XI')->where('semester', 'gasal')->first();

        $questions = [
            [
                'chapter_id' => $fiilChapter->id,
                'tier1_question' => 'ما هوَ العددُ الذي يُمثِّلُ "خَمْسُمِائَةٍ وَسَبْعَةٌ وَعِشْرُونَ"؟',
                'tier1_options' => json_encode([
                    '275',
                    '527',
                    '725',
                    '257',
                    '752'
                ]),
                'tier1_correct_answer' => 1, // 527
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'مِائَتَانِ وَخَمْسَةٌ وَسَبْعُونَ',
                    'خَمْسُمِائَةٍ وَسَبْعَةٌ وَعِشْرُونَ',
                    'سَبْعُمِائَةٍ وَخَمْسَةٌ وَعِشْرُونَ',
                    'مِائَتَانِ وَسَبْعَةٌ وَخَمْسُونَ',
                    'سَبْعُمِائَةٍ وَاثْنَانِ وَخَمْسُونَ'
                ]),
                'tier2_correct_answer' => 1, // خَمْسُمِائَةٍ وَسَبْعَةٌ وَعِشْرُونَ
                'difficulty' => 'sulit',
                'created_by' => 3, // Ustadzah Siti Khadijah
            ],
            [
                'chapter_id' => $fiilChapter->id,
                'tier1_question' => 'ما هوَ العددُ الذي يُمثِّلُ "أَلْفٌ وَثَلاثُمِائَةٍ وَتِسْعَةٌ وَأَرْبَعُونَ"؟',
                'tier1_options' => json_encode([
                    '1349',
                    '1439',
                    '3149',
                    '4139',
                    '9413'
                ]),
                'tier1_correct_answer' => 0, // 1349
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'أَلْفٌ وَثَلاثُمِائَةٍ وَتِسْعَةٌ وَأَرْبَعُونَ',
                    'أَلْفٌ وَأَرْبَعُمِائَةٍ وَتِسْعَةٌ وَثَلاثُونَ',
                    'ثَلاثَةُ آلافٍ وَمِائَةٌ وَتِسْعَةٌ وَأَرْبَعُونَ',
                    'أَرْبَعَةُ آلافٍ وَمِائَةٌ وَثَلاثَةٌ وَتِسْعُونَ',
                    'تِسْعَةُ آلافٍ وَأَرْبَعُمِائَةٍ وَثَلاثَةَ عَشَرَ'
                ]),
                'tier2_correct_answer' => 0, // أَلْفٌ وَثَلاثُمِائَةٍ وَتِسْعَةٌ وَأَرْبَعُونَ
                'difficulty' => 'sulit',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $fiilChapter->id,
                'tier1_question' => 'ما هوَ العددُ الذي يُمثِّلُ "مِلْيُونٌ"؟',
                'tier1_options' => json_encode([
                    '1000',
                    '10000',
                    '100000',
                    '1000000',
                    '10000000'
                ]),
                'tier1_correct_answer' => 3, // 1000000
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'أَلْفٌ',
                    'عَشَرَةُ آلافٍ',
                    'مِائَةُ أَلْفٍ',
                    'مِلْيُونٌ',
                    'عَشَرَةُ مَلايِينٍ'
                ]),
                'tier2_correct_answer' => 3, // مِلْيُونٌ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'كلمةُ "مِنَ البَيْتِ إِلَى المَسْجِدِ" تحتوي على...',
                'tier1_options' => json_encode([
                    'حرفِ عَطْفٍ',
                    'حرفِ جَرٍّ',
                    'ظرفِ مكانٍ',
                    'اسمٍ مُعَرَّفٍ',
                    'اسمٍ نَكِرَةٍ'
                ]),
                'tier1_correct_answer' => 1, // حرفِ جَرٍّ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تربطُ بينَ الكلماتِ بِوَاوِ العَطْفِ',
                    'تربطُ بينَ الأسماءِ بِحَرْفِ الجَرِّ',
                    'تدلُّ على المكانِ',
                    'تدلُّ على اسمٍ مُحَدَّدٍ',
                    'تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ'
                ]),
                'tier2_correct_answer' => 1, // تربطُ بينَ الأسماءِ بِحَرْفِ الجَرِّ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'كلمةُ "الكِتَابُ وَالقَلَمُ" تحتوي على...',
                'tier1_options' => json_encode([
                    'حرفِ جَرٍّ',
                    'حرفِ عَطْفٍ',
                    'ظرفِ زمانٍ',
                    'اسمٍ نَكِرَةٍ',
                    'فِعْلٍ'
                ]),
                'tier1_correct_answer' => 1, // حرفِ عَطْفٍ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تربطُ بينَ الأسماءِ بِحَرْفِ الجَرِّ',
                    'تربطُ بينَ الكلماتِ بِوَاوِ العَطْفِ',
                    'تدلُّ على الزمانِ',
                    'تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ',
                    'تدلُّ على حَدَثٍ'
                ]),
                'tier2_correct_answer' => 1, // تربطُ بينَ الكلماتِ بِوَاوِ العَطْفِ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'كلمةُ "رَجُلٌ" تُعتَبَرُ...',
                'tier1_options' => json_encode([
                    'اسمًا مُعَرَّفًا',
                    'اسمًا نَكِرَةً',
                    'حرفَ جَرٍّ',
                    'حرفَ عَطْفٍ',
                    'ظرفَ مكانٍ'
                ]),
                'tier1_correct_answer' => 1, // اسمًا نَكِرَةً
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على اسمٍ مُحَدَّدٍ',
                    'تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ',
                    'تربطُ بينَ الأسماءِ',
                    'تربطُ بينَ الكلماتِ',
                    'تدلُّ على المكانِ'
                ]),
                'tier2_correct_answer' => 1, // تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'كلمةُ "المَسْجِدُ" تُعتَبَرُ...',
                'tier1_options' => json_encode([
                    'اسمًا نَكِرَةً',
                    'اسمًا مُعَرَّفًا',
                    'حرفَ جَرٍّ',
                    'حرفَ عَطْفٍ',
                    'ظرفَ زمانٍ'
                ]),
                'tier1_correct_answer' => 1, // اسمًا مُعَرَّفًا
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ',
                    'تدلُّ على اسمٍ مُحَدَّدٍ',
                    'تربطُ بينَ الأسماءِ',
                    'تربطُ بينَ الكلماتِ',
                    'تدلُّ على الزمانِ'
                ]),
                'tier2_correct_answer' => 1, // تدلُّ على اسمٍ مُحَدَّدٍ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'كلمةُ "في السَّاحَةِ" تحتوي على...',
                'tier1_options' => json_encode([
                    'اسمًا نَكِرَةً',
                    'اسمًا مُعَرَّفًا',
                    'حرفَ جَرٍّ',
                    'حرفَ عَطْفٍ',
                    'ظرفَ زمانٍ'
                ]),
                'tier1_correct_answer' => 2, // حرفَ جَرٍّ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على اسمٍ غَيْرِ مُحَدَّدٍ',
                    'تدلُّ على اسمٍ مُحَدَّدٍ وَسَبَقَها حَرْفُ جَرٍّ',
                    'تربطُ بينَ الأسماءِ',
                    'تربطُ بينَ الكلماتِ',
                    'تدلُّ على الزمانِ'
                ]),
                'tier2_correct_answer' => 1, // تدلُّ على اسمٍ مُحَدَّدٍ وَسَبَقَها حَرْفُ جَرٍّ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $fiilChapter->id,
                'tier1_question' => 'ما هوَ العددُ الذي يُمثِّلُ "عَشَرَةُ آلافٍ"؟',
                'tier1_options' => json_encode([
                    '1000',
                    '10000',
                    '100000',
                    '1000000',
                    '10000000'
                ]),
                'tier1_correct_answer' => 1, // 10000
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'أَلْفٌ واحدٌ',
                    'عَشَرَةُ آلافٍ',
                    'مِائَةُ أَلْفٍ',
                    'مِلْيُونٌ',
                    'عَشَرَةُ مَلايِينٍ'
                ]),
                'tier2_correct_answer' => 1, // عَشَرَةُ آلافٍ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $jumlahChapter->id,
                'tier1_question' => 'في عبارة "كتابُ الطالبِ" ما هي العلاقة بين الكلمتين؟',
                'tier1_options' => json_encode([
                    'علاقة عطف',
                    'علاقة إضافة',
                    'علاقة نعت',
                    'علاقة جر',
                    'علاقة ظرف'
                ]),
                'tier1_correct_answer' => 1, // علاقة إضافة
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تربط بالواو',
                    'الكلمة الأولى تضاف للثانية',
                    'الكلمة الثانية تصف الأولى',
                    'تربط بحرف جر',
                    'تدل على المكان'
                ]),
                'tier2_correct_answer' => 1, // الكلمة الأولى تضاف للثانية
                'difficulty' => 'sedang',
                'created_by' => 3,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }

    private function createQuestionsForSemesterGenap()
    {
        // Ambil chapter untuk kelas XI semester genap
        $nahwuChapter = Chapter::where('name', 'النحو المتقدم')->where('grade', 'XI')->where('semester', 'genap')->first();
        $qiraahChapter = Chapter::where('name', 'القراءة والفهم')->where('grade', 'XI')->where('semester', 'genap')->first();

        $questions = [
            [
                'chapter_id' => $nahwuChapter->id,
                'tier1_question' => 'أَيُّ الْفِعْلِ التَّالِيَةِ يُعْتَبَرُ فِعْلًا مُضَارِعًا؟',
                'tier1_options' => json_encode([
                    'كَتَبَ',
                    'يَكْتُبُ',
                    'اُكْتُبْ',
                    'كَاتِبٌ',
                    'مَكْتُوبٌ'
                ]),
                'tier1_correct_answer' => 1, // يَكْتُبُ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ يَدُلُّ عَلَى الْمَاضِي',
                    'لِأَنَّهُ يَدُلُّ عَلَى الْحَاضِرِ أَوِ الْمُسْتَقْبَلِ',
                    'لِأَنَّهُ فِعْلُ أَمْرٍ',
                    'لِأَنَّهُ اِسْمُ فَاعِلٍ',
                    'لِأَنَّهُ اِسْمُ مَفْعُولٍ'
                ]),
                'tier2_correct_answer' => 1, // لِأَنَّهُ يَدُلُّ عَلَى الْحَاضِرِ أَوِ الْمُسْتَقْبَلِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $nahwuChapter->id,
                'tier1_question' => 'أَيُّ الْفِعْلِ التَّالِيَةِ يُعْتَبَرُ فِعْلًا مَاضِيًا؟',
                'tier1_options' => json_encode([
                    'يَذْهَبُ',
                    'اِذْهَبْ',
                    'ذَهَبَ',
                    'ذَاهِبٌ',
                    'مَذْهُوبٌ'
                ]),
                'tier1_correct_answer' => 2, // ذَهَبَ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ يَدُلُّ عَلَى الْحَاضِرِ أَوِ الْمُسْتَقْبَلِ',
                    'لِأَنَّهُ فِعْلُ أَمْرٍ',
                    'لِأَنَّهُ يَدُلُّ عَلَى الْمَاضِي',
                    'لِأَنَّهُ اِسْمُ فَاعِلٍ',
                    'لِأَنَّهُ اِسْمُ مَفْعُولٍ'
                ]),
                'tier2_correct_answer' => 2, // لِأَنَّهُ يَدُلُّ عَلَى الْمَاضِي
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $qiraahChapter->id,
                'tier1_question' => 'مَا هُوَ تَصْرِيفُ الْفِعْلِ "كَتَبَ" لِلْمُؤَنَّثِ الْغَائِبِ؟',
                'tier1_options' => json_encode([
                    'كَتَبَ',
                    'كَتَبَتْ',
                    'كَتَبُوا',
                    'كَتَبْنَ',
                    'يَكْتُبْنَ'
                ]),
                'tier1_correct_answer' => 1, // كَتَبَتْ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْغَائِبِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْغَائِبِ',
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْجَمْعِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْجَمْعِ',
                    'لِأَنَّهُ فِعْلٌ مُضَارِعٌ'
                ]),
                'tier2_correct_answer' => 1, // لِأَنَّهُ لِلْمُؤَنَّثِ الْغَائِبِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $qiraahChapter->id,
                'tier1_question' => 'مَا هُوَ تَصْرِيفُ الْفِعْلِ "يَذْهَبُ" لِلْمُذَكَّرِ الْجَمْعِ؟',
                'tier1_options' => json_encode([
                    'يَذْهَبُ',
                    'تَذْهَبُ',
                    'يَذْهَبُونَ',
                    'يَذْهَبْنَ',
                    'ذَهَبُوا'
                ]),
                'tier1_correct_answer' => 2, // يَذْهَبُونَ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لِلْمُفْرَدِ الْمُذَكَّرِ',
                    'لِأَنَّهُ لِلْمُفْرَدِ الْمُؤَنَّثِ',
                    'لِأَنَّهُ لِلْجَمْعِ الْمُذَكَّرِ',
                    'لِأَنَّهُ لِلْجَمْعِ الْمُؤَنَّثِ',
                    'لِأَنَّهُ فِعْلٌ مَاضٍ'
                ]),
                'tier2_correct_answer' => 2, // لِأَنَّهُ لِلْجَمْعِ الْمُذَكَّرِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $nahwuChapter->id,
                'tier1_question' => 'أَيُّ الْفِعْلِ التَّالِيَةِ يُعْتَبَرُ مُذَكَّرًا؟',
                'tier1_options' => json_encode([
                    'كَتَبَتْ',
                    'ذَهَبَتْ',
                    'قَرَأَتْ',
                    'كَتَبَ',
                    'تَكْتُبُ'
                ]),
                'tier1_correct_answer' => 3, // كَتَبَ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ يَنْتَهِي بِتَاءِ التَّأْنِيثِ',
                    'لِأَنَّهُ لَا يَنْتَهِي بِتَاءِ التَّأْنِيثِ',
                    'لِأَنَّهُ فِعْلٌ مُضَارِعٌ',
                    'لِأَنَّهُ اِسْمٌ',
                    'لِأَنَّهُ فِعْلُ أَمْرٍ'
                ]),
                'tier2_correct_answer' => 1, // لِأَنَّهُ لَا يَنْتَهِي بِتَاءِ التَّأْنِيثِ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $nahwuChapter->id,
                'tier1_question' => 'أَيُّ الْفِعْلِ التَّالِيَةِ يُعْتَبَرُ مُؤَنَّثًا؟',
                'tier1_options' => json_encode([
                    'ذَهَبَ',
                    'كَتَبَ',
                    'قَرَأَ',
                    'ذَهَبَتْ',
                    'يَذْهَبُ'
                ]),
                'tier1_correct_answer' => 3, // ذَهَبَتْ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لَا يَنْتَهِي بِتَاءِ التَّأْنِيثِ',
                    'لِأَنَّهُ يَنْتَهِي بِتَاءِ التَّأْنِيثِ',
                    'لِأَنَّهُ فِعْلٌ مُضَارِعٌ',
                    'لِأَنَّهُ اِسْمٌ',
                    'لِأَنَّهُ فِعْلُ أَمْرٍ'
                ]),
                'tier2_correct_answer' => 1, // لِأَنَّهُ يَنْتَهِي بِتَاءِ التَّأْنِيثِ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $qiraahChapter->id,
                'tier1_question' => 'مَا هُوَ تَصْرِيفُ الْفِعْلِ "يَكْتُبُ" لِلْمُؤَنَّثِ الْغَائِبِ؟',
                'tier1_options' => json_encode([
                    'يَكْتُبُ',
                    'تَكْتُبُ',
                    'يَكْتُبُونَ',
                    'يَكْتُبْنَ',
                    'كَتَبَتْ'
                ]),
                'tier1_correct_answer' => 1, // تَكْتُبُ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْمُفْرَدِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْمُفْرَدِ',
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْجَمْعِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْجَمْعِ',
                    'لِأَنَّهُ فِعْلٌ مَاضٍ'
                ]),
                'tier2_correct_answer' => 1, // لِأَنَّهُ لِلْمُؤَنَّثِ الْمُفْرَدِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $qiraahChapter->id,
                'tier1_question' => 'مَا هُوَ تَصْرِيفُ الْفِعْلِ "قَرَأَ" لِلْمُذَكَّرِ الْجَمْعِ؟',
                'tier1_options' => json_encode([
                    'قَرَأَ',
                    'قَرَأَتْ',
                    'قَرَأُوا',
                    'قَرَأْنَ',
                    'يَقْرَأُونَ'
                ]),
                'tier1_correct_answer' => 2, // قَرَأُوا
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لِلْمُفْرَدِ الْمُذَكَّرِ',
                    'لِأَنَّهُ لِلْمُفْرَدِ الْمُؤَنَّثِ',
                    'لِأَنَّهُ لِلْجَمْعِ الْمُذَكَّرِ',
                    'لِأَنَّهُ لِلْجَمْعِ الْمُؤَنَّثِ',
                    'لِأَنَّهُ فِعْلٌ مُضَارِعٌ'
                ]),
                'tier2_correct_answer' => 2, // لِأَنَّهُ لِلْجَمْعِ الْمُذَكَّرِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $nahwuChapter->id,
                'tier1_question' => 'أَيُّ الْفِعْلِ التَّالِيَةِ يُعْتَبَرُ فِعْلَ أَمْرٍ؟',
                'tier1_options' => json_encode([
                    'كَتَبَ',
                    'يَكْتُبُ',
                    'اُكْتُبْ',
                    'كَاتِبٌ',
                    'مَكْتُوبٌ'
                ]),
                'tier1_correct_answer' => 2, // اُكْتُبْ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ يَدُلُّ عَلَى الْمَاضِي',
                    'لِأَنَّهُ يَدُلُّ عَلَى الْحَاضِرِ أَوِ الْمُسْتَقْبَلِ',
                    'لِأَنَّهُ يَدُلُّ عَلَى طَلَبِ الْفِعْلِ',
                    'لِأَنَّهُ اِسْمُ فَاعِلٍ',
                    'لِأَنَّهُ اِسْمُ مَفْعُولٍ'
                ]),
                'tier2_correct_answer' => 2, // لِأَنَّهُ يَدُلُّ عَلَى طَلَبِ الْفِعْلِ
                'difficulty' => 'mudah',
                'created_by' => 3,
            ],
            [
                'chapter_id' => $qiraahChapter->id,
                'tier1_question' => 'مَا هُوَ تَصْرِيفُ الْفِعْلِ "شَرِبَ" لِلْمُؤَنَّثِ الْجَمْعِ؟',
                'tier1_options' => json_encode([
                    'شَرِبَ',
                    'شَرِبَتْ',
                    'شَرِبُوا',
                    'شَرِبْنَ',
                    'يَشْرَبْنَ'
                ]),
                'tier1_correct_answer' => 3, // شَرِبْنَ
                'tier2_question' => 'مَا هُوَ السَّبَبُ فِي اخْتِيَارِكَ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْغَائِبِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْغَائِبِ',
                    'لِأَنَّهُ لِلْمُذَكَّرِ الْجَمْعِ',
                    'لِأَنَّهُ لِلْمُؤَنَّثِ الْجَمْعِ',
                    'لِأَنَّهُ فِعْلٌ مُضَارِعٌ'
                ]),
                'tier2_correct_answer' => 3, // لِأَنَّهُ لِلْمُؤَنَّثِ الْجَمْعِ
                'difficulty' => 'sedang',
                'created_by' => 3,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
