<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Roles;
use App\Models\User;
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
        Pelamar::firstOrCreate(
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
                'keterampilan' => 'Laravel, PHP, MySQL, Redis, Docker',
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
                'keterampilan' => 'React, JavaScript, Tailwind CSS, Redux, TypeScript',
                'deskripsi' => 'Bergabunglah dengan tim frontend kami sebagai React Developer. Anda akan mengembangkan user interface yang modern dan responsive untuk aplikasi kami.',
                'persyaratan_tambahan' => "• Pengalaman 3+ tahun dengan React\n• Strong understanding of JavaScript ES6+\n• Familiar dengan CSS frameworks (Tailwind, Bootstrap)\n• Git dan agile methodology\n• Portfolio atau GitHub profile yang bagus\n• English proficiency yang cukup untuk komunikasi tim",
                'status' => 'Open',
            ],
            [
                'judul' => 'Data Scientist',
                'posisi' => 'Data Science',
                'lokasi_kantor' => 'Remote',
                'gaji' => 'Rp 18,000,000 - Rp 25,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Python, Machine Learning, SQL, TensorFlow, Pandas',
                'deskripsi' => 'Kami mencari Data Scientist untuk menganalisis data besar dan membangun model machine learning yang dapat meningkatkan produk kami.',
                'persyaratan_tambahan' => "• Pengalaman 4+ tahun dalam data science\n• Master dalam Statistik, Mathematics, atau Computer Science\n• Proficient dengan Python dan SQL\n• Experience dengan Machine Learning libraries\n• Knowledge tentang Big Data tools\n• Strong problem-solving skills",
                'status' => 'Open',
            ],
            [
                'judul' => 'UI/UX Designer',
                'posisi' => 'Design',
                'lokasi_kantor' => 'Jakarta',
                'gaji' => 'Rp 10,000,000 - Rp 14,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Figma, UI Design, UX Research, Prototyping',
                'deskripsi' => 'Kami sedang membuka posisi UI/UX Designer untuk merancang interface dan user experience yang amazing untuk produk kami.',
                'persyaratan_tambahan' => "• Minimal 3 tahun pengalaman sebagai UI/UX Designer\n• Expert dengan Figma atau Adobe XD\n• Pemahaman tentang design principles dan user-centered design\n• Portofolio yang menunjukkan design work\n• Collaboration skills yang baik\n• Passion untuk continuous learning",
                'status' => 'Open',
            ],
            [
                'judul' => 'DevOps Engineer',
                'posisi' => 'DevOps',
                'lokasi_kantor' => 'Surabaya',
                'gaji' => 'Rp 14,000,000 - Rp 19,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Docker, Kubernetes, CI/CD, AWS, Linux',
                'deskripsi' => 'Diperlukan DevOps Engineer untuk mengelola infrastructure dan deployment pipeline kami di cloud.',
                'persyaratan_tambahan' => "• Pengalaman 3+ tahun sebagai DevOps Engineer\n• Strong knowledge tentang Docker dan Kubernetes\n• Familiar dengan cloud platforms (AWS/GCP/Azure)\n• Experience dengan IaC tools (Terraform, Ansible)\n• Understanding tentang monitoring dan logging\n• Linux administration skills",
                'status' => 'Open',
            ],
            [
                'judul' => 'Mobile Developer (Flutter)',
                'posisi' => 'Mobile Developer',
                'lokasi_kantor' => 'Remote',
                'gaji' => 'Rp 13,000,000 - Rp 17,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Flutter, Dart, Firebase, REST API',
                'deskripsi' => 'Kami mencari Flutter Developer untuk mengembangkan aplikasi mobile cross-platform yang berkualitas tinggi.',
                'persyaratan_tambahan' => "• Pengalaman 2+ tahun dengan Flutter\n• Solid understanding of Dart language\n• Experience dengan Firebase\n• Familiar dengan REST API integration\n• Understanding of mobile app architecture\n• Can work independently and in team",
                'status' => 'Open',
            ],
            [
                'judul' => 'Business Analyst',
                'posisi' => 'Business Analysis',
                'lokasi_kantor' => 'Jakarta',
                'gaji' => 'Rp 11,000,000 - Rp 15,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Business Analysis, Requirements Gathering, SQL',
                'deskripsi' => 'Posisi Business Analyst untuk menganalisis kebutuhan bisnis dan mentranslate menjadi requirement teknis.',
                'persyaratan_tambahan' => "• Pengalaman 3+ tahun sebagai Business Analyst\n• Strong analytical and problem-solving skills\n• Experience dengan requirement gathering dan documentation\n• Basic SQL knowledge\n• Excellent communication skills\n• Knowledge tentang software development lifecycle",
                'status' => 'Open',
            ],
            [
                'judul' => 'QA Automation Engineer',
                'posisi' => 'Quality Assurance',
                'lokasi_kantor' => 'Bandung',
                'gaji' => 'Rp 9,000,000 - Rp 13,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Selenium, Java, Test Automation, Cypress',
                'deskripsi' => 'Kami membutuhkan QA Automation Engineer untuk mengembangkan dan maintain automation test suite kami.',
                'persyaratan_tambahan' => "• Pengalaman 2+ tahun dengan test automation\n• Knowledge tentang Selenium dan/atau Cypress\n• Programming skills (Java, Python, JavaScript)\n• Understanding tentang testing methodologies\n• Attention to detail\n• Good documentation skills",
                'status' => 'Open',
            ],
            [
                'judul' => 'Project Manager',
                'posisi' => 'Project Management',
                'lokasi_kantor' => 'Jakarta',
                'gaji' => 'Rp 12,000,000 - Rp 18,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Project Management, Agile, Scrum, Leadership',
                'deskripsi' => 'Mencari Project Manager yang berpengalaman untuk memimpin tim dan mengelola proyek-proyek besar kami.',
                'persyaratan_tambahan' => "• Pengalaman 4+ tahun sebagai Project Manager\n• Sertifikasi PMP atau Scrum Master\n• Expert dengan Agile dan Scrum\n• Strong leadership dan communication skills\n• Experience dengan project management tools (Jira, Asana)\n• Proven track record dalam delivery on time",
                'status' => 'Open',
            ],
            [
                'judul' => 'Security Engineer',
                'posisi' => 'Security',
                'lokasi_kantor' => 'Remote',
                'gaji' => 'Rp 16,000,000 - Rp 22,000,000',
                'tipe_kerja' => 'Full Time',
                'keterampilan' => 'Security, Penetration Testing, Network Security, OWASP',
                'deskripsi' => 'Diperlukan Security Engineer untuk memastikan security infrastructure dan melakukan vulnerability assessment.',
                'persyaratan_tambahan' => "• Pengalaman 5+ tahun dalam cybersecurity\n• Sertifikasi seperti CEH, OSCP, atau CISSP\n• Knowledge tentang network security dan cryptography\n• Experience dengan penetration testing\n• Understanding tentang OWASP dan security best practices\n• Security compliance knowledge (ISO 27001, dll)",
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
    }
}
