<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\StudentExamSession;
use Illuminate\Support\Facades\Cache;

class ItemAnalysisService
{
    /**
     * Get comprehensive item analysis summary for an exam
     */
    public function getItemAnalysisSummary(int $examId): array
    {
        // Cache results for 1 hour
        return Cache::remember("item_analysis_{$examId}", 3600, function () use ($examId) {
            $exam = Exam::with(['questions', 'examQuestions'])->findOrFail($examId);

            // Get all finished sessions
            $sessions = StudentExamSession::where('exam_id', $examId)
                ->where('status', 'finished')
                ->with('studentAnswers')
                ->get();

            $totalStudents = $sessions->count();

            // Warning: need at least 10 students for reliable results
            if ($totalStudents < 10) {
                // Still calculate, but will show warning in view
            }

            // Calculate reliability (Cronbach's Alpha)
            $reliability = $this->calculateReliability($examId);

            // Calculate per-item statistics
            $items = [];
            $sumValidity = 0;
            $sumDiscrimination = 0;
            $sumDifficulty = 0;

            foreach ($exam->examQuestions as $examQuestion) {
                $question = $examQuestion->question;
                $questionId = $question->id;

                $validity = $this->calculateValidity($examId, $questionId, $totalStudents);
                $discrimination = $this->calculateDiscrimination($examId, $questionId);
                $difficulty = $this->calculateDifficulty($examId, $questionId);

                $sumValidity += $validity['r_hitung'];
                $sumDiscrimination += $discrimination['value'];
                $sumDifficulty += $difficulty['value'];

                $items[$questionId] = [
                    'question_number' => $examQuestion->question_order,
                    'question_text' => strip_tags($question->tier1_question),
                    'difficulty' => [
                        'value' => $difficulty['value'],
                        'status' => $difficulty['status'],
                        'color' => $difficulty['color'],
                    ],
                    'discrimination' => [
                        'value' => $discrimination['value'],
                        'status' => $discrimination['status'],
                        'color' => $discrimination['color'],
                    ],
                    'validity' => [
                        'r_hitung' => $validity['r_hitung'],
                        'r_tabel' => $validity['r_tabel'],
                        'is_valid' => $validity['is_valid'],
                        'status' => $validity['status'],
                        'color' => $validity['color'],
                    ],
                    'recommendation' => $this->getRecommendation(
                        $validity['is_valid'],
                        $discrimination['value'],
                        $difficulty['value']
                    ),
                ];
            }

            $questionCount = count($items);

            // Build student scores matrix (0-2 scale)
            $studentScores = [];
            foreach ($sessions as $session) {
                $studentRow = [
                    'student_name' => $session->student_name,
                    'total_score' => $session->total_score,
                    'scores' => []
                ];

                foreach ($exam->examQuestions as $examQuestion) {
                    $questionId = $examQuestion->question->id;
                    $answer = $session->studentAnswers->firstWhere('question_id', $questionId);

                    $itemScore = 0;
                    if ($answer) {
                        $itemScore = match ($answer->result_category) {
                            'benar-benar' => 2,
                            'benar-salah' => 1,
                            'salah-benar' => 1,
                            'salah-salah' => 0,
                            default => 0
                        };
                    }

                    $studentRow['scores'][$examQuestion->question_order] = $itemScore;
                }

                $studentScores[] = $studentRow;
            }

            return [
                'reliability' => [
                    'alpha' => $reliability['alpha'],
                    'interpretation' => $reliability['interpretation'],
                    'color' => $reliability['color'],
                ],
                'statistics' => [
                    'total_students' => $totalStudents,
                    'total_questions' => $questionCount,
                    'avg_validity' => $questionCount > 0 ? $sumValidity / $questionCount : 0,
                    'avg_discrimination' => $questionCount > 0 ? $sumDiscrimination / $questionCount : 0,
                    'avg_difficulty' => $questionCount > 0 ? $sumDifficulty / $questionCount : 0,
                ],
                'items' => $items,
                'student_scores' => $studentScores,
            ];
        });
    }

