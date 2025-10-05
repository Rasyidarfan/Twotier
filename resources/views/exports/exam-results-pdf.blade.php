<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Ujian - {{ $exam->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 22px;
            margin: 5px 0;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding: 8px;
            background: #4a5568;
            color: white;
            border-radius: 4px;
        }
        .info-section {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-row {
            margin: 3px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stats-row {
            display: table-column;
        }
        .stats-cell {
            display: table-cell;    
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            background: #f5f5f5;
        }
        .stats-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .chart-container {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .bar-chart {
            width: 100%;
            margin: 10px 0;
        }
        .bar-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .bar-label {
            display: table-cell;
            width: 20%;
            padding: 5px;
            font-size: 9px;
            vertical-align: middle;
        }
        .bar-container {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }
        .bar {
            height: 20px;
            display: inline-block;
            text-align: center;
            color: white;
            font-size: 8px;
            line-height: 20px;
        }
        .bar-value {
            display: table-cell;
            width: 10%;
            text-align: right;
            padding: 5px;
            font-size: 9px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
            page-break-inside: avoid;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2d3748;
        }
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #48bb78;
            color: white;
        }
        .badge-danger {
            background-color: #f56565;
            color: white;
        }
        .badge-info {
            background-color: #4299e1;
            color: white;
        }
        .badge-warning {
            background-color: #ed8936;
            color: white;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL UJIAN</h1>
        <h2>{{ $exam->title }}</h2>
        <p style="margin: 5px 0;">Kode Ujian: <strong>{{ $exam->code }}</strong> | Mata Pelajaran: {{ $exam->subject->name ?? '-' }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Kelas/Semester:</span>
            {{ $exam->grade }} / {{ ucfirst($exam->semester) }}
        </div>
        <div class="info-row">
            <span class="info-label">Durasi:</span>
            {{ $exam->duration_minutes }} menit
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Soal:</span>
            {{ $exam->examQuestions->count() }} soal
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Export:</span>
            {{ $generatedAt }}
        </div>
    </div>

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <div class="stats-label">Total Peserta</div>
                <div class="stats-value">{{ $totalParticipants }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Tingkat Kelulusan</div>
                <div class="stats-value">{{ $passRate }}%</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Rata-rata Nilai</div>
                <div class="stats-value">{{ $averageScore }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Nilai Tertinggi</div>
                <div class="stats-value">{{ $highestScore }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Lulus</div>
                <div class="stats-value" style="color: #48bb78;">{{ $passedCount }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Tidak Lulus</div>
                <div class="stats-value" style="color: #f56565;">{{ $failedCount }}</div>
            </div>
        </div>
    </div>

    <!-- Distribusi Nilai -->
    <div class="section-title">1. Distribusi Nilai Peserta</div>
    <div class="chart-container">
        <table style="width: 100%; border: 1px solid #ddd;">
            <thead>
                <tr>
                    <th style="width: 25%; text-align: center;">Range Nilai</th>
                    <th style="width: 15%; text-align: center;">Jumlah</th>
                    <th style="width: 60%; text-align: left;">Distribusi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxValue = max($scoresDistribution) ?: 1;
                    $ranges = ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'];
                    $colors = ['#f56565', '#ed8936', '#ecc94b', '#4299e1', '#48bb78'];
                @endphp
                @foreach($scoresDistribution as $index => $count)
                <tr>
                    <td class="text-center">{{ $ranges[$index] }}</td>
                    <td class="text-center"><strong>{{ $count }}</strong></td>
                    <td>
                        <div style="background: {{ $colors[$index] }}; width: {{ ($count / $maxValue) * 100 }}%; height: 20px; display: inline-block; color: white; text-align: center; line-height: 20px; font-size: 8px;">
                            {{ $count > 0 ? $count : '' }}
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f5f5f5; font-weight: bold;">
                    <td class="text-center">TOTAL</td>
                    <td class="text-center">{{ array_sum($scoresDistribution) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Kategori Jawaban Keseluruhan -->
<div class="section-title">2. Kategori Jawaban Keseluruhan</div>
<div class="chart-container">
    <table style="width: 100%; border: 1px solid #ddd;">
        <thead>
            <tr>
                <th style="width: 25%; text-align: center;">Paham Konsep</th>
                <th style="width: 25%; text-align: center;">Miskonsepsi</th>
                <th style="width: 25%; text-align: center;">Menebak</th>
                <th style="width: 25%; text-align: center;">Tidak Paham Konsep</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center"><strong>{{ $answerCategoryBreakdown['benar_benar'] }}</strong></td>
                <td class="text-center"><strong>{{ $answerCategoryBreakdown['benar_salah'] }}</strong></td>
                <td class="text-center"><strong>{{ $answerCategoryBreakdown['salah_benar'] }}</strong></td>
                <td class="text-center"><strong>{{ $answerCategoryBreakdown['salah_salah'] }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Stacked Bar Chart -->
    <div style="margin-top: 15px;">
        <table style="width: 100%; height: 30px; border-collapse: collapse; border: 1px solid #ddd; margin: 0;">
            <tr>
                @if($categoryPercentages['paham_konsep'] > 0)
                <td style="width: {{ $categoryPercentages['paham_konsep'] }}%; background: #48bb78; color: white; text-align: center; font-size: 10px; font-weight: bold; padding: 0; border: none;">
                    {{ $categoryPercentages['paham_konsep'] > 8 ? $categoryPercentages['paham_konsep'].'%' : '&nbsp;' }}
                </td>
                @endif
                @if($categoryPercentages['miskonsepsi'] > 0)
                <td style="width: {{ $categoryPercentages['miskonsepsi'] }}%; background: #ed8936; color: white; text-align: center; font-size: 10px; font-weight: bold; padding: 0; border: none;">
                    {{ $categoryPercentages['miskonsepsi'] > 8 ? $categoryPercentages['miskonsepsi'].'%' : '&nbsp;' }}
                </td>
                @endif
                @if($categoryPercentages['menebak'] > 0)
                <td style="width: {{ $categoryPercentages['menebak'] }}%; background: #4299e1; color: white; text-align: center; font-size: 10px; font-weight: bold; padding: 0; border: none;">
                    {{ $categoryPercentages['menebak'] > 8 ? $categoryPercentages['menebak'].'%' : '&nbsp;' }}
                </td>
                @endif
                @if($categoryPercentages['tidak_paham'] > 0)
                <td style="width: {{ $categoryPercentages['tidak_paham'] }}%; background: #f56565; color: white; text-align: center; font-size: 10px; font-weight: bold; padding: 0; border: none;">
                    {{ $categoryPercentages['tidak_paham'] > 8 ? $categoryPercentages['tidak_paham'].'%' : '&nbsp;' }}
                </td>
                @endif
            </tr>
        </table>
    </div>
</div>

<!-- Analisis Per Bab -->
<div class="section-title">3. Analisis Jawaban Per Bab</div>
<table>
    <thead>
        <tr>
            <th style="width: 25%;">Nama Bab</th>
            <th class="text-center" style="width: 8%;">PK</th>
            <th class="text-center" style="width: 8%;">MK</th>
            <th class="text-center" style="width: 8%;">MB</th>
            <th class="text-center" style="width: 8%;">TP</th>
            <th class="text-center" style="width: 8%;">Total</th>
            <th style="width: 35%;">Distribusi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($chapterBreakdown as $chapter)
        @php
            $total = $chapter['benar_benar'] + $chapter['benar_salah'] + $chapter['salah_benar'] + $chapter['salah_salah'];

            // Calculate percentages for this chapter
            $pkPercent = $total > 0 ? round(($chapter['benar_benar'] / $total) * 100, 1) : 0;
            $mkPercent = $total > 0 ? round(($chapter['benar_salah'] / $total) * 100, 1) : 0;
            $mbPercent = $total > 0 ? round(($chapter['salah_benar'] / $total) * 100, 1) : 0;
            $tpPercent = $total > 0 ? round(($chapter['salah_salah'] / $total) * 100, 1) : 0;
        @endphp
        <tr>
            <td>{{ $chapter['chapter_name'] }}</td>
            <td class="text-center"><span class="badge badge-success">{{ $chapter['benar_benar'] }}</span></td>
            <td class="text-center"><span class="badge badge-warning">{{ $chapter['benar_salah'] }}</span></td>
            <td class="text-center"><span class="badge badge-info">{{ $chapter['salah_benar'] }}</span></td>
            <td class="text-center"><span class="badge badge-danger">{{ $chapter['salah_salah'] }}</span></td>
            <td class="text-center"><strong>{{ $total }}</strong></td>
            <td style="padding: 2px;">
                @if($total > 0)
                <table style="width: 100%; height: 24px; border-collapse: collapse; border: 1px solid #ddd; margin: 0;">
                    <tr>
                        @if($pkPercent > 0)
                        <td style="width: {{ $pkPercent }}%; background: #48bb78; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                            {{ $pkPercent > 10 ? $pkPercent.'%' : '&nbsp;' }}
                        </td>
                        @endif
                        @if($mkPercent > 0)
                        <td style="width: {{ $mkPercent }}%; background: #ed8936; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                            {{ $mkPercent > 10 ? $mkPercent.'%' : '&nbsp;' }}
                        </td>
                        @endif
                        @if($mbPercent > 0)
                        <td style="width: {{ $mbPercent }}%; background: #4299e1; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                            {{ $mbPercent > 10 ? $mbPercent.'%' : '&nbsp;' }}
                        </td>
                        @endif
                        @if($tpPercent > 0)
                        <td style="width: {{ $tpPercent }}%; background: #f56565; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                            {{ $tpPercent > 10 ? $tpPercent.'%' : '&nbsp;' }}
                        </td>
                        @endif
                    </tr>
                </table>
                @else
                <div style="text-align: center; color: #999; font-size: 8px;">Tidak ada data</div>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Legend for Chapter Breakdown -->
<div style="margin-top: 10px; padding: 8px; background: #f9f9f9; border-radius: 4px;">
    <div style="font-size: 8px; color: #666; margin-bottom: 5px;"><strong>Keterangan:</strong></div>
    <div style="text-align: center;">
        <span style="display: inline-block; margin: 0 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #48bb78; vertical-align: middle; margin-right: 3px;"></span>
            <span style="font-size: 8px;">PK = Paham Konsep</span>
        </span>
        <span style="display: inline-block; margin: 0 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #ed8936; vertical-align: middle; margin-right: 3px;"></span>
            <span style="font-size: 8px;">MK = Miskonsepsi</span>
        </span>
        <span style="display: inline-block; margin: 0 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #4299e1; vertical-align: middle; margin-right: 3px;"></span>
            <span style="font-size: 8px;">MB = Menebak</span>
        </span>
        <span style="display: inline-block; margin: 0 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #f56565; vertical-align: middle; margin-right: 3px;"></span>
            <span style="font-size: 8px;">TP = Tidak Paham Konsep</span>
        </span>
    </div>
</div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Analisis Per Soal -->
    <div class="section-title">4. Analisis Detail Per Soal</div>

    @foreach($questionAnalysis as $index => $analysis)
    @php
        $question = $analysis['question'];
        $tier1Options = is_array($question->tier1_options) ? $question->tier1_options : json_decode($question->tier1_options, true);
        $tier2Options = is_array($question->tier2_options) ? $question->tier2_options : json_decode($question->tier2_options, true);
        $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];

        // Category percentages for this question
        $totalAns = $analysis['total_answers'];
        $pkPct = $totalAns > 0 ? round(($analysis['benar_benar'] / $totalAns) * 100, 1) : 0;
        $mkPct = $totalAns > 0 ? round(($analysis['benar_salah'] / $totalAns) * 100, 1) : 0;
        $mbPct = $totalAns > 0 ? round(($analysis['salah_benar'] / $totalAns) * 100, 1) : 0;
        $tpPct = $totalAns > 0 ? round(($analysis['salah_salah'] / $totalAns) * 100, 1) : 0;
    @endphp

    <div style="border: 2px solid #4a5568; border-radius: 6px; padding: 12px; margin-bottom: 15px; page-break-inside: avoid;">
        <!-- Question Header -->
        <div style="background: #4a5568; color: white; padding: 8px; margin: -12px -12px 12px -12px; border-radius: 4px 4px 0 0;">
            <strong>Soal {{ $index + 1 }}</strong>
            <span style="float: right; font-size: 9px;">
                Bab: <span dir="rtl">{{ $question->chapter->name ?? 'N/A' }}</span> |
                Total Jawaban: {{ $totalAns }} siswa
            </span>
        </div>

        <!-- Tier 1 Question -->
        <div style="margin-bottom: 12px;">
            <div style="background: #4299e1; color: white; padding: 6px; font-weight: bold; font-size: 10px; border-radius: 3px; text-align: right;">
                <span dir="rtl">المستوى الأول</span> (Tier 1)
            </div>
            <div style="padding: 8px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 3px; margin-top: 5px;">
                <p style="font-size: 11px; margin: 5px 0; text-align: right;" dir="rtl">{{ $question->tier1_question }}</p>

                <!-- Tier 1 Options -->
                <table style="width: 100%; margin-top: 8px; font-size: 10px;">
                    @foreach($tier1Options as $key => $option)
                    @php
                        $isCorrect = (string)$question->tier1_correct_answer === (string)$key;
                        $percentage = $analysis['tier1_percentages'][$key] ?? 0;
                    @endphp
                    <tr>
                        <td style="width: 8%; text-align: right; padding: 3px;">
                            @if($isCorrect)
                            <span style="background: #48bb78; color: white; padding: 3px 6px; border-radius: 3px; font-weight: bold;">✓</span>
                            @else
                            <span style="padding: 3px 6px;" dir="rtl">{{ $arabicLetters[$key] ?? $key }}</span>
                            @endif
                        </td>
                        <td style="width: 50%; padding: 3px; {{ $isCorrect ? 'background: #c6f6d5; font-weight: bold;' : '' }}; text-align: right;" dir="rtl">
                            {{ $option }}
                        </td>
                        <td style="width: 42%; padding: 3px;">
                            <div style="width: 100%; background: #e2e8f0; height: 18px; border-radius: 2px; overflow: hidden;">
                                <div style="width: {{ $percentage }}%; background: {{ $isCorrect ? '#48bb78' : '#a39080ff' }}; height: 18px; text-align: center; line-height: 18px; color: {{ $percentage > 30 ? 'white' : '#333' }}; font-weight: bold;">
                                    {{ $percentage > 0 ? $percentage.'%' : '' }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <!-- Tier 2 Question -->
        <div style="margin-bottom: 12px;">
            <div style="background: #48bb78; color: white; padding: 6px; font-weight: bold; font-size: 10px; border-radius: 3px; text-align: right;">
                <span dir="rtl">المستوى الثاني</span> (Tier 2)
            </div>
            <div style="padding: 8px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 3px; margin-top: 5px;">
                <p style="font-size: 11px; margin: 5px 0; text-align: right;" dir="rtl">{{ $question->tier2_question }}</p>

                <!-- Tier 2 Options -->
                <table style="width: 100%; margin-top: 8px; font-size: 10px;">
                    @foreach($tier2Options as $key => $option)
                    @php
                        $isCorrect = (string)$question->tier2_correct_answer === (string)$key;
                        $percentage = $analysis['tier2_percentages'][$key] ?? 0;
                    @endphp
                    <tr>
                        <td style="width: 8%; text-align: right; padding: 3px;">
                            @if($isCorrect)
                            <span style="background: #48bb78; color: white; padding: 3px 6px; border-radius: 3px; font-weight: bold;">✓</span>
                            @else
                            <span style="padding: 3px 6px;" dir="rtl">{{ $arabicLetters[$key] ?? $key }}</span>
                            @endif
                        </td>
                        <td style="width: 50%; padding: 3px; {{ $isCorrect ? 'background: #c6f6d5; font-weight: bold;' : '' }}; text-align: right;" dir="rtl">
                            {{ $option }}
                        </td>
                        <td style="width: 42%; padding: 3px;">
                            <div style="width: 100%; background: #e2e8f0; height: 18px; border-radius: 2px; overflow: hidden;">
                                <div style="width: {{ $percentage }}%; background: {{ $isCorrect ? '#48bb78' : '#a39080ff' }}; height: 18px; text-align: center; line-height: 18px; color: {{ $percentage > 30 ? 'white' : '#333' }}; font-weight: bold;">
                                    {{ $percentage > 0 ? $percentage.'%' : '' }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

<!-- Category Breakdown Stacked Bar -->
<div style="margin-top: 10px; padding: 8px; background: #f9f9f9; border-radius: 3px;">
    <div style="font-size: 8px; font-weight: bold; margin-bottom: 5px;">Kategori Kebenaran:</div>
    
    <table style="width: 100%; height: 24px; border-collapse: collapse; border: 1px solid #ccc; margin: 0;">
        <tr>
            @if($pkPct > 0)
            <td style="width: {{ $pkPct }}%; background: #48bb78; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                {{ $pkPct > 8 ? $pkPct.'%' : '&nbsp;' }}
            </td>
            @endif
            @if($mkPct > 0)
            <td style="width: {{ $mkPct }}%; background: #ed8936; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                {{ $mkPct > 8 ? $mkPct.'%' : '&nbsp;' }}
            </td>
            @endif
            @if($mbPct > 0)
            <td style="width: {{ $mbPct }}%; background: #4299e1; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                {{ $mbPct > 8 ? $mbPct.'%' : '&nbsp;' }}
            </td>
            @endif
            @if($tpPct > 0)
            <td style="width: {{ $tpPct }}%; background: #f56565; color: white; text-align: center; font-size: 7px; font-weight: bold; padding: 0; border: none;">
                {{ $tpPct > 8 ? $tpPct.'%' : '&nbsp;' }}
            </td>
            @endif
        </tr>
    </table>
    
    <div style="margin-top: 5px; font-size: 7px; text-align: center;">
        <span style="margin: 0 5px;"><span style="display: inline-block; width: 8px; height: 8px; background: #48bb78; vertical-align: middle;"></span> PK: {{ $analysis['benar_benar'] }}</span>
        <span style="margin: 0 5px;"><span style="display: inline-block; width: 8px; height: 8px; background: #ed8936; vertical-align: middle;"></span> MK: {{ $analysis['benar_salah'] }}</span>
        <span style="margin: 0 5px;"><span style="display: inline-block; width: 8px; height: 8px; background: #4299e1; vertical-align: middle;"></span> MB: {{ $analysis['salah_benar'] }}</span>
        <span style="margin: 0 5px;"><span style="display: inline-block; width: 8px; height: 8px; background: #f56565; vertical-align: middle;"></span> TP: {{ $analysis['salah_salah'] }}</span>
    </div>
</div>
    </div>

    @if(($index + 1) % 2 == 0 && ($index + 1) < count($questionAnalysis))
    <div class="page-break"></div>
    @endif
    @endforeach

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Hasil Per Siswa -->
    <div class="section-title">5. Hasil Ujian Per Siswa</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 18%;">Nama Siswa</th>
                <th style="width: 12%;">Identitas</th>
                <th style="width: 10%;">Waktu Mulai</th>
                <th style="width: 10%;">Waktu Selesai</th>
                <th style="width: 7%;">Durasi</th>
                <th class="text-center" style="width: 5%;">PK</th>
                <th class="text-center" style="width: 5%;">MK</th>
                <th class="text-center" style="width: 5%;">MB</th>
                <th class="text-center" style="width: 5%;">TP</th>
                <th class="text-center" style="width: 8%;">Skor</th>
                <th class="text-center" style="width: 7%;">%</th>
                <th class="text-center" style="width: 5%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $result['student_name'] }}</td>
                <td>{{ $result['student_identifier'] }}</td>
                <td style="font-size: 8px;">{{ $result['started_at'] }}</td>
                <td style="font-size: 8px;">{{ $result['finished_at'] }}</td>
                <td class="text-center">{{ $result['duration'] }}</td>
                <td class="text-center"><span class="badge badge-success">{{ $result['paham_konsep'] }}</span></td>
                <td class="text-center"><span class="badge badge-warning">{{ $result['miskonsepsi'] }}</span></td>
                <td class="text-center"><span class="badge badge-info">{{ $result['menebak'] }}</span></td>
                <td class="text-center"><span class="badge badge-danger">{{ $result['tidak_paham'] }}</span></td>
                <td class="text-center"><strong>{{ $result['total_score'] }}</strong></td>
                <td class="text-center"><strong>{{ $result['percentage'] }}%</strong></td>
                <td class="text-center">
                    @if($result['passed'])
                        <span class="badge badge-success">L</span>
                    @else
                        <span class="badge badge-danger">TL</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Keterangan:</strong> PK = Paham Konsep | MK = Miskonsepsi | MB = Menebak | TP = Tidak Paham Konsep | L = Lulus | TL = Tidak Lulus</p>
        <p>Dokumen ini digenerate secara otomatis oleh sistem pada {{ $generatedAt }}</p>
    </div>
</body>
</html>
