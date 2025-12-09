<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCompany()) {
            return redirect()->route('company.dashboard');
        } elseif ($user->isPelamar()) {
            return redirect()->route('pelamar.profil');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
