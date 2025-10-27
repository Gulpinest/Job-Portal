<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // Show the company login/register page
    public function loginRegisterPage()
    {
        // If the user is authenticated, redirect them to the dashboard
        // if (!auth()->check()) {
        //     return redirect()->route('dashboard');
        // }

        // If not authenticated, show the login/register page
        return view('company.login-register'); // Make sure this view exists
    }

    // Show the company registration form
    public function showForm()
    {
        return view('company.register'); // Ensure this view exists
    }
    
    public function showLoginForm()
    {
        return view('company.login'); // Ensure this view exists
    }
    

    // Handle storing company data
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'description' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Store company and user data
        // Create the company and the associated user
        // Make sure you have the logic here to handle company registration

        return redirect()->route('company.dashboard'); // Redirect to company dashboard after registration
    }
}
