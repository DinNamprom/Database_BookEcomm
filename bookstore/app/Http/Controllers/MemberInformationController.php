<?php

namespace App\Http\Controllers;

use App\Concerns\ProfileValidationRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberInformationController extends Controller
{
    use ProfileValidationRules;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'member_number' => 'M-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => $this->emailRules($user->id),
            'phone_number' => ['sometimes', 'nullable', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
        ]);

        $user->fill($validated);
        $user->name = trim($validated['first_name'].' '.$validated['last_name']);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $this->show($request);
    }

    public function updateAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address' => ['required', 'string', 'max:2000'],
        ]);

        $request->user()->update($validated);

        return $this->show($request);
    }
}
