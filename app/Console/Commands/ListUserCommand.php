<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('roles')->get();


        foreach ($users as $user) {

            $this->info('name   :      ' . $user->name);
            $this->info('email  :      ' . $user->email);
            $this->info('Role   :      ' . $user->roles->pluck('name'));
            $this->info('');
            $this->info('------------------------------');
            $this->info('');
        }

        $this->info('This is all user');
        return 0;
    }
}
