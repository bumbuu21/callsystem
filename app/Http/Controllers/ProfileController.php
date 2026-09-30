<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'phone' => ['nullable', 'string', 'max:20'], 'aimag_name' => ['nullable', 'string', 'max:80'], 'soum_name' => ['nullable', 'string', 'max:80']]);
        $user->update($data);

        return back()->with('success', 'Профайл шинэчлэгдлээ.');
    }
}
