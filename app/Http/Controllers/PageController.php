<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show student guide page
     */
    public function studentGuide()
    {
        return view('pages.student-guide');
    }

    /**
     * Show teacher guide page
     */
    public function teacherGuide()
    {
        return view('pages.teacher-guide');
    }

    /**
     * Show developer profile page
     */
    public function developerProfile()
    {
        return view('pages.developer-profile');
    }
}
