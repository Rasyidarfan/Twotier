# Two-Tier Examination System

Sistem ujian berbasis web yang menggunakan metodologi **Two-Tier Diagnostic Test** untuk mengevaluasi pemahaman konsep dan kemampuan reasoning siswa.

## Tentang Sistem

Two-Tier Examination System adalah aplikasi web komprehensif yang dirancang untuk melakukan penilaian diagnostik dengan pendekatan dua tingkat. Sistem ini memungkinkan pendidik untuk membuat, mengelola, dan memantau ujian secara real-time sambil memberikan siswa pengalaman ujian yang terstruktur.

### Apa itu Two-Tier Test?

Two-Tier Test adalah metode penilaian yang terdiri dari dua bagian:

1. **Tier 1 (Konsep)**: Pertanyaan tentang pemahaman konsep dasar
2. **Tier 2 (Reasoning)**: Pertanyaan tentang alasan/justifikasi dari jawaban Tier 1

Sistem penilaian:
- **Benar-Benar (100%)**: Pemahaman konsep dan reasoning sempurna
- **Benar-Salah (50%)**: Paham konsep tapi reasoning kurang tepat
- **Salah-Benar (50%)**: Konsep salah tapi reasoning logis
- **Salah-Salah (0%)**: Miskonsepsi total

## Fitur Utama

### Untuk Administrator
- Manajemen user (admin & guru)
- Manajemen bank soal dengan two-tier structure
- Manajemen mata pelajaran dan chapter
- Kontrol akses berbasis role

### Untuk Guru
- Pembuatan dan pengelolaan ujian
- Monitoring real-time peserta ujian
- Kontrol waktu ujian (extend time per siswa/semua)
- Approval manual peserta
- Broadcast pesan ke peserta
- Export hasil ujian (CSV)
- Analisis statistik two-tier
- Analisis tingkat kesulitan soal

### Untuk Siswa
- Join ujian dengan kode 6 karakter (tanpa login)
- Interface ujian yang user-friendly
- Timer otomatis
- Progress tracking
- Hasil langsung (jika diaktifkan)

## Teknologi

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL/MariaDB
- **Frontend**: Blade Templates + JavaScript
- **Real-time**: AJAX Polling
- **Export**: CSV Generation
- **PDF**: mPDF (untuk export laporan)

## Quick Start

### Instalasi Otomatis

```bash
# Clone repository
git clone <repository-url>
cd twotier

# Jalankan setup script
./setup.sh
```

### Instalasi Manual

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Konfigurasi Database**

   Edit `.env`:
   ```env
   DB_DATABASE=twotier
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Migrasi & Seeding**
   ```bash
   # Buat database terlebih dahulu
   mysql -u root -p
   CREATE DATABASE twotier;
   EXIT;

   # Jalankan migrasi
   php artisan migrate

   # Seed data sample (optional)
   php artisan db:seed
   ```

5. **Start Server**
   ```bash
   php artisan serve
   ```

6. **Akses Aplikasi**

   Buka browser: `http://localhost:8000`

**Default Credentials** (setelah db:seed):
- Admin: `admin@example.com` / `password`
- Guru: `guru@example.com` / `password`

## Dokumentasi

- [SETUP.md](SETUP.md) - Panduan instalasi lengkap
- [FEATURES.md](FEATURES.md) - Dokumentasi fitur lengkap

## Struktur Project

```
twotier/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php    # Admin functions
│   │   ├── GuruController.php     # Teacher functions
│   │   ├── StudentController.php  # Student exam flow
│   │   └── AuthController.php     # Authentication
│   ├── Models/
│   │   ├── User.php               # Admin & Guru
│   │   ├── Exam.php               # Exam management
│   │   ├── Question.php           # Two-tier questions
│   │   ├── StudentExamSession.php # Student sessions
│   │   └── StudentAnswer.php      # Student answers
│   └── Traits/
│       └── HasTimezone.php        # Timezone support
├── database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Sample data
├── resources/
│   └── views/
│       ├── admin/                 # Admin views
│       ├── guru/                  # Teacher views
│       └── student/               # Student views
└── routes/
    └── web.php                    # Application routes
```

## Workflow Ujian

```
┌─────────┐
│  DRAFT  │ ──> Guru menyiapkan soal
└────┬────┘
     │
     v
┌─────────┐
│ WAITING │ ──> Siswa join & auto-approved
└────┬────┘     (Generate exam code)
     │
     v
┌─────────┐
│ ACTIVE  │ ──> Ujian berlangsung
└────┬────┘     (Manual approval untuk late joiners)
     │
     v
┌──────────┐
│ FINISHED │ ──> Hasil & statistik
└──────────┘
```

## Database Schema

### Core Tables
- `users` - Admin & Guru users
- `subjects` - Mata pelajaran
- `chapters` - Chapter per subject
- `questions` - Bank soal two-tier
- `exams` - Data ujian
- `exam_questions` - Soal per ujian
- `student_exam_sessions` - Sesi siswa
- `student_answers` - Jawaban siswa

## Kontribusi

Silakan buat issue atau pull request untuk:
- Bug reports
- Feature requests
- Improvements

## License

Project ini adalah bagian dari sistem pendidikan internal. Silakan hubungi maintainer untuk informasi lisensi.

## Support

Untuk pertanyaan atau bantuan:
1. Baca dokumentasi di [FEATURES.md](FEATURES.md)
2. Cek [SETUP.md](SETUP.md) untuk troubleshooting
3. Buat issue di repository

---

**Catatan Keamanan**: Pastikan untuk mengganti default credentials dan mengamankan `.env` file di production!

## Changelog

- **v2.0** - Complete system overhaul dengan improved workflow
- **v1.0** - Initial release dengan basic two-tier functionality

## Credits

Developed for educational assessment purposes using Laravel framework.
