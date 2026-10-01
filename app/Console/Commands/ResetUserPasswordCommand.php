<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetUserPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {username : Username, Email, atau No. HP} {new_password? : Password baru} {--pin= : Set PIN pemulihan baru}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password atau PIN pemulihan user secara darurat lewat terminal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = $this->argument('username');
        $newPassword = $this->argument('new_password');
        $newPin = $this->option('pin');

        $user = User::where('username', $identifier)
            ->orWhere('telepon', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            $this->error("Akun dengan identifier '{$identifier}' tidak ditemukan.");
            return 1;
        }

        if (!$newPassword) {
            $newPassword = $this->secret('Masukkan password baru untuk user ' . $user->username);
            if (empty($newPassword) || strlen($newPassword) < 6) {
                $this->error('Password minimal 6 karakter.');
                return 1;
            }
        }

        $user->password = Hash::make($newPassword);

        if ($newPin) {
            $user->recovery_pin = $newPin;
        }

        $user->save();

        $this->info("--------------------------------------------------");
        $this->info(" BERHASIL MERESET AKUN DARURAT");
        $this->info("--------------------------------------------------");
        $this->line("Nama      : " . $user->name);
        $this->line("Username  : " . $user->username);
        $this->line("Role      : " . $user->role);
        $this->line("Password  : " . $newPassword);
        $this->line("PIN Darurat: " . ($user->recovery_pin ?? '-'));
        $this->info("--------------------------------------------------");

        return 0;
    }
}
