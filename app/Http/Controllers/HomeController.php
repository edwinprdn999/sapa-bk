<?php

namespace App\Http\Controllers;

use App\Models\CounselingService;
use App\Models\Counselor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = CounselingService::where('is_active', true)->get();
        $counselors = Counselor::with('user')->where('status', 'available')->get();

        return view('home', compact('services', 'counselors'));
    }
}
