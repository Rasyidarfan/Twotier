<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isTeacher()
    {
        return $this->role === 'guru'; // Alias untuk backward compatibility
    }

    public function createdQuestions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    public function createdExams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    // Timezone helper methods
    public function getUserTimezone()
    {
        return $this->timezone ?? 'Asia/Jakarta';
    }

    public function convertToUserTime($datetime, $format = 'Y-m-d H:i:s')
    {
        if (!$datetime) {
            return null;
        }

        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        return $datetime->setTimezone($this->getUserTimezone())->format($format);
    }

    public function convertToUserTimeCarbon($datetime)
    {
        if (!$datetime) {
            return null;
        }

        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        return $datetime->setTimezone($this->getUserTimezone());
    }

    // Get available timezones for Indonesia
    public static function getIndonesianTimezones()
    {
        return [
            'Asia/Jakarta' => 'WIB (Waktu Indonesia Barat)',
            'Asia/Makassar' => 'WITA (Waktu Indonesia Tengah)', 
            'Asia/Jayapura' => 'WIT (Waktu Indonesia Timur)',
        ];
    }
}
