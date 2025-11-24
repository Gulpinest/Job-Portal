<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $company = null;

        if ($user->isCompany()) {
            $company = $user->company;
        }

        return view('profile.edit', [
            'user' => $user,
            'company' => $company,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Separate company fields from user fields
        $companyFields = [];
        if ($request->user()->isCompany()) {
            $companyFields = [
                'nama_perusahaan' => $validated['nama_perusahaan'] ?? null,
                'no_telp_perusahaan' => $validated['no_telp_perusahaan'] ?? null,
                'alamat_perusahaan' => $validated['alamat_perusahaan'] ?? null,
                'desc_company' => $validated['desc_company'] ?? null,
            ];

            // Remove company fields from user fill
            unset($validated['nama_perusahaan'], $validated['no_telp_perusahaan'], $validated['alamat_perusahaan'], $validated['desc_company']);
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Update company information if user is company
        if ($request->user()->isCompany() && !empty($companyFields)) {
            $request->user()->company->update($companyFields);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
