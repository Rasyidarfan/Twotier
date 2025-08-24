<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $dhamairChapter = Chapter::where('name', 'الضمائر (Dhama\'ir)')->first();
        $marifaNakiraChapter = Chapter::where('name', 'الأسماء المعرفة والنكرة')->first();

        // Soal-soal Dhama'ir
        $dhamairQuestions = [
            [
                'tier1_question' => 'أي الضمائر التالية هو ضمير منفصل؟',
                'tier1_options' => [
                    'أ. هُ-',
                    'ب. كَ-',
                    'ج. أنا',
                    'د. ي-',
                    'ه. نا-'
                ],
                'tier1_correct_answer' => 2,
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الأول:',
                'tier2_options' => [
                    'أ. لأنه يتصل بالكلمة',
                    'ب. لأنه يدل على الملكية',
                    'ج. لأنه يأتي منفصلاً عن الكلمة',
                    'د. لأنه يدل على المتكلم',
                    'ه. لأنه يستخدم للغائب'
                ],
                'tier2_correct_answer' => 2,
                'difficulty' => 'mudah'
            ],
            [
                'tier1_question' => 'أي الضمائر التالية هو ضمير متصل؟',
                'tier1_options' => [
                    'أ. أنتَ',
                    'ب. هُنَّ',
                    'ج. هُمَا',
                    'د. كُم-',
                    'ه. هي'
                ],
                'tier1_correct_answer' => 3,
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الثاني:',
                'tier2_options' => [
                    'أ. لأنه يأتي منفصلاً',
                    'ب. لأنه يتصل بالفعل أو الاسم',
                    'ج. لأنه يستخدم للغائب',
                    'د. لأنه يستخدم للمخاطب',
                    'ه. لأنه يدل على الجمع'
                ],
                'tier2_correct_answer' => 1,
                'difficulty' => 'mudah'
            ],
            [
                'tier1_question' => 'في جملة "كتابُكَ جديدٌ"، ما نوع الضمير "كَ"؟',
                'tier1_options' => [
                    'أ. ضمير منفصل',
                    'ب. ضمير متصل',
                    'ج. ضمير مستتر',
                    'د. اسم ظاهر',
                    'ه. اسم إشارة'
                ],
                'tier1_correct_answer' => 1,
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الثالث:',
                'tier2_options' => [
                    'أ. لأنه يدل على المخاطب منفصلاً',
                    'ب. لأنه اتصل بالاسم "كتابُ"',
                    'ج. لأنه غير ظاهر في الجملة',
                    'د. لأنه ليس ضميراً',
                    'ه. لأنه يدل على الغائب'
                ],
                'tier2_correct_answer' => 1,
                'difficulty' => 'sedang'
            ]
        ];

        foreach ($dhamairQuestions as $questionData) {
            Question::create([
                'chapter_id' => $dhamairChapter->id,
                'tier1_question' => $questionData['tier1_question'],
                'tier1_options' => $questionData['tier1_options'],
                'tier1_correct_answer' => $questionData['tier1_correct_answer'],
                'tier2_question' => $questionData['tier2_question'],
                'tier2_options' => $questionData['tier2_options'],
                'tier2_correct_answer' => $questionData['tier2_correct_answer'],
                'difficulty' => $questionData['difficulty'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }

        // Soal-soal Ma'rifa dan Nakira
        $marifaNakiraQuestions = [
            [
                'tier1_question' => 'أي الكلمات التالية هي اسم نكرة؟',
                'tier1_options' => [
                    'أ. الكتاب',
                    'ب. المدرسة',
                    'ج. قلم',
                    'د. البيت',
                    'ه. الطالب'
                ],
                'tier1_correct_answer' => 2,
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الأول:',
                'tier2_options' => [
                    'أ. لأنه معرف بأل التعريف',
                    'ب. لأنه يدل على شيء معين',
                    'ج. لأنه يدل على شيء غير معين',
                    'د. لأنه اسم علم',
                    'ه. لأنه اسم إشارة'
                ],
                'tier2_correct_answer' => 2,
                'difficulty' => 'mudah'
            ],
            [
                'tier1_question' => 'أي الكلمات التالية هي اسم معرفة؟',
                'tier1_options' => [
                    'أ. رجل',
                    'ب. مدينة',
                    'ج. الشمس',
                    'د. بيت',
                    'ه. سيارة'
                ],
                'tier1_correct_answer' => 2,
                'tier2_question' => 'اختر السبب الصحيح لاختيارك في السؤال الثاني:',
                'tier2_options' => [
                    'أ. لأنه لا يحتوي على أل التعريف',
                    'ب. لأنه يدل على شيء غير محدد',
                    'ج. لأنه يحتوي على أل التعريف',
                    'د. لأنه اسم نكرة',
                    'ه. لأنه اسم مبهم'
                ],
                'tier2_correct_answer' => 2,
                'difficulty' => 'mudah'
            ]
        ];

        foreach ($marifaNakiraQuestions as $questionData) {
            Question::create([
                'chapter_id' => $marifaNakiraChapter->id,
                'tier1_question' => $questionData['tier1_question'],
                'tier1_options' => $questionData['tier1_options'],
                'tier1_correct_answer' => $questionData['tier1_correct_answer'],
                'tier2_question' => $questionData['tier2_question'],
                'tier2_options' => $questionData['tier2_options'],
                'tier2_correct_answer' => $questionData['tier2_correct_answer'],
                'difficulty' => $questionData['difficulty'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }
    }
}
