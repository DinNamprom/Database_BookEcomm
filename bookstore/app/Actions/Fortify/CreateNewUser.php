<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string'],
            'password'   => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name'       => $input['first_name'].' '.$input['last_name'],
            'email'      => $input['email'],
            'password'   => $input['password'],
            'first_name' => $input['first_name'],
            'last_name'  => $input['last_name'],
            'address'    => $input['address'] ?? null,
        ]);
    }
}
