<?php

namespace App\Console\Commands;

use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Console\Command;

class CreatePelamarProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-pelamar-profile {email : Email user pelamar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat profil pelamar untuk user yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan");
            return 1;
        }

        if ($user->role_id !== 2) {
            $this->error("User {$email} bukan role pelamar");
            return 1;
        }

        if ($user->pelamar) {
            $this->error("User {$email} sudah memiliki profil pelamar");
            return 1;
        }

        Pelamar::create([
            'id_user' => $user->id,
            'nama_pelamar' => $user->name,
            'status_pekerjaan' => 'Sedang Mencari Kerja',
            'no_telp' => '-',
            'alamat' => '-',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => now()->subYears(25)->format('Y-m-d'),
        ]);

        $this->info("Profil pelamar berhasil dibuat untuk {$email}");
        return 0;
    }
}

