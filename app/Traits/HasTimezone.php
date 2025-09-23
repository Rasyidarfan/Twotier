<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait HasTimezone
{
    /**
     * Get the current user's timezone or default
     */
    public function getCurrentTimezone()
    {
        // For authenticated users
        if (Auth::check()) {
            return Auth::user()->getUserTimezone();
        }

        // For non-authenticated users (students), check session
        return Session::get('user_timezone', 'Asia/Jakarta');
    }

    /**
     * Convert datetime to current user's timezone
     */
    public function toUserTimezone($datetime, $format = null)
    {
        if (!$datetime) {
            return null;
        }

        if (is_string($datetime)) {
            $datetime = Carbon::parse($datetime);
        }

        $converted = $datetime->setTimezone($this->getCurrentTimezone());
        
        return $format ? $converted->format($format) : $converted;
    }

    /**
     * Convert datetime to user timezone and format for display
     */
    public function formatForUser($datetime, $format = 'd/m/Y H:i')
    {
        return $this->toUserTimezone($datetime, $format);
    }

    /**
     * Get timezone offset in seconds from server timezone
     */
    public function getTimezoneOffset()
    {
        $serverTimezone = config('app.timezone');
        $userTimezone = $this->getCurrentTimezone();
        
        $serverTime = Carbon::now($serverTimezone);
        $userTime = Carbon::now($userTimezone);
        
        return $userTime->getOffset() - $serverTime->getOffset();
    }

    /**
     * Convert server time to user time in seconds (for JS countdown)
     */
    public function adjustTimeForClient($timeInSeconds)
    {
        return $timeInSeconds; // Keep as is since server time calculations remain consistent
    }

    /**
     * Set timezone for non-authenticated users (students)
     */
    public function setSessionTimezone($timezone)
    {
        if (in_array($timezone, array_keys($this->getAvailableTimezones()))) {
            Session::put('user_timezone', $timezone);
        }
    }

    /**
     * Get available Indonesian timezones
     */
    public function getAvailableTimezones()
    {
        return [
            'Asia/Jakarta' => 'WIB (UTC+7)',
            'Asia/Makassar' => 'WITA (UTC+8)',
            'Asia/Jayapura' => 'WIT (UTC+9)',
        ];
    }
}