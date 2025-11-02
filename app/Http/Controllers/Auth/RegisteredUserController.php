<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add company validation if company_name is provided
        if ($request->has('company_name') && !empty($request->company_name)) {
            $rules['company_name'] = 'required|string|max:255|unique:companies,name';
            $rules['company_email'] = 'nullable|email|max:255';
            $rules['company_phone'] = 'nullable|string|max:20';
        }

        $request->validate($rules);

        // Create or get company
        $company = null;
        if ($request->has('company_name') && !empty($request->company_name)) {
            // Generate unique slug
            $slug = Str::slug($request->company_name);
            $originalSlug = $slug;
            $counter = 1;

            while (Company::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $company = Company::create([
                'name' => $request->company_name,
                'slug' => $slug,
                'email' => $request->company_email,
                'phone' => $request->company_phone,
            ]);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'company_id' => $company?->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        // Redirect with success message
        return redirect()->route('dashboard')->with('success',
            $company
                ? "Account created successfully! Welcome to {$company->name}."
                : 'Account created successfully! You can join a company later from your dashboard.'
        );
    }
}
