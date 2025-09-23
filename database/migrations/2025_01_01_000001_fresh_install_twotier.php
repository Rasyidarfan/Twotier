<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Force drop all existing tables
        $this->dropAllTables();
        
        // Create all tables fresh
        $this->createAllTables();
    }

    private function dropAllTables()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Get all table names
        $tables = DB::select('SHOW TABLES');
        
        foreach ($tables as $table) {
            // Convert stdClass to array and get the first value
            $tableArray = (array) $table;
            $tableName = reset($tableArray);
            
            // Skip the migrations table
            if ($tableName === 'migrations') {
                continue;
            }
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "All existing tables dropped.\n";
    }

    private function createAllTables()
    {
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'guru'])->default('guru');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // Create subjects table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create chapters table
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('grade');
            $table->enum('semester', ['gasal', 'genap']);
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create questions table with JSON structure
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->text('tier1_question');
            $table->json('tier1_options'); // 5 opsi dalam JSON array
            $table->integer('tier1_correct_answer'); // Index 0-4
            $table->text('tier2_question');
            $table->json('tier2_options'); // 5 opsi dalam JSON array
            $table->integer('tier2_correct_answer'); // Index 0-4
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Create exams table
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('code', 6)->unique();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('grade');
            $table->enum('semester', ['gasal', 'genap']);
            $table->integer('duration_minutes');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->enum('status', ['draft', 'waiting', 'active', 'finished'])->default('draft');
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('show_result_immediately')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Create exam_questions table (pivot table)
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('question_order');
            $table->integer('points')->default(10);
            $table->timestamps();
            
            $table->unique(['exam_id', 'question_id']);
            $table->index(['exam_id', 'question_order']);
        });

        // Create student_exam_sessions table
        Schema::create('student_exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('student_name');
            $table->string('student_identifier')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->integer('total_score')->nullable();
            $table->json('scoring_breakdown')->nullable();
            $table->enum('status', ['registered', 'in_progress', 'finished', 'timeout'])->default('registered');
            $table->timestamps();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('student_name');
            $table->string('student_identifier')->nullable();
            $table->datetime('started_at');
            $table->datetime('finished_at')->nullable();
            $table->integer('correct_correct')->default(0);
            $table->integer('correct_wrong')->default(0);
            $table->integer('wrong_correct')->default(0);
            $table->integer('wrong_wrong')->default(0);
            $table->integer('total_score')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'timeout'])->default('in_progress');
            $table->timestamps();
        });

        // Create student_answers table
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('student_exam_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('result_id')->nullable()->constrained('exam_results')->onDelete('cascade');
            $table->integer('tier1_answer')->nullable();
            $table->integer('tier2_answer')->nullable();
            $table->enum('result_category', ['benar-benar', 'benar-salah', 'salah-benar', 'salah-salah'])->nullable();
            $table->integer('points_earned')->default(0);
            $table->timestamps();
            
            $table->unique(['session_id', 'question_id']);
        });

        // Re-create standard Laravel tables
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        echo "All tables created successfully.\n";
    }

    public function down(): void
    {
        $this->dropAllTables();
    }
};