    /**
     * Calculate validity (item-total correlation) for a specific question
     */
    private function calculateValidity(int $examId, int $questionId, int $totalStudents): array
    {
        // Get item scores (0-2 scale)
        $sessions = StudentExamSession::where('exam_id', $examId)
            ->where('status', 'finished')
            ->with(['studentAnswers' => function ($query) use ($questionId) {
                $query->where('question_id', $questionId);
            }])
            ->orderBy('id')
            ->get();

        $itemScores = [];
        $totalScores = [];

        foreach ($sessions as $session) {
            $answer = $session->studentAnswers->first();

            $itemScore = 0;
            if ($answer) {
                $itemScore = match ($answer->result_category) {
                    'benar-benar' => 2,
                    'benar-salah' => 1,
                    'salah-benar' => 1,
                    'salah-salah' => 0,
                    default => 0
                };
            }

            $itemScores[] = $itemScore;
            $totalScores[] = $session->total_score;
        }

        // Calculate Pearson correlation
        $rHitung = $this->pearsonCorrelation($itemScores, $totalScores);

        // Get r table value
        $df = $totalStudents - 2;
        $rTabel = $this->getRTableValue($df);

        // Determine validity
        $isValid = $rHitung > $rTabel;

        return [
            'r_hitung' => round($rHitung, 3),
            'r_tabel' => round($rTabel, 3),
            'is_valid' => $isValid,
            'status' => $isValid ? 'Valid' : 'Tidak Valid',
            'color' => $isValid ? 'success' : 'danger',
        ];
    }

    /**
     * Calculate reliability (Cronbach's Alpha) for the entire exam
     */
    private function calculateReliability(int $examId): array
    {
        $exam = Exam::with('examQuestions')->findOrFail($examId);

        $sessions = StudentExamSession::where('exam_id', $examId)
            ->where('status', 'finished')
            ->with('studentAnswers')
            ->get();

        if ($sessions->count() < 2) {
            return [
                'alpha' => 0,
                'interpretation' => 'Data Tidak Cukup',
                'color' => 'secondary',
            ];
        }

        // Calculate variance per item
        $itemVariances = [];

        foreach ($exam->examQuestions as $examQuestion) {
            $questionId = $examQuestion->question->id;

            $itemScores = $sessions->map(function ($session) use ($questionId) {
                $answer = $session->studentAnswers->firstWhere('question_id', $questionId);

                if (!$answer) {
                    return 0;
                }

                return match ($answer->result_category) {
                    'benar-benar' => 2,
                    'benar-salah' => 1,
                    'salah-benar' => 1,
                    'salah-salah' => 0,
                    default => 0
                };
            })->toArray();

            $itemVariances[] = $this->variance($itemScores);
        }

        // Calculate total variance
        $totalScores = $sessions->pluck('total_score')->toArray();
        $totalVariance = $this->variance($totalScores);

        // Calculate Cronbach's Alpha
        $k = count($itemVariances);

        if ($k < 2 || $totalVariance == 0) {
            return [
                'alpha' => 0,
                'interpretation' => 'Data Tidak Cukup',
                'color' => 'secondary',
            ];
        }

        $sumItemVariance = array_sum($itemVariances);
        $alpha = ($k / ($k - 1)) * (1 - ($sumItemVariance / $totalVariance));

        // Interpretation
        $isReliable = $alpha > 0.6;

        return [
            'alpha' => round($alpha, 3),
            'interpretation' => $isReliable ? 'Reliabel' : 'Tidak Reliabel',
            'color' => $isReliable ? 'success' : 'danger',
        ];
    }

