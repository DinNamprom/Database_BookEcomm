<?php

namespace App\Http\Controllers;

use App\Concerns\ProfileValidationRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberInformationController extends Controller
{
    use ProfileValidationRules;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $deliveryAddress = $user->addresses()->orderByDesc('is_default')->orderBy('id')->first();

        return response()->json([
            'member_number' => 'M-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'delivery_address' => $deliveryAddress?->only([
                'street_address', 'city', 'state', 'zip_code', 'country',
            ]),
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
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        DB::transaction(function () use ($user, $validated) {
            // Serialize default-address updates for this member only.
            $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $address = $user->addresses()->orderByDesc('is_default')->orderBy('id')->first();
            $user->addresses()->where('is_default', true)
                ->where('id', '!=', $address?->id ?? 0)->update(['is_default' => false]);

            if ($address) {
                $address->fill($validated);
                $address->is_default = true;
                $address->save();
            } else {
                $user->addresses()->create(array_merge($validated, ['is_default' => true]));
            }

            // Preserve the existing combined address used by member information.
            $user->update(['address' => implode("\n", [
                $validated['street_address'],
                $validated['city'].', '.$validated['state'].' '.$validated['zip_code'],
                $validated['country'],
            ])]);
        });

        return $this->show($request);
    }
}
