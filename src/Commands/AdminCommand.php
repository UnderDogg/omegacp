<?php

namespace artworx\omegacp\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputOption;
use artworx\omegacp\Facades\Omega;

class AdminCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'omega:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure there is a user with the admin role that has all of the necessary permissions.';

    /**
     * Get user options.
     */
    protected function getOptions()
    {
        return [
            ['create', null, InputOption::VALUE_NONE, 'Create an admin user', null],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Get or create user
        $user = $this->getUser(
            $this->option('create')
        );

        // Get or create role
        $role = $this->getAdministratorRole();

        // Get all permissions
        $permissions = Omega::model('Permission')->all();

        // Assign all permissions to the admin role
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        // Ensure that the user is admin
        $user->role_id = $role->id;
        $user->save();

        $this->info('The user now has full access to your site.');
    }

    /**
     * Get command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['email', InputOption::VALUE_REQUIRED, 'The email of the user.', null],
        ];
    }

    /**
     * Get the administrator role, create it if it does not exists.
     *
     * @return mixed
     */
    protected function getAdministratorRole()
    {
        $role = Omega::model('Role')->firstOrNew([
            'name' => 'admin',
        ]);

        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Administrator',
            ])->save();
        }

        return $role;
    }

    /**
     * Get or create user.
     *
     * @param bool $create
     *
     * @return \App\User
     */
    protected function getUser($create = false)
    {
        $email = $this->argument('email');

        $model = config('omega.user.namespace', 'App\\User');

        // If we need to create a new user go ahead and create it
        if ($create) {
            $name = $this->ask('Enter the admin name');
            $password = $this->secret('Enter admin password');
            $this->info('Creating admin account');

            return $model::create([
                'name'             => $name,
                'email'            => $email,
                'password'         => Hash::make($password),
                'avatar'           => 'users/default.png',
            ]);
        }

        return $model::where('email', $email)->firstOrFail();
    }
}