    /**
     * Calculate discrimination index for a specific question
     */
    private function calculateDiscrimination(int $examId, int $questionId): array
    {
        // Get sessions ordered by total score (descending)
        $sessions = StudentExamSession::where('exam_id', $examId)
            ->where('status', 'finished')
            ->orderBy('total_score', 'desc')
            ->with(['studentAnswers' => function ($query) use ($questionId) {
                $query->where('question_id', $questionId);
            }])
            ->get();

        if ($sessions->count() < 2) {
            return [
                'value' => 0,
                'status' => 'Data Tidak Cukup',
                'color' => 'secondary',
            ];
        }

        // Split into upper 50% and lower 50%
        $upperCount = (int) floor($sessions->count() / 2);
        $upperGroup = $sessions->take($upperCount);
        $lowerGroup = $sessions->reverse()->take($upperCount);

        // Calculate average score for upper group (0-2 scale)
        $upperScoreSum = $upperGroup->sum(function ($session) {
            $answer = $session->studentAnswers->first();

            if (!$answer) {
                return 0;
            }

            return match ($answer->result_category) {
                'benar-benar' => 2,
                'benar-salah' => 1,
                'salah-benar' => 1,
                'salah-salah' => 0,
                default => 0
            };
        });

        // Calculate average score for lower group (0-2 scale)
        $lowerScoreSum = $lowerGroup->sum(function ($session) {
            $answer = $session->studentAnswers->first();

            if (!$answer) {
                return 0;
            }

            return match ($answer->result_category) {
                'benar-benar' => 2,
                'benar-salah' => 1,
                'salah-benar' => 1,
                'salah-salah' => 0,
                default => 0
            };
        });

        $upperAvg = $upperCount > 0 ? $upperScoreSum / $upperCount : 0;
        $lowerAvg = $upperCount > 0 ? $lowerScoreSum / $upperCount : 0;

        // Calculate discrimination index (normalize to 0-1 range)
        $discrimination = ($upperAvg - $lowerAvg) / 2;

        // Interpretation
        $interpretation = $this->interpretDiscrimination($discrimination);

        return [
            'value' => round($discrimination, 3),
            'status' => $interpretation['status'],
            'color' => $interpretation['color'],
        ];
    }

    /**
     * Calculate difficulty index for a specific question
     */
    private function calculateDifficulty(int $examId, int $questionId): array
    {
        $sessions = StudentExamSession::where('exam_id', $examId)
            ->where('status', 'finished')
            ->with(['studentAnswers' => function ($query) use ($questionId) {
                $query->where('question_id', $questionId);
            }])
            ->get();

        if ($sessions->count() == 0) {
            return [
                'value' => 0,
                'status' => 'Data Tidak Cukup',
                'color' => 'secondary',
            ];
        }

        // Calculate total score (0-2 scale)
        $totalScore = $sessions->sum(function ($session) {
            $answer = $session->studentAnswers->first();

            if (!$answer) {
                return 0;
            }

            return match ($answer->result_category) {
                'benar-benar' => 2,
                'benar-salah' => 1,
                'salah-benar' => 1,
                'salah-salah' => 0,
                default => 0
            };
        });

        // Calculate average and normalize to 0-1 range
        $avgScore = $totalScore / $sessions->count();
        $difficulty = $avgScore / 2; // Normalize from 0-2 scale to 0-1 scale

        // Interpretation
        $interpretation = $this->interpretDifficulty($difficulty);

        return [
            'value' => round($difficulty, 3),
            'status' => $interpretation['status'],
            'color' => $interpretation['color'],
        ];
    }

    /**
     * Get recommendation based on validity, discrimination, and difficulty
     */
    private function getRecommendation(bool $isValid, float $discrimination, float $difficulty): array
    {
        // Ideal question: Valid, good discrimination (>0.40), moderate difficulty (0.30-0.70)
        if ($isValid && $discrimination > 0.40 && $difficulty >= 0.30 && $difficulty <= 0.70) {
            return [
                'text' => 'Soal Baik - Dipertahankan',
                'color' => 'success',
            ];
        }

        // Poor question: Invalid or very poor discrimination
        if (!$isValid || $discrimination <= 0.20) {
            return [
                'text' => 'Soal Buruk - Diganti',
                'color' => 'danger',
            ];
        }

        // Otherwise: needs improvement
        return [
            'text' => 'Soal Perlu Diperbaiki',
            'color' => 'warning',
        ];
    }

    /**
     * Interpret discrimination index value
     */
    private function interpretDiscrimination(float $d): array
    {
        if ($d > 0.70) {
            return ['status' => 'Sangat Baik', 'color' => 'success'];
        } elseif ($d > 0.40) {
            return ['status' => 'Baik', 'color' => 'success'];
        } elseif ($d > 0.20) {
            return ['status' => 'Sedang', 'color' => 'warning'];
        } elseif ($d > 0.00) {
            return ['status' => 'Jelek', 'color' => 'danger'];
        } else {
            return ['status' => 'Sangat Jelek', 'color' => 'danger'];
        }
    }

