<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'grade',
        'semester',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scopeByGradeAndSemester($query, $grade, $semester)
    {
        return $query->where('grade', $grade)->where('semester', $semester);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
