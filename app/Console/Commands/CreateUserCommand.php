<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name of the new user');
        $user['email'] = $this->ask('Email of the new user');
        $user['role'] = $this->choice('Role of the new user', ['admin', 'user']);
        $user['password'] = $this->secret('Password of the new user');

        $role = Role::where('name', $user['role'])->first();


        if (!$role) {
            $this->error('Role not found');

            return -1;
        }

        $validator = Validator::make($user, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::default()]
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return -1;
        }


        DB::transaction(function() use ($user, $role) {
            $user['password'] = Hash::make($user['password']);

            $newUser = User::create($user);

            $newUser->roles()->attach($role);

        });

        $this->info('Create user \'' . $user['password'] . '\' was successfully :)');
        return 0;
    }
}
