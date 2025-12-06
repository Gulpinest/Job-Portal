<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- INI YANG HILANG/SALAH KETIK TADI
use Symfony\Component\HttpFoundation\Response;

class CheckJobQuota
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Pastikan user adalah perusahaan (role_id 3)
        if ($user && $user->role_id == 3) { 
            
            // Ambil data company milik user
            $company = $user->company; 

            // Cek apakah company ada DAN kuotanya habis (<= 0)
            if (!$company || $company->job_quota <= 0) {
                // Jika kuota habis, lempar ke halaman paket harga
                return redirect()->route('subscription.index')->with('error', 'Kuota posting habis. Silakan beli paket!');
            }
        }

        return $next($request);
    }
}