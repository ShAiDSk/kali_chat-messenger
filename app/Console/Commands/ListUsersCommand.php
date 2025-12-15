<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


//Inside tinker --> run - App\Models\User::all(); -- show all existing users





class ListUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-users-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
