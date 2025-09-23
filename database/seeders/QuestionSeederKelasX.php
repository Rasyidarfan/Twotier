<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Chapter;

class QuestionSeederKelasX extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 20 soal untuk Kelas X (10 soal semester gasal + 10 soal semester genap)
     */
    public function run(): void
    {
        // Ujian 1: Kelas X Semester Gasal - Dasar Gramatikal (10 soal)
        $this->createQuestionsForSemesterGasal();
        
        // Ujian 2: Kelas X Semester Genap - Kata Kerja dan Keterangan (10 soal)
        $this->createQuestionsForSemesterGenap();
    }

    private function createQuestionsForSemesterGasal()
    {
        // Ambil chapter untuk kelas X semester gasal
        $dhamairChapter = Chapter::where('name', 'الضمائر (Dhama\'ir)')->where('grade', 'X')->where('semester', 'gasal')->first();
        $nakirahMarifahChapter = Chapter::where('name', 'الأسماء المعرفة والنكرة')->where('grade', 'X')->where('semester', 'gasal')->first();
        $afaalChapter = Chapter::where('name', 'الأفعال الأساسية')->where('grade', 'X')->where('semester', 'gasal')->first();

        $questions = [
            [
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => 'أي الضمائر التالية هو ضمير منفصل؟',
                'tier1_options' => json_encode([
                    '-هُ',
                    '-كَ', 
                    'أنا',
                    '-ي',
                    'هُوَ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // أنا
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الأول:',
                'tier2_options' => json_encode([
                    'لأنه يتصل بالكلمة',
                    'لأنه يدل على الملكية',
                    'لأنه يأتي منفصلاً عن الكلمة',
                    'لأنه يدل على المتكلم',
                    'لأنه يدل على الغائب'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لأنه يأتي منفصلاً عن الكلمة
                'difficulty' => 'sedang',
                'created_by' => 2, // Ustadz Ahmad Fadhil
            ],
            [
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => 'أي الضمائر التالية هو ضمير متصل؟',
                'tier1_options' => json_encode([
                    'أنتَ',
                    'هُنَّ',
                    'هُمَا',
                    '-كُم',
                    'أنا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 3, // -كُم
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الثاني:',
                'tier2_options' => json_encode([
                    'لأنه يأتي منفصلاً',
                    'لأنه يتصل بالفعل أو الاسم',
                    'لأنه يستخدم للغائب',
                    'لأنه يستخدم للمخاطب',
                    'لأنه يدل على المتكلم'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنه يتصل بالفعل أو الاسم
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => 'في جملة "كتابُكَ جديدٌ"، ما نوع الضمير "كَ"؟',
                'tier1_options' => json_encode([
                    'ضمير منفصل',
                    'ضمير متصل',
                    'ضمير مستتر',
                    'اسم ظاهر',
                    'حرف جر'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // ضمير متصل
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الثالث:',
                'tier2_options' => json_encode([
                    'لأنه يدل على المخاطب منفصلاً',
                    'لأنه اتصل بالاسم "كتابُ"',
                    'لأنه غير ظاهر في الجملة',
                    'لأنه ليس ضميراً',
                    'لأنه حرف من حروف الجر'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنه اتصل بالاسم "كتابُ"
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => 'في جملة "هُوَ طالبٌ مجتهدٌ"، ما نوع الضمير "هُوَ"؟',
                'tier1_options' => json_encode([
                    'ضمير متصل',
                    'ضمير منفصل',
                    'ضمير مستتر',
                    'اسم إشارة',
                    'حرف عطف'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // ضمير منفصل
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الرابع:',
                'tier2_options' => json_encode([
                    'لأنه اتصل بالفعل "طالبٌ"',
                    'لأنه جاء منفصلاً عن الكلمة',
                    'لأنه غير ظاهر في الجملة',
                    'لأنه يدل على الإشارة',
                    'لأنه يربط بين الكلمات'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنه جاء منفصلاً عن الكلمة
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => 'أي الجمل التالية تحتوي على ضمير متصل يدل على الملكية؟',
                'tier1_options' => json_encode([
                    'أنتَ طالبٌ',
                    'كتابُهُ جميلٌ',
                    'هُم طلابٌ',
                    'هي معلمةٌ',
                    'نحن مجتهدون'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // كتابُهُ جميلٌ
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الخامس:',
                'tier2_options' => json_encode([
                    'لأن الضمير جاء منفصلاً',
                    'لأن الضمير اتصل بالاسم ودل على ملكية',
                    'لأن الضمير يدل على جمع الغائبين',
                    'لأن الضمير يدل على المفردة الغائبة',
                    'لأن الضمير يدل على المتكلمين'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأن الضمير اتصل بالاسم ودل على ملكية
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $nakirahMarifahChapter->id,
                'tier1_question' => 'أي الكلمات التالية هي اسم نكرة؟',
                'tier1_options' => json_encode([
                    'الكتاب',
                    'المدرسة',
                    'قلم',
                    'البيت',
                    'الطالب'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // قلم
                'tier2_question' => 'اختر السبب الصحيح لاختيارك:',
                'tier2_options' => json_encode([
                    'لأنه معرف بأل التعريف',
                    'لأنه يدل على شيء معين',
                    'لأنه يدل على شيء غير معين',
                    'لأنه اسم علم',
                    'لأنه اسم موصول'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لأنه يدل على شيء غير معين
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $nakirahMarifahChapter->id,
                'tier1_question' => 'أي الكلمات التالية هي اسم معرفة؟',
                'tier1_options' => json_encode([
                    'رجل',
                    'مدينة',
                    'الشمس',
                    'بيت',
                    'طالب'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // الشمس
                'tier2_question' => 'اختر السبب الصحيح لاختيارك:',
                'tier2_options' => json_encode([
                    'لأنه لا يحتوي على أل التعريف',
                    'لأنه يدل على شيء غير محدد',
                    'لأنه يحتوي على أل التعريف',
                    'لأنه اسم نكرة',
                    'لأنه حرف جر'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // لأنه يحتوي على أل التعريف
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $nakirahMarifahChapter->id,
                'tier1_question' => 'في جملة "قرأتُ كتابًا"، ما نوع كلمة "كتابًا"؟',
                'tier1_options' => json_encode([
                    'اسم معرفة',
                    'اسم نكرة',
                    'اسم علم',
                    'اسم إشارة',
                    'اسم موصول'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // اسم نكرة
                'tier2_question' => 'اختر السبب الصحيح لاختيارك:',
                'tier2_options' => json_encode([
                    'لأنه يحتوي على أل التعريف',
                    'لأنه لا يحتوي على أل التعريف ويدل على كتاب غير محدد',
                    'لأنه اسم شخص',
                    'لأنه يدل على شيء محدد بالاشارة',
                    'لأنه يربط بين جملتين'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // لأنه لا يحتوي على أل التعريف ويدل على كتاب غير محدد
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $afaalChapter->id,
                'tier1_question' => 'أيُّ الكلماتِ التاليةِ تُعتبَرُ فعلاً؟',
                'tier1_options' => json_encode([
                    'مَكتَبٌ',
                    'إلى',
                    'قَرَأَ',
                    'هَلْ',
                    'رَجُلٌ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // قَرَأَ
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'اسمُ مكانٍ',
                    'حرفُ جرٍّ',
                    'يدلُّ على حدثٍ في الزمنِ الماضي',
                    'حرفُ استفهامٍ',
                    'اسمُ إنسانٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // يدلُّ على حدثٍ في الزمنِ الماضي
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $afaalChapter->id,
                'tier1_question' => 'أيُّ الكلماتِ التاليةِ تُعتبَرُ حرفًا؟',
                'tier1_options' => json_encode([
                    'قَلَمٌ',
                    'مِنْ',
                    'يَجلِسُ',
                    'هُوَ',
                    'مَسجِدٌ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // مِنْ
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'اسمُ جمادٍ',
                    'يربطُ بينَ الكلماتِ',
                    'فعلٌ مضارعٌ',
                    'ضميرٌ غائبٌ',
                    'اسمُ مكانٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // يربطُ بينَ الكلماتِ
                'difficulty' => 'mudah',
                'created_by' => 2,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }

    private function createQuestionsForSemesterGenap()
    {
        // Ambil chapter untuk kelas X semester genap
        $tarkibChapter = Chapter::where('name', 'التركيب الإضافي')->where('grade', 'X')->where('semester', 'genap')->first();
        $muhadatsahChapter = Chapter::where('name', 'المحادثة اليومية')->where('grade', 'X')->where('semester', 'genap')->first();

        $questions = [
            [
                'chapter_id' => $tarkibChapter->id,
                'tier1_question' => 'أيُّ الأفعالِ التاليةِ يُعتبَرُ فعلاً مُضارِعًا؟',
                'tier1_options' => json_encode([
                    'كَتَبَ',
                    'اِكتُبْ',
                    'يَكتُبُ',
                    'كُتِبَ',
                    'كاتِبٌ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // يَكتُبُ
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'يدلُّ على حدثٍ في الزمنِ الماضي',
                    'فعلُ أمرٍ',
                    'يدلُّ على حدثٍ في الزمنِ الحاضرِ أَوِ المُستقبلِ',
                    'فعلٌ مبنيٌّ للمجهولِ',
                    'اسمُ فاعلٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // يدلُّ على حدثٍ في الزمنِ الحاضرِ أَوِ المُستقبلِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $tarkibChapter->id,
                'tier1_question' => 'أيُّ الأفعالِ التاليةِ يُعتبَرُ فعلاً ماضِيًا؟',
                'tier1_options' => json_encode([
                    'يَذهَبُ',
                    'اِذهَبْ',
                    'ذَهَبَ',
                    'مَذهَبٌ',
                    'ذَاهِبٌ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // ذَهَبَ
                'tier2_question' => 'لأنَّهُ...',
                'tier2_options' => json_encode([
                    'فعلٌ مُضارِعٌ',
                    'فعلُ أمرٍ',
                    'يدلُّ على حدثٍ في الزمنِ الماضي',
                    'اسمُ مكانٍ',
                    'اسمُ فاعلٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // يدلُّ على حدثٍ في الزمنِ الماضي
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'كلمةُ "كَتَبَتْ" تُعتَبَرُ فعلاً...',
                'tier1_options' => json_encode([
                    'مُذَكَّرًا',
                    'مُؤَنَّثًا',
                    'مُتَكَلِّمًا',
                    'مُخَاطَبًا',
                    'أَمْرًا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // مُؤَنَّثًا
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على الفاعلِ المُذَكَّرِ',
                    'تدلُّ على الفاعلِ المُؤَنَّثِ',
                    'تدلُّ على المُتَكَلِّمِ',
                    'تدلُّ على المُخَاطَبِ',
                    'تدلُّ على طلبِ الفعلِ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // تدلُّ على الفاعلِ المُؤَنَّثِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'كلمةُ "قَرَأُوا" تُعتَبَرُ فعلاً...',
                'tier1_options' => json_encode([
                    'مُذَكَّرًا',
                    'مُؤَنَّثًا',
                    'مُتَكَلِّمًا',
                    'مُخَاطَبًا',
                    'أَمْرًا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // مُذَكَّرًا
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على الفاعلِ المُذَكَّرِ',
                    'تدلُّ على الفاعلِ المُؤَنَّثِ',
                    'تدلُّ على المُتَكَلِّمِ',
                    'تدلُّ على المُخَاطَبِ',
                    'تدلُّ على طلبِ الفعلِ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 0, // تدلُّ على الفاعلِ المُذَكَّرِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'أيُّ الكلماتِ التاليةِ تُعتَبَرُ أداةَ استفهامٍ؟',
                'tier1_options' => json_encode([
                    'في',
                    'لَمْ',
                    'مَتَى',
                    'ثُمَّ',
                    'عِنْدَ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // مَتَى
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'حرفُ جرٍّ',
                    'حرفُ جزمٍ',
                    'تُستخدَمُ للسؤالِ عن الزمانِ',
                    'حرفُ عطفٍ',
                    'ظرفُ مكانٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // تُستخدَمُ للسؤالِ عن الزمانِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'أيُّ الكلماتِ التاليةِ تُعتَبَرُ أداةَ استفهامٍ؟',
                'tier1_options' => json_encode([
                    'أَيْنَ',
                    'قَدْ',
                    'أَوْ',
                    'لَكِنْ',
                    'بَعْدَ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // أَيْنَ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تُستخدَمُ للسؤالِ عن المكانِ',
                    'حرفُ تحقيقٍ',
                    'حرفُ عطفٍ',
                    'حرفُ استدراكٍ',
                    'ظرفُ زمانٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 0, // تُستخدَمُ للسؤالِ عن المكانِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $tarkibChapter->id,
                'tier1_question' => 'كلمةُ "أَمَامَ" تُعتَبَرُ...',
                'tier1_options' => json_encode([
                    'ظرفَ زمانٍ',
                    'ظرفَ مكانٍ',
                    'حرفَ جرٍّ',
                    'حرفَ عطفٍ',
                    'اسمًا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // ظرفَ مكانٍ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على الزمانِ',
                    'تدلُّ على المكانِ',
                    'تربطُ بينَ الأسماءِ',
                    'تربطُ بينَ الجُمَلِ',
                    'تدلُّ على شيءٍ ملموسٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // تدلُّ على المكانِ
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $tarkibChapter->id,
                'tier1_question' => 'كلمةُ "صَبَاحًا" تُعتَبَرُ...',
                'tier1_options' => json_encode([
                    'ظرفَ زمانٍ',
                    'ظرفَ مكانٍ',
                    'حرفَ جرٍّ',
                    'حرفَ عطفٍ',
                    'اسمًا'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 0, // ظرفَ زمانٍ
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على الزمانِ',
                    'تدلُّ على المكانِ',
                    'تربطُ بينَ الأسماءِ',
                    'تربطُ بينَ الجُمَلِ',
                    'تدلُّ على شيءٍ ملموسٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 0, // تدلُّ على الزمانِ
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'كلمةُ "يَجْلِسُ" تُعتَبَرُ فعلاً...',
                'tier1_options' => json_encode([
                    'ماضيًا',
                    'مُضارِعًا',
                    'أَمْرًا',
                    'اسمَ فاعلٍ',
                    'اسمَ مفعولٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 1, // مُضارِعًا
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على حدثٍ في الزمنِ الماضي',
                    'تدلُّ على حدثٍ في الزمنِ الحاضرِ أَوِ المُستقبلِ',
                    'تدلُّ على طلبِ الفعلِ',
                    'تدلُّ على مَنْ قامَ بالفعلِ',
                    'تدلُّ على مَنْ وقعَ عليهِ الفعلُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 1, // تدلُّ على حدثٍ في الزمنِ الحاضرِ أَوِ المُستقبلِ
                'difficulty' => 'mudah',
                'created_by' => 2,
            ],
            [
                'chapter_id' => $muhadatsahChapter->id,
                'tier1_question' => 'كلمةُ "اِشْرَبْ" تُعتَبَرُ فعلاً...',
                'tier1_options' => json_encode([
                    'ماضيًا',
                    'مُضارِعًا',
                    'أَمْرًا',
                    'اسمَ فاعلٍ',
                    'اسمَ مفعولٍ'
                ],JSON_UNESCAPED_UNICODE),
                'tier1_correct_answer' => 2, // أَمْرًا
                'tier2_question' => 'لأنَّها...',
                'tier2_options' => json_encode([
                    'تدلُّ على حدثٍ في الزمنِ الماضي',
                    'تدلُّ على حدثٍ في الزمنِ الحاضرِ أَوِ المُستقبلِ',
                    'تدلُّ على طلبِ الفعلِ',
                    'تدلُّ على مَنْ قامَ بالفعلِ',
                    'تدلُّ على مَنْ وقعَ عليهِ الفعلُ'
                ],JSON_UNESCAPED_UNICODE),
                'tier2_correct_answer' => 2, // تدلُّ على طلبِ الفعلِ
                'difficulty' => 'sedang',
                'created_by' => 2,
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
