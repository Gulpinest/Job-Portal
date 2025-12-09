<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Resume;
use App\Models\Roles;
use App\Models\User;
use App\Models\InterviewSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Seed test data into database
     */
    public function run(): void
    {
        // 0. Create roles if not exist
        $adminRole = Roles::firstOrCreate(['name' => 'Admin'], ['name' => 'Admin', 'display_name' => 'Administrator']);
        $pelamarRole = Roles::firstOrCreate(['name' => 'Pelamar'], ['name' => 'Pelamar', 'display_name' => 'Job Seeker']);
        $companyRole = Roles::firstOrCreate(['name' => 'Company'], ['name' => 'Company', 'display_name' => 'Company']);

        // 0.1. Create test admin user
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // 0.5. Create test pelamar user
        $pelamarUser = User::firstOrCreate(
            ['email' => 'pelamar@test.com'],
            [
                'name' => 'Pelamar Uji',
                'email' => 'pelamar@test.com',
                'password' => Hash::make('password'),
                'role_id' => $pelamarRole->id,
            ]
        );

        // 0.6. Create pelamar profile if not exists
        $pelamar = Pelamar::firstOrCreate(
            ['id_user' => $pelamarUser->id],
            [
                'id_user' => $pelamarUser->id,
                'nama_pelamar' => 'Pelamar Uji',
                'status_pekerjaan' => 'Sedang Mencari Kerja',
                'no_telp' => '081234567890',
                'alamat' => 'Jakarta, Indonesia',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir' => '2000-01-01',
            ]
        );

        // 1. Create test company user
        $companyUser = User::firstOrCreate(
            ['email' => 'company@test.com'],
            [
                'name' => 'PT Test Company',
                'email' => 'company@test.com',
                'password' => Hash::make('password'),
                'role_id' => $companyRole->id,
            ]
        );

        // 2. Create company
        $company = Company::firstOrCreate(
            ['id_user' => $companyUser->id],
            [
                'id_user' => $companyUser->id,
                'nama_perusahaan' => 'PT Test Company',
                'alamat_perusahaan' => 'Jakarta, Indonesia',
                'no_telp_perusahaan' => '021-1234567',
                'desc_company' => 'Perusahaan teknologi terkemuka dengan fokus pada pengembangan software.',
            ]
        );

        // 3. Create test job listings with various data
        $jobListings = [
            [
                'judul' => 'Senior Laravel Developer',
                'posisi' => 'Backend Developer',
                'lokasi_kantor' => 'Jakarta',
                'gaji' => 'Rp 15,000,000 - Rp 20,000,000',
                'tipe_kerja' => 'Full Time',
                'deskripsi' => 'Kami mencari Senior Laravel Developer yang berpengalaman minimal 5 tahun untuk bergabung dengan tim backend kami. Anda akan bekerja pada proyek-proyek skala enterprise dan berkontribusi pada arsitektur sistem kami.',
                'persyaratan_tambahan' => "• Pengalaman minimal 5 tahun dengan Laravel\n• Memahami konsep Clean Code dan Design Patterns\n• Berpengalaman dengan REST API development\n• Familiar dengan Git dan CI/CD\n• Pendidikan minimal S1 Teknik Informatika atau setara\n• Dapat bekerja dalam tim dan komunikasi yang baik",
                'status' => 'Open',
            ],
            [
                'judul' => 'Frontend React Developer',
                'posisi' => 'Frontend Developer',
                'lokasi_kantor' => 'Bandung',
                'gaji' => 'Rp 12,000,000 - Rp 16,000,000',
                'tipe_kerja' => 'Full Time',
                'deskripsi' => 'Bergabunglah dengan tim frontend kami sebagai React Developer. Anda akan mengembangkan user interface yang modern dan responsive untuk aplikasi kami.',
                'persyaratan_tambahan' => "• Pengalaman 3+ tahun dengan React\n• Strong understanding of JavaScript ES6+\n• Familiar dengan CSS frameworks (Tailwind, Bootstrap)\n• Git dan agile methodology\n• Portfolio atau GitHub profile yang bagus\n• English proficiency yang cukup untuk komunikasi tim",
                'status' => 'Open',
            ],
        ];

        // Insert all job listings
        foreach ($jobListings as $job) {
            Lowongan::firstOrCreate(
                ['judul' => $job['judul']],
                array_merge($job, ['id_company' => $company->id_company])
            );
        }

        // Create resumes for pelamar
        $resume1 = Resume::firstOrCreate(
            ['id_pelamar' => $pelamar->id_pelamar, 'nama_resume' => 'Resume Standar'],
            [
                'id_pelamar' => $pelamar->id_pelamar,
                'nama_resume' => 'Resume Standar',
                'skill' => 'PHP, Laravel, JavaScript, React, SQL, MySQL',
                'pendidikan_terakhir' => 'S1 Teknik Informatika',
                'ringkasan_singkat' => 'Full Stack Developer dengan pengalaman 3 tahun',
                'file_resume' => null,
            ]
        );

        $resume2 = Resume::firstOrCreate(
            ['id_pelamar' => $pelamar->id_pelamar, 'nama_resume' => 'Resume Backend'],
            [
                'id_pelamar' => $pelamar->id_pelamar,
                'nama_resume' => 'Resume Backend',
                'skill' => 'PHP, Laravel, Python, Docker, Kubernetes, AWS',
                'pendidikan_terakhir' => 'S1 Teknik Informatika',
                'ringkasan_singkat' => 'Backend Developer dengan fokus pada Laravel dan DevOps',
                'file_resume' => null,
            ]
        );

        // Create sample applications (lamarans)
        $seniorLaravelJob = Lowongan::where('judul', 'Senior Laravel Developer')->first();
        $frontendJob = Lowongan::where('judul', 'Frontend React Developer')->first();
        $dataScientistJob = Lowongan::where('judul', 'Data Scientist')->first();

        if ($seniorLaravelJob && !Lamaran::where('id_lowongan', $seniorLaravelJob->id_lowongan)->where('id_pelamar', $pelamar->id_pelamar)->exists()) {
            Lamaran::create([
                'id_pelamar' => $pelamar->id_pelamar,
                'id_lowongan' => $seniorLaravelJob->id_lowongan,
                'id_resume' => $resume1->id_resume,
                'cv' => null,
                'status_ajuan' => 'Pending',
            ]);
        }

        if ($frontendJob && !Lamaran::where('id_lowongan', $frontendJob->id_lowongan)->where('id_pelamar', $pelamar->id_pelamar)->exists()) {
            Lamaran::create([
                'id_pelamar' => $pelamar->id_pelamar,
                'id_lowongan' => $frontendJob->id_lowongan,
                'id_resume' => $resume1->id_resume,
                'cv' => null,
                'status_ajuan' => 'Accepted',
            ]);
        }

        if ($dataScientistJob && !Lamaran::where('id_lowongan', $dataScientistJob->id_lowongan)->where('id_pelamar', $pelamar->id_pelamar)->exists()) {
            Lamaran::create([
                'id_pelamar' => $pelamar->id_pelamar,
                'id_lowongan' => $dataScientistJob->id_lowongan,
                'id_resume' => $resume2->id_resume,
                'cv' => null,
                'status_ajuan' => 'Rejected',
            ]);
        }

        // Create interview schedule for frontend job with accepted applicant
        if ($frontendJob && !InterviewSchedule::where('id_lowongan', $frontendJob->id_lowongan)->exists()) {
            InterviewSchedule::create([
                'id_lowongan' => $frontendJob->id_lowongan,
                'waktu_jadwal' => now()->addDays(7)->setTime(10, 0),
                'lokasi' => 'Bandung - Meeting Room 3 / Zoom: https://zoom.us/j/123456',
                'type' => 'Hybrid',
                'catatan' => 'Persiapkan portfolio dan siap diskusi tentang project terdahulu.',
                'status' => 'Scheduled',
            ]);
        }
    }
}
