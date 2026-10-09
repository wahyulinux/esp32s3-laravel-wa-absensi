<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    protected $signature = 'user:create-admin
                            {--username= : Username untuk login}
                            {--name= : Nama lengkap}';

    protected $description = 'Buat akun admin untuk login ke dashboard';

    public function handle(): int
    {
        $username = strtolower($this->option('username') ?: $this->ask('Username'));
        $name     = $this->option('name') ?: $this->ask('Nama lengkap', 'Administrator');
        $password = $this->secret('Password (min. 8 karakter)');
        $confirm  = $this->secret('Ulangi password');

        $validator = Validator::make(
            ['username' => $username, 'name' => $name, 'password' => $password, 'password_confirmation' => $confirm],
            [
                'username' => 'required|string|max:50|alpha_dash|unique:users,username',
                'name'     => 'required|string|max:100',
                'password' => ['required', 'confirmed', Password::min(8)],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create([
            'name'     => $name,
            'username' => $username,
            'password' => $password,
            'role'     => User::ROLE_ADMIN,
            'aktif'    => true,
        ]);

        $this->info("Admin '{$username}' berhasil dibuat. Silakan login di /login.");

        return self::SUCCESS;
    }
}
