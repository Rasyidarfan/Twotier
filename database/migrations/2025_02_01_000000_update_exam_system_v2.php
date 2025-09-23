<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update exams table
        Schema::table('exams', function (Blueprint $table) {
            // Remove scheduled times, use only duration
            $table->dropColumn(['start_time', 'end_time']);
            
            // Add code generation settings
            $table->string('current_code', 6)->nullable()->after('code');
            $table->timestamp('code_generated_at')->nullable();
            $table->boolean('auto_regenerate_code')->default(true);
            
            // Update status enum
            $table->enum('status', ['draft', 'waiting', 'active', 'finished'])->default('draft')->change();
        });

        // Update student_exam_sessions table
        Schema::table('student_exam_sessions', function (Blueprint $table) {
            // Add timezone and approval system
            $table->string('timezone', 50)->default('Asia/Jakarta')->after('student_identifier');
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->integer('extended_time_minutes')->default(0);
            $table->timestamp('joined_at')->nullable();
            $table->json('device_info')->nullable();
            
            // Update status enum to include more states
            $table->enum('status', [
                'waiting_identity',    // Waiting in lobby, need to fill identity
                'waiting_approval',    // Identity filled, waiting for approval (active exam only)
                'approved',           // Approved but not started
                'in_progress',        // Currently taking exam
                'finished',           // Completed normally
                'timeout',            // Time expired
                'kicked'              // Removed by teacher
            ])->default('waiting_identity')->change();
        });

        // Add exam_sessions table for better session management
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('session_token', 32)->unique();
            $table->timestamp('expires_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['exam_id', 'expires_at']);
        });

        echo "Exam system v2 migration completed.\n";
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
        
        Schema::table('student_exam_sessions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'timezone', 'approved', 'approved_at', 'approved_by',
                'extended_time_minutes', 'joined_at', 'device_info'
            ]);
            $table->enum('status', ['registered', 'in_progress', 'finished', 'timeout'])->default('registered')->change();
        });
        
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['current_code', 'code_generated_at', 'auto_regenerate_code']);
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->enum('status', ['draft', 'waiting', 'active', 'finished'])->default('draft')->change();
        });
    }
};