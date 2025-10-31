<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{

    public function settings(Request $request)
    {
        return Inertia::render('Settings/Settings', [
            'user' => $request->user(),
        ]);
    }



    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('user.settings');
    }
}
