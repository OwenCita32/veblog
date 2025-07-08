<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // $request->user()->fill($request->validated());

				$validatedData = $request->validated();

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

				// if ($request->hasFile('avatar')) {
				// 	if (!empty($request->user()->avatar)) {
				// 		// Delete the old avatar if it exists
				// 		Storage::disk('public')->delete($request->user()->avatar);
				// 	}
				// 		$validatedData['avatar'] = $request->file('avatar')->store('img', 'public');
				// }

				if ($request->avatar) {
					if (!empty($request->user()->avatar)) {
						// Delete the old avatar if it exists
						Storage::disk(config('filesystems.default_public_disk'))->delete($request->user()->avatar);
					}
					$newFileName = Str::after($request->avatar, 'tmp/');
					Storage::disk(config('filesystems.default_public_disk'))->move($request->avatar, "img/$newFileName");
					$validatedData['avatar'] = "img/$newFileName";
				}

        $request->user()->update($validatedData);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

		public function upload(Request $request) {
			if ($request->hasFile('avatar')) {
				$path = $request->file('avatar')->store('tmp', config('filesystems.default_public_disk'));
			}
			return $path;
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