    /**
     * Interpret difficulty index value
     */
    private function interpretDifficulty(float $p): array
    {
        if ($p > 0.70) {
            return ['status' => 'Mudah', 'color' => 'info'];
        } elseif ($p >= 0.30) {
            return ['status' => 'Sedang', 'color' => 'success'];
        } else {
            return ['status' => 'Sukar', 'color' => 'warning'];
        }
    }

    /**
     * Calculate Pearson correlation coefficient
     */
    private function pearsonCorrelation(array $x, array $y): float
    {
        $n = count($x);

        if ($n < 2 || $n !== count($y)) {
            return 0;
        }

        $meanX = $this->mean($x);
        $meanY = $this->mean($y);

        $numerator = 0;
        $sumSqX = 0;
        $sumSqY = 0;

        for ($i = 0; $i < $n; $i++) {
            $diffX = $x[$i] - $meanX;
            $diffY = $y[$i] - $meanY;
            $numerator += $diffX * $diffY;
            $sumSqX += $diffX * $diffX;
            $sumSqY += $diffY * $diffY;
        }

        $denominator = sqrt($sumSqX * $sumSqY);

        return $denominator != 0 ? $numerator / $denominator : 0;
    }

    /**
     * Calculate mean (average) of an array
     */
    private function mean(array $data): float
    {
        return count($data) > 0 ? array_sum($data) / count($data) : 0;
    }

    /**
     * Calculate variance of an array
     */
    private function variance(array $data): float
    {
        $n = count($data);

        if ($n < 2) {
            return 0;
        }

        $mean = $this->mean($data);
        $sumSquares = array_sum(array_map(fn($x) => pow($x - $mean, 2), $data));

        return $sumSquares / $n;
    }

    /**
     * Get r table value for given degrees of freedom (df = n - 2)
     * For α = 5% (0.05) one-tailed test
     */
    private function getRTableValue(int $df): float
    {
        // r table for α = 5% one-tailed test
        $rTable = [
            1 => 0.988, 2 => 0.900, 3 => 0.805, 4 => 0.729, 5 => 0.669,
            6 => 0.622, 7 => 0.582, 8 => 0.549, 9 => 0.521, 10 => 0.497,
            11 => 0.476, 12 => 0.458, 13 => 0.441, 14 => 0.426, 15 => 0.412,
            16 => 0.400, 17 => 0.389, 18 => 0.378, 19 => 0.369, 20 => 0.360,
            21 => 0.352, 22 => 0.344, 23 => 0.337, 24 => 0.330, 25 => 0.323,
            26 => 0.317, 27 => 0.311, 28 => 0.306, 29 => 0.301, 30 => 0.296,
            35 => 0.275, 40 => 0.257, 45 => 0.243, 50 => 0.231, 60 => 0.211,
            70 => 0.195, 80 => 0.183, 90 => 0.173, 100 => 0.164, 120 => 0.150,
            150 => 0.134, 200 => 0.116, 300 => 0.095, 400 => 0.082, 500 => 0.073,
            1000 => 0.052
        ];

        // Direct lookup if exists
        if (isset($rTable[$df])) {
            return $rTable[$df];
        }

        // For df > 1000, use approximation formula
        if ($df > 1000) {
            return 1.645 / sqrt($df);
        }

        // Linear interpolation for missing values
        $keys = array_keys($rTable);
        $lower = null;
        $upper = null;

        foreach ($keys as $key) {
            if ($key < $df) {
                $lower = $key;
            } elseif ($key > $df && $upper === null) {
                $upper = $key;
                break;
            }
        }

        if ($lower !== null && $upper !== null) {
            // Linear interpolation
            $r1 = $rTable[$lower];
            $r2 = $rTable[$upper];
            return $r1 + ($r2 - $r1) * ($df - $lower) / ($upper - $lower);
        }

        // Fallback to nearest value
        return $rTable[$keys[count($keys) - 1]];
    }

    /**
     * Clear cache for specific exam
     */
    public function clearCache(int $examId): void
    {
        Cache::forget("item_analysis_{$examId}");
    }
}
