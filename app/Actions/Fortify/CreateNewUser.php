<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'employee_id' => ['required', 'string', 'max:20', 'unique:employees,employee_id']
        ])->validate();

         // Create the user record
        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id'  => 6, // default employee role
        ]);

        // Create the associated student record with proper linkage.
        Employee::create([
            'matric_no'   => $input['matric_no'],
            'full_name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'user_id'     => $user->id,
        ]);

        return $user;
    }
}
