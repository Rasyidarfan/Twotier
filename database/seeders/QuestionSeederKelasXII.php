<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Chapter;

class QuestionSeederKelasXII extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 20 soal untuk Kelas XII (10 soal semester gasal + 10 soal semester genap)
     */
    public function run(): void
    {
        // Ujian 5: Kelas XII Semester Gasal - Struktur Lanjutan (10 soal)
        $this->createQuestionsForSemesterGasal();
        
        // Ujian 6: Kelas XII Semester Genap - Persiapan Kelulusan (10 soal)
        $this->createQuestionsForSemesterGenap();
    }

    private function createQuestionsForSemesterGasal()
    {
        // Ambil chapter untuk kelas XII semester gasal
        $tahlilChapter = Chapter::where('name', 'التحليل النحوي')->where('grade', 'XII')->where('semester', 'gasal')->first();
        $kitabahChapter = Chapter::where('name', 'الكتابة المتقدمة')->where('grade', 'XII')->where('semester', 'gasal')->first();

        $questions = [
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'اختر الكلمة التي تعرب نعتاً في الجملة: "الولدُ الصغيرُ يلعبُ"',
                'tier1_options' => json_encode([
                    'الولدُ',
                    'الصغيرُ',
                    'يلعبُ',
                    'بالكرةِ',
                    'لا شيء'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // الصغيرُ
                'tier2_question' => 'لماذا اخترت تلك الكلمة؟',
                'tier2_options' => json_encode([
                    'لأنها اسم مرفوع',
                    'لأنها تصف اسمًا آخر وتتبعه في الإعراب',
                    'لأنها فعل مضارع',
                    'لأنها حرف جر',
                    'لأنها اسم معرفة'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنها تصف اسمًا آخر وتتبعه في الإعراب
                'difficulty' => 'sulit',
                'created_by' => 4, // Ustadz Muhammad Ridwan
            ],
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'حدد النعت في الجملة: "رأيتُ طائراً جميلاً"',
                'tier1_options' => json_encode([
                    'رأيتُ',
                    'طائراً',
                    'جميلاً',
                    'يحلقُ',
                    'في السماءِ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // جميلاً
                'tier2_question' => 'ما هي وظيفة الكلمة التي حددتها؟',
                'tier2_options' => json_encode([
                    'فاعل',
                    'مفعول به',
                    'صفة للاسم الذي قبله',
                    'ظرف مكان',
                    'فعل'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // صفة للاسم الذي قبله
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $kitabahChapter->id,
                'tier1_question' => 'حدد الإضافة في العبارة: "هذا كتابُ الطالبِ"',
                'tier1_options' => json_encode([
                    'كتابُ',
                    'الطالبِ',
                    'كتابُ الطالبِ',
                    'لا يوجد إضافة',
                    'هذا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // كتابُ الطالبِ
                'tier2_question' => 'ما السبب في اختيارك؟',
                'tier2_options' => json_encode([
                    'لأنها فعل',
                    'لأنها اسم يضاف إلى اسم آخر لتوضيحه',
                    'لأنها حرف',
                    'لأنها تدل على ملكية',
                    'لأنها تعرب مضاف إليه'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنها اسم يضاف إلى اسم آخر لتوضيحه
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $kitabahChapter->id,
                'tier1_question' => 'حدد المضاف إليه في العبارة: "بابُ البيتِ مفتوحٌ"',
                'tier1_options' => json_encode([
                    'بابُ',
                    'البيتِ',
                    'مفتوحٌ',
                    'بابُ البيتِ',
                    'لا يوجد'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // البيتِ
                'tier2_question' => 'لماذا تعرب الكلمة مضافًا إليه؟',
                'tier2_options' => json_encode([
                    'لأنها اسم مرفوع',
                    'لأنها تصف اسمًا آخر',
                    'لأنها اسم يأتي بعد المضاف لتوضيحه',
                    'لأنها فعل',
                    'لأنها حرف جر'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لأنها اسم يأتي بعد المضاف لتوضيحه
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'اختر الفعل المبني للمعلوم: "كتبَ الطالبُ الدرسَ"',
                'tier1_options' => json_encode([
                    'كتبَ',
                    'الطالبُ',
                    'الدرسَ',
                    'كتبَ الدرسُ',
                    'لا شيء'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // كتبَ
                'tier2_question' => 'لماذا هو فعل مبني للمعلوم؟',
                'tier2_options' => json_encode([
                    'لأنه مبدوء بحرف مضارعة',
                    'لأن فاعله مذكور ومعروف',
                    'لأنه يدل على حدث في الماضي',
                    'لأنه فعل أمر',
                    'لأنه فعل مضارع'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأن فاعله مذكور ومعروف
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'اختر الفعل المبني للمعلوم: "يقرأُ الطالبُ الكتابَ"',
                'tier1_options' => json_encode([
                    'يقرأُ',
                    'الطالبُ',
                    'الكتابَ',
                    'يقرأُ الكتابَ',
                    'لا شيء'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // يقرأُ
                'tier2_question' => 'ما علامة بناء الفعل؟',
                'tier2_options' => json_encode([
                    'الضمة',
                    'الفتحة',
                    'الكسرة',
                    'السكون',
                    'لا تتغير'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 0, // الضمة
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $kitabahChapter->id,
                'tier1_question' => 'حدد الفعل المبني للمجهول: "كُتِبَ الدرسُ"',
                'tier1_options' => json_encode([
                    'كُتِبَ',
                    'الدرسُ',
                    'كَتَبَ',
                    'الطالبُ',
                    'لا شيء'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // كُتِبَ
                'tier2_question' => 'ما الفرق بينه وبين الفعل المبني للمعلوم؟',
                'tier2_options' => json_encode([
                    'فاعله معروف',
                    'يبدأ بحرف مضارعة',
                    'فاعله غير مذكور أو مجهول',
                    'يدل على المستقبل',
                    'في صيغة أمر'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // فاعله غير مذكور أو مجهول
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $kitabahChapter->id,
                'tier1_question' => 'حدد الفعل المبني للمجهول: "يُكْتَبُ الدرسُ كلَّ يومٍ"',
                'tier1_options' => json_encode([
                    'يُكْتَبُ',
                    'الدرسُ',
                    'كلَّ',
                    'يومٍ',
                    'يكتبُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // يُكْتَبُ
                'tier2_question' => 'كيف يتم صياغة الفعل المبني للمجهول المضارع؟',
                'tier2_options' => json_encode([
                    'بزيادة حرف المضارعة',
                    'بضم الحرف الأول وفتح ما قبل الآخر',
                    'بكسر الحرف الأول وضم ما قبل الآخر',
                    'بوضع سكون على الآخر',
                    'بقلب حرف العلة'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // بضم الحرف الأول وفتح ما قبل الآخر
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'اختر اسم التفضيل: "هذا الكتابُ أكبرُ من ذلك"',
                'tier1_options' => json_encode([
                    'هذا',
                    'الكتابُ',
                    'أكبرُ',
                    'من',
                    'ذلك'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // أكبرُ
                'tier2_question' => 'ما معنى الكلمة التي اخترتها؟',
                'tier2_options' => json_encode([
                    'Ini',
                    'Buku',
                    'Lebih besar',
                    'Dari',
                    'Itu'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // Lebih besar
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tahlilChapter->id,
                'tier1_question' => 'اختر اسم التفضيل: "القلمُ الأزرقُ أفضلُ من الأحمرِ"',
                'tier1_options' => json_encode([
                    'القلمُ',
                    'الأزرقُ',
                    'أفضلُ',
                    'من',
                    'الأحمرِ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // أفضلُ
                'tier2_question' => 'ما وزن اسم التفضيل؟',
                'tier2_options' => json_encode([
                    'فاعل',
                    'مفعول',
                    'أفعل',
                    'يفعل',
                    'فعل'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // أفعل
                'difficulty' => 'sedang',
                'created_by' => 4,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }

    private function createQuestionsForSemesterGenap()
    {
        // Ambil chapter untuk kelas XII semester genap
        $murajaahChapter = Chapter::where('name', 'المراجعة الشاملة')->where('grade', 'XII')->where('semester', 'genap')->first();
        $tathbiqChapter = Chapter::where('name', 'التطبيق العملي')->where('grade', 'XII')->where('semester', 'genap')->first();

        $questions = [
            [
                'chapter_id' => $murajaahChapter->id,
                'tier1_question' => 'أَيُّ الْكَلِمَاتِ التَّالِيَةِ مِن الْأَسْمَاءِ الْخَمْسَةِ؟',
                'tier1_options' => json_encode([
                    'الْكِتَابُ',
                    'الْأَخُ',
                    'الْمَدْرَسَةُ',
                    'الْقَلَمُ',
                    'الْبَيْتُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // الْأَخُ
                'tier2_question' => 'لِمَاذَا اخْتَرْتَ هذه الإجابة؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهَا اسْمٌ مُفْرَدٌ مُذَكَّرٌ',
                    'لِأَنَّهَا تُرْفَعُ بِالضَّمَّةِ',
                    'لِأَنَّهَا مِنْ الْأَسْمَاءِ الْمُعْرَبَةِ بِالْحُرُوفِ',
                    'لِأَنَّهَا تَجْمَعُ جَمْعَ مُؤَنَّثٍ سَالِمٍ',
                    'لِأَنَّهَا تَنْتَهِي بِتَاءٍ مَرْبُوطَةٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لِأَنَّهَا مِنْ الْأَسْمَاءِ الْمُعْرَبَةِ بِالْحُرُوفِ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $murajaahChapter->id,
                'tier1_question' => 'أَيُّ الْكَلِمَاتِ التَّالِيَةِ تُعْرَبُ بِالْوَاوِ فِي حَالَةِ الرَّفْعِ؟',
                'tier1_options' => json_encode([
                    'الْكِتَابَ',
                    'الْأَخَوَيْنِ',
                    'الْأَخُ',
                    'الْأَخَوَاتُ',
                    'الْأَخَوِينَ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // الْأَخُ
                'tier2_question' => 'لِمَاذَا تُعْرَبُ بِالْوَاوِ فِي حَالَةِ الرَّفْعِ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ جَمْعُ مُذَكَّرٍ سَالِمٍ',
                    'لِأَنَّهُ مِنَ الْأَسْمَاءِ الْخَمْسَةِ',
                    'لِأَنَّهُ مُثَنَّى',
                    'لِأَنَّهُ اسْمٌ مُنَوَّنٌ',
                    'لِأَنَّهُ جَمْعُ تَكْسِيرٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لِأَنَّهُ مِنَ الْأَسْمَاءِ الْخَمْسَةِ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tathbiqChapter->id,
                'tier1_question' => 'مَا هُوَ الْفِعْلُ مِنَ الْأَفْعَالِ الْخَمْسَةِ: "الْأَوْلَادُ يَكْتُبُونَ الدَّرْسَ"',
                'tier1_options' => json_encode([
                    'الْأَوْلَادُ',
                    'يَكْتُبُونَ',
                    'الدَّرْسَ',
                    'فِي',
                    'الْـ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // يَكْتُبُونَ
                'tier2_question' => 'لِمَاذَا هَذَا الْفِعْلُ مِنَ الْأَفْعَالِ الْخَمْسَةِ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ فِعْلٌ مَاضٍ',
                    'لِأَنَّهُ مُضَارِعٌ اتَّصَلَ بِوَاوِ الْجَمَاعَةِ',
                    'لِأَنَّهُ فِعْلٌ أَمْرٍ',
                    'لِأَنَّهُ مُفْرَدٌ مُذَكَّرٌ',
                    'لِأَنَّهُ مَبْنِيٌّ عَلَى الْفَتْحِ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لِأَنَّهُ مُضَارِعٌ اتَّصَلَ بِوَاوِ الْجَمَاعَةِ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tathbiqChapter->id,
                'tier1_question' => 'عَلَامَةُ رَفْعِ الْفِعْلِ الْمُضَارِعِ: "الطَّالِبُ يَقْرَأُ الْكِتَابَ"',
                'tier1_options' => json_encode([
                    'الْفَتْحَةُ',
                    'الْكَسْرَةُ',
                    'الضَّمَّةُ',
                    'السُّكُونُ',
                    'التَّنْوِينُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // الضَّمَّةُ
                'tier2_question' => 'لِمَاذَا يُرْفَعُ الْفِعْلُ الْمُضَارِعُ؟',
                'tier2_options' => json_encode([
                    'لِأَنَّهُ لَمْ يَدْخُلْ عَلَيْهِ نَاصِبٌ وَلَا جَازِمٌ',
                    'لِأَنَّهُ فِعْلُ أَمْرٍ',
                    'لِأَنَّهُ فِعْلٌ مَاضٍ',
                    'لِأَنَّهُ يَدُلُّ عَلَى الْمُسْتَقْبَلِ',
                    'لِأَنَّهُ مَبْنِيٌّ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 0, // لِأَنَّهُ لَمْ يَدْخُلْ عَلَيْهِ نَاصِبٌ وَلَا جَازِمٌ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $murajaahChapter->id,
                'tier1_question' => 'حَدِّدِ الْفِعْلَ الْمُضَارِعَ الْمَنْصُوبَ: "الطَّالِبُ لَنْ يُهْمِلَ وَاجِبَهُ"',
                'tier1_options' => json_encode([
                    'الطَّالِبُ',
                    'لَنْ',
                    'يُهْمِلَ',
                    'وَاجِبَهُ',
                    'لا يوجد'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // يُهْمِلَ
                'tier2_question' => 'مَا الَّذِي نَصَبَ الْفِعْلَ الْمُضَارِعَ؟',
                'tier2_options' => json_encode([
                    'الطَّالِبُ',
                    'لَنْ',
                    'وَاجِبَهُ',
                    'لَا نَاصِبَ لَهُ',
                    'الْفَتْحَةُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لَنْ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $murajaahChapter->id,
                'tier1_question' => 'حَدِّدِ الْفِعْلَ الْمُضَارِعَ الْمَجْزُومَ: "الطَّالِبُ لَمْ يَنَمْ مُبَكِّرًا"',
                'tier1_options' => json_encode([
                    'الطَّالِبُ',
                    'لَمْ',
                    'يَنَمْ',
                    'مُبَكِّرًا',
                    'لا يوجد'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // يَنَمْ
                'tier2_question' => 'مَا الَّذِي جَزَمَ الْفِعْلَ الْمُضَارِعَ؟',
                'tier2_options' => json_encode([
                    'الطَّالِبُ',
                    'لَمْ',
                    'مُبَكِّرًا',
                    'لَا جَازِمَ لَهُ',
                    'السُّكُونُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لَمْ
                'difficulty' => 'sulit',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tathbiqChapter->id,
                'tier1_question' => 'حَدِّدِ الِاسْمَ الْمَجْرُورَ: "ذَهَبَ الطَّالِبُ إِلَى الْمَدْرَسَةِ"',
                'tier1_options' => json_encode([
                    'ذَهَبَ',
                    'الطَّالِبُ',
                    'إِلَى',
                    'الْمَدْرَسَةِ',
                    'لا يوجد'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 3, // الْمَدْرَسَةِ
                'tier2_question' => 'مَا الَّذِي جَرَّ الِاسْمَ؟',
                'tier2_options' => json_encode([
                    'ذَهَبَ',
                    'الطَّالِبُ',
                    'إِلَى',
                    'لَا جَارَّ لَهُ',
                    'الْكَسْرَةُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // إِلَى
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tathbiqChapter->id,
                'tier1_question' => 'في الجملة "جاء الرجلُ الطويلُ" ما نوع التركيب؟',
                'tier1_options' => json_encode([
                    'تركيب إضافي',
                    'تركيب وصفي',
                    'تركيب عطفي',
                    'تركيب بدلي',
                    'تركيب ظرفي'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // تركيب وصفي
                'tier2_question' => 'لماذا يسمى تركيبًا وصفيًا؟',
                'tier2_options' => json_encode([
                    'لأن الكلمة الثانية تضاف للأولى',
                    'لأن الكلمة الثانية تصف الأولى',
                    'لأن الكلمتين مرتبطتان بواو العطف',
                    'لأن الكلمة الثانية بدل من الأولى',
                    'لأن الكلمة الثانية ظرف'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأن الكلمة الثانية تصف الأولى
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $murajaahChapter->id,
                'tier1_question' => 'أي حروف العطف التالية تفيد التنويع؟',
                'tier1_options' => json_encode([
                    'وَ',
                    'فَ',
                    'أَوْ',
                    'ثُمَّ',
                    'لَكِنْ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // أَوْ
                'tier2_question' => 'ما معنى التنويع في حروف العطف؟',
                'tier2_options' => json_encode([
                    'الجمع بين شيئين',
                    'الترتيب مع التراخي',
                    'الاختيار بين شيئين أو أكثر',
                    'الترتيب مع التعقيب',
                    'الاستدراك'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // الاختيار بين شيئين أو أكثر
                'difficulty' => 'sedang',
                'created_by' => 4,
            ],
            [
                'chapter_id' => $tathbiqChapter->id,
                'tier1_question' => 'في الجملة "الطالبُ مجتهدٌ" ما نوع التركيب؟',
                'tier1_options' => json_encode([
                    'تركيب إضافي',
                    'تركيب وصفي',
                    'تركيب إسنادي',
                    'تركيب عطفي',
                    'تركيب مزجي'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // تركيب إسنادي
                'tier2_question' => 'لماذا يسمى تركيبًا إسناديًا؟',
                'tier2_options' => json_encode([
                    'لأنه يتكون من مضاف ومضاف إليه',
                    'لأنه يتكون من موصوف وصفة',
                    'لأنه يتكون من مسند ومسند إليه',
                    'لأنه يتكون من معطوف ومعطوف عليه',
                    'لأنه يتكون من كلمتين مدموجتين'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لأنه يتكون من مسند ومسند إليه
                'difficulty' => 'sedang',
                'created_by' => 4,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
