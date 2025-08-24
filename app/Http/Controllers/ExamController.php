<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\StudentExamSession;

class ExamController extends Controller
{
    public function getStatus(Exam $exam)
    {
        $this->authorize('view', $exam);

        return response()->json([
            'status' => $exam->status,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'duration_minutes' => $exam->duration_minutes,
        ]);
    }

    public function getParticipants(Exam $exam)
    {
        $this->authorize('view', $exam);

        $participants = StudentExamSession::where('exam_id', $exam->id)
            ->select(['id', 'student_name', 'status', 'started_at', 'finished_at', 'total_score'])
            ->latest()
            ->get();

        return response()->json([
            'participants' => $participants,
            'total_count' => $participants->count(),
            'in_progress_count' => $participants->where('status', 'in_progress')->count(),
            'finished_count' => $participants->where('status', 'finished')->count(),
        ]);
    }
}
