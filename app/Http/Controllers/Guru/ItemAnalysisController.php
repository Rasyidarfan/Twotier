<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\ItemAnalysisService;
use Illuminate\Http\Request;

class ItemAnalysisController extends Controller
{
    protected $itemAnalysisService;

    public function __construct(ItemAnalysisService $itemAnalysisService)
    {
        $this->itemAnalysisService = $itemAnalysisService;
    }

    /**
     * Display item analysis for an exam
     */
    public function index(Exam $exam)
    {
        // Ensure the exam belongs to the authenticated teacher
        if ($exam->created_by !== auth()->id()) {
            abort(403, 'Unauthorized access to this exam');
        }

        // Get comprehensive item analysis
        $analysis = $this->itemAnalysisService->getItemAnalysisSummary($exam->id);

        return view('guru.exams.item-analysis', [
            'exam' => $exam,
            'analysis' => $analysis,
        ]);
    }
}
