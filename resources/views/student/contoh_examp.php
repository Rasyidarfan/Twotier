<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Tier Diagnostic Test - Bahasa Arab</title>
    <style>
        :root {
            --primary-color: #6777ef;
            --secondary-color: #e3eaff;
            --success-color: #47c363;
            --danger-color: #fc544b;
            --warning-color: #ffa426;
            --info-color: #3abaf4;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
        }

        .container {
            display: flex;
            flex-direction: row-reverse;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .exam-container {
            flex: 1;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .nav-container {
            width: 250px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-left: 20px;
            margin-right: 0;
        }

        .header {
            background-color: #f0f3ff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .timer {
            background-color: var(--success-color);
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .timer i {
            margin-right: 5px;
        }

        .content {
            padding: 0;
            height: calc(100vh - 180px);
            overflow-y: auto;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            border: 3px solid #abc;
            box-shadow: inset 0 0 2.5px rgba(0, 0, 0, .5);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgb(66, 66, 66);
        }

        .question {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .question-number {
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
            display: flex;
            justify-content: space-between;
            direction: rtl;
        }

        .flag-btn {
            background: #f1f1f1;
            border: none;
            padding: 3px 6px;
            border-radius: 3px;
            cursor: pointer;
            color: #666;
        }

        .flag-btn.flagged {
            color: var(--danger-color);
        }

        .question-content {
            margin-bottom: 15px;
            line-height: 1.6;
            text-align: right;
        }

        .options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .option {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #e1e5eb;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: right;
        }

        .option:hover {
            border-color: var(--primary-color);
            background-color: var(--secondary-color);
        }

        .option.selected {
            border-color: var(--primary-color);
            background-color: var(--secondary-color);
        }

        .option-label {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .option-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            margin-right: 10px;
        }

        .option.selected .option-circle {
            background-color: var(--primary-color);
            color: white;
        }

        .second-tier {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
            display: none;
        }

        .second-tier.visible {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .second-tier-label {
            font-weight: 600;
            margin-bottom: 15px;
            color: #555;
            text-align: right;
        }

        .footer {
            padding: 15px 20px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .nav-btn {
            background-color: var(--info-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-btn i {
            margin-right: 5px;
        }

        .nav-btn:hover {
            background-color: #2c9ad1;
        }

        .nav-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Navigation sidebar */
        .nav-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 20px;
            direction: ltr;
        }

        .nav-item {
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            background-color: var(--light-color);
            color: var(--dark-color);
            cursor: pointer;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }

        .nav-item:hover {
            border-color: var(--primary-color);
        }

        .nav-item.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .nav-item.answered {
            background-color: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .nav-item.flagged {
            background-color: var(--warning-color);
            color: white;
            border-color: var(--warning-color);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #e03c3c;
        }

        .font-size-control {
            display: flex;
            align-items: center;
            margin-left: 15px;
        }

        .font-size-btn {
            background-color: var(--info-color);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 500;
            margin-left: 5px;
        }

        .font-size-btn:hover {
            background-color: #2c9ad1;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 10px;
            }

            .exam-container {
                margin-bottom: 15px;
            }

            .nav-container {
                width: 100%;
                margin-left: 0;
            }

            .content {
                height: calc(100vh - 250px);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="exam-container">
            <div class="header">
                <div class="title">Two Tier Diagnostic Test - Bahasa Arab</div>
                <div class="font-size-control">
                    <button class="font-size-btn decrease-font" onclick="changeFont(false)">a</button>
                    <button class="font-size-btn increase-font" onclick="changeFont(true)">A</button>
                </div>
                <div class="timer"><i class="fa fa-clock"></i> <span id="time">45:00</span></div>
            </div>
            <div class="content" id="exam-content">
                <!-- Questions will be dynamically loaded here -->
            </div>
            <div class="footer">
                <button class="nav-btn" id="next-btn">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                <div class="question-indicator">No: <span id="current-question">1</span></div>
                <button class="nav-btn" id="prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
            </div>
        </div>
        <div class="nav-container">
            <div class="nav-title">Navigasi Soal</div>
            <div class="nav-grid" id="question-navigator">
                <!-- Navigation items will be dynamically loaded here -->
            </div>
            <button class="submit-btn" id="submit-exam">Kumpulkan</button>
        </div>
    </div>

    <script>
        // Sample data structure for two-tier questions
        const questions = [
            {
                id: 1,
                tier1: {
                    question: "Manakah di antara kata ganti berikut yang merupakan kata ganti terpisah?",
                    options: [
                        "A. -nya",
                        "B. -mu",
                        "C. -saya",
                        "D. -ku"
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal pertama:",
                    options: [
                        "A. Karena terhubung dengan kata.",
                        "B. Karena menunjukkan kepemilikan.",
                        "C. Karena muncul terpisah dari kata.",
                        "D. Karena menunjukkan pembicara."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 2,
                tier1: {
                    question: "Manakah di antara kata ganti berikut yang merupakan kata ganti terhubung?",
                    options: [
                        "A. -engkau",
                        "B. -mereka (perempuan)",
                        "C. -mereka (dua orang)",
                        "D. -kamu (jamak)"
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal kedua:",
                    options: [
                        "A. Karena muncul terpisah.",
                        "B. Karena terhubung dengan kata kerja atau kata benda.",
                        "C. Karena digunakan untuk orang ketiga.",
                        "D. Karena digunakan untuk orang yang diajak bicara."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 3,
                tier1: {
                    question: "Dalam kalimat \"bukumu baru\", jenis kata ganti apakah \"mu\"?",
                    options: [
                        "A. Kata ganti terpisah.",
                        "B. Kata ganti terhubung.",
                        "C. Kata ganti tersirat.",
                        "D. Kata benda."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal ketiga:",
                    options: [
                        "A. Karena menunjukkan orang yang diajak bicara secara terpisah.",
                        "B. Karena terhubung dengan kata benda \"buku\".",
                        "C. Karena tidak terlihat dalam kalimat.",
                        "D. Karena bukan kata ganti."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 4,
                tier1: {
                    question: "Dalam kalimat \"Dia siswa yang rajin\", jenis kata ganti apakah \"Dia\"?",
                    options: [
                        "A. Kata ganti terhubung.",
                        "B. Kata ganti terpisah.",
                        "C. Kata ganti tersirat.",
                        "D. Kata tunjuk."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal keempat:",
                    options: [
                        "A. Karena terhubung dengan kata kerja \"siswa\".",
                        "B. Karena muncul terpisah dari kata.",
                        "C. Karena tidak terlihat dalam kalimat.",
                        "D. Karena menunjukkan arah pandangan."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 5,
                tier1: {
                    question: "Manakah di antara kalimat berikut yang mengandung kata ganti terhubung yang menunjukkan kepemilikan?",
                    options: [
                        "A. Kamu seorang siswa.",
                        "B. Bukunya indah.",
                        "C. Mereka para siswa.",
                        "D. Dia seorang guru."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal kelima:",
                    options: [
                        "A. Karena kata ganti muncul terpisah.",
                        "B. Karena kata ganti terhubung dengan kata benda dan menunjukkan kepemilikan.",
                        "C. Karena kata ganti menunjukkan jamak orang ketiga.",
                        "D. Karena kata ganti menunjukkan tunggal orang ketiga."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 6,
                tier1: {
                    question: "Manakah di antara kata-kata berikut yang merupakan kata benda tanpa artikel?",
                    options: [
                        "A. Buku",
                        "B. Sekolah",
                        "C. Pensil",
                        "D. Rumah"
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal pertama:",
                    options: [
                        "A. Karena mengandung artikel 'al'.",
                        "B. Karena menunjukkan sesuatu yang spesifik.",
                        "C. Karena menunjukkan sesuatu yang tidak spesifik.",
                        "D. Karena merupakan nama orang."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 7,
                tier1: {
                    question: "Manakah di antara kata-kata berikut yang merupakan kata benda dengan artikel?",
                    options: [
                        "A. Orang",
                        "B. Kota",
                        "C. Matahari",
                        "D. Gedung"
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal kedua:",
                    options: [
                        "A. Karena tidak mengandung artikel 'al'.",
                        "B. Karena menunjukkan sesuatu yang tidak ditentukan.",
                        "C. Karena mengandung artikel 'al'.",
                        "D. Karena merupakan kata benda tanpa artikel."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 8,
                tier1: {
                    question: "Dalam kalimat \"Saya membaca sebuah buku\", jenis kata apakah \"buku\"?",
                    options: [
                        "A. Kata benda dengan artikel.",
                        "B. Kata benda tanpa artikel.",
                        "C. Nama orang.",
                        "D. Kata tunjuk."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal ketiga:",
                    options: [
                        "A. Karena mengandung artikel 'al'.",
                        "B. Karena tidak mengandung artikel 'al' dan menunjukkan buku yang tidak spesifik.",
                        "C. Karena merupakan nama orang.",
                        "D. Karena menunjukkan sesuatu yang spesifik dengan tunjukan."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 9,
                tier1: {
                    question: "Dalam kalimat \"Guru mengunjungi sekolah\", jenis kata apakah \"sekolah\"?",
                    options: [
                        "A. Kata benda tanpa artikel.",
                        "B. Kata benda dengan artikel.",
                        "C. Kata penghubung.",
                        "D. Kata tanya."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal keempat:",
                    options: [
                        "A. Karena tidak mengandung artikel 'al'.",
                        "B. Karena mengandung artikel 'al' dan menunjukkan sekolah yang spesifik.",
                        "C. Karena menghubungkan dua kalimat.",
                        "D. Karena digunakan untuk bertanya."
                    ],
                    answer: null
                },
                flagged: false
            },
            {
                id: 10,
                tier1: {
                    question: "Manakah di antara kalimat berikut yang mengandung kata benda dengan artikel?",
                    options: [
                        "A. Saya membeli sebuah mobil.",
                        "B. Di rumah ada tamu.",
                        "C. Siswa datang.",
                        "D. Saya melihat seorang pria."
                    ],
                    answer: null
                },
                tier2: {
                    question: "Pilih alasan yang benar untuk pilihan Anda pada soal kelima:",
                    options: [
                        "A. Karena kata \"mobil\" tanpa artikel.",
                        "B. Karena kata \"tamu\" tanpa artikel.",
                        "C. Karena kata \"siswa\" dengan artikel.",
                        "D. Karena kata \"pria\" tanpa artikel."
                    ],
                    answer: null
                },
                flagged: false
            }
        ];

        // Global variables
        let currentQuestionIndex = 0;
        let fontSize = 16; // Default font size
        let timerValue = 45 * 60; // 45 minutes in seconds
        let timerInterval;

        // DOM elements
        const examContent = document.getElementById('exam-content');
        const questionNavigator = document.getElementById('question-navigator');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const currentQuestionIndicator = document.getElementById('current-question');
        const submitExamBtn = document.getElementById('submit-exam');
        const timeDisplay = document.getElementById('time');

        // Initialize exam
        function initExam() {
            renderQuestions();
            renderNavigation();
            updateNavigationState();
            startTimer();

            // Add event listeners
            prevBtn.addEventListener('click', goToPreviousQuestion);
            nextBtn.addEventListener('click', goToNextQuestion);
            submitExamBtn.addEventListener('click', submitExam);
        }

        // Render questions
        function renderQuestions() {
            examContent.innerHTML = '';
            
            questions.forEach((question, index) => {
                const questionElement = document.createElement('div');
                questionElement.className = 'question';
                questionElement.id = `question-${question.id}`;
                
                // Question header
                const questionHeader = document.createElement('div');
                questionHeader.className = 'question-number';
                questionHeader.innerHTML = `
                    <span>No ${question.id}</span>
                    <button class="flag-btn ${question.flagged ? 'flagged' : ''}" onclick="toggleFlag(${question.id})">
                        <i class="fas fa-flag"></i>
                    </button>
                `;
                
                // Tier 1 content
                const tier1Content = document.createElement('div');
                tier1Content.className = 'question-content';
                tier1Content.textContent = question.tier1.question;
                
                // Tier 1 options
                const tier1Options = document.createElement('div');
                tier1Options.className = 'options';
                
                question.tier1.options.forEach((option, optionIndex) => {
                    const optionElement = document.createElement('div');
                    optionElement.className = `option${question.tier1.answer === optionIndex ? ' selected' : ''}`;
                    optionElement.onclick = () => selectTier1Option(question.id, optionIndex);
                    
                    // Get the option letter (A, B, C, D)
                    const optionLetter = option.substring(0, option.indexOf('.'));
                    // Trim any whitespace from the option text
                    const optionText = option.substring(option.indexOf('.') + 1).trim();
                    
                    optionElement.innerHTML = `
                        <div class="option-label">
                            <div class="option-circle">${optionLetter}</div>
                        </div>
                        <div class="option-text">${optionText}</div>
                    `;
                    
                    tier1Options.appendChild(optionElement);
                });
                
                // Tier 2 content
                const tier2Container = document.createElement('div');
                tier2Container.className = `second-tier${question.tier1.answer !== null ? ' visible' : ''}`;
                tier2Container.id = `tier2-${question.id}`;
                
                const tier2Label = document.createElement('div');
                tier2Label.className = 'second-tier-label';
                tier2Label.textContent = question.tier2.question;
                
                const tier2Options = document.createElement('div');
                tier2Options.className = 'options';
                
                question.tier2.options.forEach((option, optionIndex) => {
                    const optionElement = document.createElement('div');
                    optionElement.className = `option${question.tier2.answer === optionIndex ? ' selected' : ''}`;
                    optionElement.onclick = () => selectTier2Option(question.id, optionIndex);
                    
                    // Get the option letter (A, B, C, D)
                    const optionLetter = option.substring(0, option.indexOf('.'));
                    const optionText = option.substring(option.indexOf('.') + 1);
                    
                    optionElement.innerHTML = `
                        <div class="option-label">
                            <div class="option-circle">${optionLetter}</div>
                        </div>
                        <div class="option-text">${optionText}</div>
                    `;
                    
                    tier2Options.appendChild(optionElement);
                });
                
                tier2Container.appendChild(tier2Label);
                tier2Container.appendChild(tier2Options);
                
                // Build the complete question
                questionElement.appendChild(questionHeader);
                questionElement.appendChild(tier1Content);
                questionElement.appendChild(tier1Options);
                questionElement.appendChild(tier2Container);
                
                examContent.appendChild(questionElement);
            });
        }

        // Render navigation items
        function renderNavigation() {
            questionNavigator.innerHTML = '';
            
            questions.forEach((question, index) => {
                const navItem = document.createElement('div');
                navItem.className = 'nav-item';
                navItem.textContent = question.id;
                navItem.onclick = () => navigateToQuestion(index);
                
                questionNavigator.appendChild(navItem);
            });
        }

        // Update navigation state based on answered questions and flags
        function updateNavigationState() {
            const navItems = questionNavigator.querySelectorAll('.nav-item');
            
            questions.forEach((question, index) => {
                const navItem = navItems[index];
                navItem.classList.remove('active', 'answered', 'flagged');
                
                if (index === currentQuestionIndex) {
                    navItem.classList.add('active');
                }
                
                if (question.tier1.answer !== null && question.tier2.answer !== null) {
                    navItem.classList.add('answered');
                }
                
                if (question.flagged) {
                    navItem.classList.add('flagged');
                }
            });
            
            // Update current question indicator
            currentQuestionIndicator.textContent = currentQuestionIndex + 1;
            
            // Update prev/next button states
            prevBtn.disabled = currentQuestionIndex === 0;
            nextBtn.disabled = currentQuestionIndex === questions.length - 1;
        }

        // Select tier 1 option
        function selectTier1Option(questionId, optionIndex) {
            const question = questions.find(q => q.id === questionId);
            if (question) {
                question.tier1.answer = optionIndex;
                
                // Show tier 2
                const tier2Element = document.getElementById(`tier2-${questionId}`);
                if (tier2Element) {
                    tier2Element.classList.add('visible');
                }
                
                // Update UI
                renderQuestions();
                updateNavigationState();
            }
        }

        // Navigate to a specific question
        function navigateToQuestion(index) {
            if (index >= 0 && index < questions.length) {
                currentQuestionIndex = index;
                
                // Scroll to the question
                const questionElement = document.getElementById(`question-${questions[index].id}`);
                if (questionElement) {
                    questionElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                // Update navigation state
                updateNavigationState();
            }
        }

        // Go to previous question
        function goToPreviousQuestion() {
            if (currentQuestionIndex > 0) {
                navigateToQuestion(currentQuestionIndex - 1);
            }
        }

        // Go to next question
        function goToNextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                navigateToQuestion(currentQuestionIndex + 1);
            }
        }

        // Change font size
        function changeFont(increase) {
            if (increase) {
                fontSize += 2;
            } else {
                fontSize = Math.max(12, fontSize - 2); // Min font size 12px
            }
            
            // Apply new font size
            document.documentElement.style.setProperty('--font-size', `${fontSize}px`);
            
            // Update all text elements
            const textElements = document.querySelectorAll('.text-font');
            textElements.forEach(element => {
                element.style.fontSize = `${fontSize}px`;
            });
        }

        // Start the timer
        function startTimer() {
            timerInterval = setInterval(updateTimer, 1000);
        }

        // Update timer display
        function updateTimer() {
            timerValue--;
            
            if (timerValue <= 0) {
                clearInterval(timerInterval);
                submitExam();
                return;
            }
            
            const minutes = Math.floor(timerValue / 60);
            const seconds = timerValue % 60;
            
            timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change timer color when less than 5 minutes
            if (timerValue < 300) {
                document.querySelector('.timer').style.backgroundColor = 'var(--danger-color)';
            }
        }

        // Submit the exam
        function submitExam() {
            if (confirm('Apakah Anda yakin ingin mengakhiri ujian?')) {
                // Count answered questions
                const answeredQuestions = questions.filter(q => 
                    q.tier1.answer !== null && q.tier2.answer !== null
                ).length;
                
                const totalQuestions = questions.length;
                
                alert(`Ujian telah selesai.\nJumlah soal dijawab: ${answeredQuestions}/${totalQuestions}`);
                
                // Here you would typically submit the answers to the server
                console.log('Answers submitted:', questions);
                
                // Disable all interactions
                clearInterval(timerInterval);
                
                // Disable all option selections
                const options = document.querySelectorAll('.option');
                options.forEach(option => {
                    option.onclick = null;
                    option.style.cursor = 'default';
                });
                
                // Disable navigation
                prevBtn.disabled = true;
                nextBtn.disabled = true;
                submitExamBtn.disabled = true;
                
                const navItems = document.querySelectorAll('.nav-item');
                navItems.forEach(item => {
                    item.onclick = null;
                    item.style.cursor = 'default';
                });
                
                document.querySelector('.timer').style.backgroundColor = '#aaa';
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', initExam);
    </script>
</body>
</html>