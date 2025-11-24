<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $pelamars = User::where('role_id', 2)->count();
        $companies = User::where('role_id', 3)->count();
        $recentLogs = Log::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pelamars',
            'companies',
            'recentLogs'
        ));
    }

    /**
     * Show the users management page.
     */
    public function users(Request $request): View
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Delete a user (only for non-admin users).
     */
    public function deleteUser(User $user): RedirectResponse
    {
        // Prevent deleting admin users
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus pengguna admin.');
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Show the activity logs page.
     */
    public function logs(Request $request): View
    {
        $query = Log::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        // Search by action
        if ($request->filled('search')) {
            $query->where('aksi', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20);
        $users = User::select('id', 'name')->get();

        return view('admin.logs', compact('logs', 'users'));
    }

    /**
     * Show a single log detail.
     */
    public function logDetail(Log $log): View
    {
        return view('admin.log-detail', compact('log'));
    }

    /**
     * Clear all logs.
     */
    public function clearLogs(): RedirectResponse
    {
        Log::truncate();

        return redirect()->back()->with('success', 'Semua log aktivitas telah dihapus.');
    }
}
