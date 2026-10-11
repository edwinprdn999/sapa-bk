<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi pengelola BK.']);
            }

            AuditLog::log('login', 'User', $user->id);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_number' => 'required|string|max:50|unique:students,student_number',
            'study_program' => 'required|string|max:100',
            'faculty' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $roleStudent = Role::whereIn('name', ['siswa', 'mahasiswa'])->firstOrFail();

            $user = User::create([
                'role_id' => $roleStudent->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'status' => 'active',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_number' => $validated['student_number'],
                'study_program' => $validated['study_program'],
                'faculty' => $validated['faculty'] ?? null,
                'academic_year' => '2024/2025',
            ]);

            DB::commit();

            Auth::login($user);
            AuditLog::log('register', 'User', $user->id);

            return redirect()->route('student.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di SAPA-BK.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal melakukan pendaftaran: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::log('logout', 'User', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar sistem.');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isCounselor()) {
            return redirect()->route('counselor.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
