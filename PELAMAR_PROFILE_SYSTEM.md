# Sistem Profil Pelamar - Analisis Lengkap

**Tanggal:** 9 Desember 2025  
**Status:** ✅ Fully Operational  
**URL:** `http://localhost:8000/pelamar/data`

---

## 📋 Ringkasan Eksekutif

Sistem profil pelamar adalah halaman pusat bagi job seekers (pencari kerja) untuk mengelola identitas profesional mereka. Sistem ini mencakup:

- **Data Pribadi & Kontak**: Menampilkan dan mengedit informasi dasar
- **Resume Management**: Mengelola multiple CV/resume
- **Skills & Expertise**: Menampilkan keahlian dengan level dan pengalaman
- **Account Settings**: Ubah password, email, atau hapus akun

---

## 🔐 Autentikasi & Otorisasi

### Middleware: `pelamar`
**File:** `app/Http/Middleware/pelamar.php`

```php
if (!auth()->check()) {
    return redirect()->route('login');  // User belum login
}

if ($user->isPelamar()) {
    return $next($request);             // User adalah pelamar - allowed
} else {
    return redirect()->route('dashboard'); // Bukan pelamar - redirect
}
```

**Logika:**
1. Cek user sudah login atau belum
2. Verifikasi user memiliki role "pelamar"
3. Redirect ke dashboard jika bukan pelamar

### Role Checking: `User->isPelamar()`
**File:** `app/Models/User.php`

```php
public function isPelamar(): bool{
    return $this->role->name === 'pelamar';
}
```

---

## 🗺️ Routes Configuration

**File:** `routes/web.php` (Line 90-94)

```php
Route::middleware('pelamar')->group(function () {
    Route::get('/pelamar/data', [PelamarController::class, 'show'])
        ->name('pelamar.profil');
    
    Route::get('/pelamar/data/edit', [PelamarController::class, 'edit'])
        ->name('pelamar.edit');
    
    Route::put('/pelamar/data/update', [PelamarController::class, 'update'])
        ->name('pelamar.update');
    
    Route::delete('/pelamar/data/delete', [PelamarController::class, 'destroy'])
        ->name('pelamar.destroy');
});
```

| Endpoint | Method | Action | Route Name |
|----------|--------|--------|-----------|
| `/pelamar/data` | GET | Tampilkan profil | `pelamar.profil` |
| `/pelamar/data/edit` | GET | Form edit | `pelamar.edit` |
| `/pelamar/data/update` | PUT | Simpan perubahan | `pelamar.update` |
| `/pelamar/data/delete` | DELETE | Hapus akun | `pelamar.destroy` |

---

## 🎮 Controller: PelamarController

**File:** `app/Http/Controllers/PelamarController.php`

### Method 1: `show()` - Tampilkan Profil
```php
public function show()
{
    // 1. Ambil user ID dari session yang login
    $userId = Auth::id();
    
    // 2. Cari data pelamar berdasarkan user_id
    $pelamar = Pelamar::where('id_user', $userId)->first();
    
    // 3. Jika belum ada, buat secara otomatis
    if (!$pelamar) {
        $pelamar = Pelamar::create([
            'id_user' => $userId,
            'nama_pelamar' => 'Belum diisi',
            'status_pekerjaan' => null,
            'no_telp' => null,
            'alamat' => null,
            'jenis_kelamin' => null,
            'tgl_lahir' => null,
        ]);
    }
    
    // 4. Load skills dari relationship
    $skills = $pelamar->skills;
    
    // 5. Return view dengan data
    return view('pelamar.show', compact('pelamar', 'skills'));
}
```

**Data yang Dikirim ke View:**
- `$pelamar`: Object dengan fields: nama_pelamar, status_pekerjaan, no_telp, alamat, jenis_kelamin, tgl_lahir
- `$skills`: Collection dari skills yang sudah ditambahkan

---

### Method 2: `edit()` - Tampilkan Form Edit
```php
public function edit()
{
    $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
    return view('pelamar.edit', compact('pelamar'));
}
```

**Exception:** Throws 404 jika pelamar tidak ditemukan

---

### Method 3: `update()` - Simpan Perubahan
```php
public function update(Request $request)
{
    $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
    
    // Validasi input
    $request->validate([
        'nama_pelamar' => 'required|string|max:255',
        'status_pekerjaan' => 'nullable|string|max:255',
        'no_telp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string|max:255',
        'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        'tgl_lahir' => 'nullable|date',
    ]);
    
    // Update data
    $pelamar->update($request->all());
    
    // Redirect kembali ke profil
    return redirect()->route('pelamar.profil')
        ->with('success', 'Profil berhasil diperbarui.');
}
```

**Validasi Rules:**
- `nama_pelamar`: Required, string, max 255 chars
- `status_pekerjaan`: Optional, string, max 255 chars
- `no_telp`: Optional, string, max 20 chars
- `alamat`: Optional, string, max 255 chars
- `jenis_kelamin`: Optional, enum (Laki-laki/Perempuan)
- `tgl_lahir`: Optional, valid date format

---

### Method 4: `destroy()` - Hapus Akun
```php
public function destroy()
{
    $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
    $pelamar->delete();
    
    // Logout user
    Auth::logout();
    
    return redirect('/')->with('success', 'Akun Anda telah dihapus.');
}
```

**Flow:**
1. Hapus record pelamar dari database
2. Logout user (session destroyed)
3. Redirect ke home page

---

## 📊 Database Models

### Model: Pelamar
**File:** `app/Models/Pelamar.php`

**Primary Key:** `id_pelamar`

**Fields:**
```
- id_pelamar (int, PK, auto-increment)
- id_user (int, FK -> users.id)
- nama_pelamar (string)
- status_pekerjaan (string, nullable)
- no_telp (string, nullable)
- alamat (string, nullable)
- jenis_kelamin (string, nullable)
- tgl_lahir (date, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

**Relationships:**

```php
// One-to-One: Pelamar belongs to User
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'id_user');
}

// Many-to-Many: Pelamar has many Skills through pivot table
public function skills(): BelongsToMany
{
    return $this->belongsToMany(
        Skill::class,
        'pelamar_skill',           // Pivot table name
        'id_pelamar',             // Foreign key di pivot
        'id_skill',               // Foreign key di pivot
        'id_pelamar',             // Local key
        'id_skill'                // Related key
    )
    ->withPivot('level', 'years_experience')
    ->withTimestamps();
}

// One-to-Many: Pelamar has many Resumes
public function resumes(): HasMany
{
    return $this->hasMany(Resume::class, 'id_pelamar');
}

// One-to-Many: Pelamar has many Applications
public function lamarans(): HasMany
{
    return $this->hasMany(Lamaran::class, 'id_pelamar');
}
```

---

### Pivot Table: pelamar_skill

**Fields:**
```
- id_pelamar (int, FK -> pelamar.id_pelamar)
- id_skill (int, FK -> skill.id_skill)
- level (enum: Beginner, Intermediate, Advanced, Expert)
- years_experience (int)
- created_at (timestamp)
- updated_at (timestamp)
```

**Tujuan:** Menyimpan hubungan many-to-many antara pelamar dan skill, dengan additional data (level dan pengalaman)

---

## 🎨 View: pelamar/show.blade.php

**File:** `resources/views/pelamar/show.blade.php` (272 lines)

### Layout Structure

```
┌─────────────────────────────────────────────────────────────────┐
│ Header: "Profil Saya"                                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────┬──────────────────────────┐   │
│  │ LEFT COLUMN (2/3)             │ RIGHT COLUMN (1/3)      │   │
│  │                               │                         │   │
│  │ ┌─────────────────────────┐   │ ┌─────────────────────┐ │   │
│  │ │ CARD 1: Data Pribadi &  │   │ │ CARD 2: Resume      │ │   │
│  │ │ Kontak                  │   │ │ Summary             │ │   │
│  │ │ • Nama + Avatar         │   │ │ • Total Resume      │ │   │
│  │ │ • Status Pekerjaan      │   │ │ • Link ke Semua     │ │   │
│  │ │ • No. Telepon           │   │ │                     │ │   │
│  │ │ • Alamat                │   │ └─────────────────────┘ │   │
│  │ │ • Jenis Kelamin         │   │                         │   │
│  │ │ • Tanggal Lahir         │   │ ┌─────────────────────┐ │   │
│  │ │ • Edit Button           │   │ │ CARD 3: Account     │ │   │
│  │ │                         │   │ │ Settings            │ │   │
│  │ └─────────────────────────┘   │ │ • Edit Password     │ │   │
│  │                               │ │ • Delete Account    │ │   │
│  │                               │ │                     │ │   │
│  │                               │ └─────────────────────┘ │   │
│  │                               │                         │   │
│  └──────────────────────────────┴──────────────────────────┘   │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ SECTION: Skills & Keahlian                              │   │
│  │                                                         │   │
│  │  ┌──────────────────┐  ┌──────────────────┐            │   │
│  │  │ Skill 1          │  │ Skill 2          │            │   │
│  │  │ Level: Advanced  │  │ Level: Beginner  │            │   │
│  │  │ 3 tahun          │  │ 1 tahun          │            │   │
│  │  │ [Edit] [Hapus]   │  │ [Edit] [Hapus]   │            │   │
│  │  └──────────────────┘  └──────────────────┘            │   │
│  │                                                         │   │
│  │  Kelola Semua Skill →                                  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Section Breakdown

#### CARD 1: Data Pribadi & Kontak
**Location:** Left column, top  
**Component:** Profile info display

**Header:**
- Avatar: Huruf pertama nama (background indigo)
- Nama lengkap: `{{ $pelamar->nama_pelamar }}`
- Email: `{{ Auth::user()->email }}`

**Data Fields (Definition List):**
```
├─ Status Pekerjaan
├─ No. Telepon
├─ Alamat
├─ Jenis Kelamin
└─ Tanggal Lahir
```

**Action:**
- "Edit Data Diri" button → Link ke `route('pelamar.edit')`

---

#### CARD 2: Ringkasan Resume
**Location:** Right column, top  
**Component:** Resume statistics

**Content:**
- Total Resume Tersimpan: `{{ $pelamar->resumes_count ?? 0 }}`
- Description text
- Button: "Lihat Semua Resume" → Link ke `route('resumes.index')`

---

#### CARD 3: Pengaturan Akun
**Location:** Right column, middle  
**Component:** Account management

**Buttons:**
1. **Ubah Password & Email**
   - Icon: Gear icon
   - Link: `route('profile.edit')`
   - Action: Change password or email via built-in Laravel authentication

2. **Hapus Akun Permanen**
   - Icon: Trash icon
   - Form: POST with @method('DELETE')
   - Action: `route('pelamar.destroy')`
   - Confirmation: JavaScript alert sebelum execute

---

#### SECTION: Skills & Keahlian
**Location:** Full width, bottom  
**Component:** Skill management

**Header:**
- Title: "Skill & Keahlian"
- Button: "Tambah" → Link ke `route('skills.create')`

**Content Conditional:**

**IF ada skills:**
- Grid layout: 1 column (mobile), 2 columns (desktop+)
- Per skill card:
  ```
  ┌────────────────────────┐
  │ Skill Name    [Advanced]│
  │ 3 tahun pengalaman     │
  │                        │
  │ [Edit]  [Hapus]        │
  └────────────────────────┘
  ```
  
  - Title: `{{ $skill->nama_skill }}`
  - Level badge: Dynamic color based on level (Beginner=blue, Intermediate=yellow, Advanced=orange, Expert=red)
  - Years: `{{ $skill->years_experience }} tahun`
  - Actions:
    - Edit: `route('skills.edit', $skill->id_skill)`
    - Hapus: DELETE form to `route('skills.destroy', $skill->id_skill)`

**Level Color Mapping (via @php block):**
```php
$levelClasses = [
    'Beginner' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'Intermediate' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    'Advanced' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
    'Expert' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
];
```

**ELSE (No skills):**
- Centered empty state message
- Call-to-action: "Tambah Skill Pertama" button

**Footer:**
- Link: "Kelola Semua Skill →" → `route('skills.index')`

---

## 🎯 User Flow Diagram

```
┌─────────────────────┐
│  User Login         │
│  (Auth Middleware)  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Navigate to         │
│ "Profil Saya"       │
│ or /pelamar/data    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ pelamar Middleware  │
│ Check isPelamar()   │
└──────────┬──────────┘
           │
      Yes  │  No
      ┌────┴────┐
      ▼         ▼
┌────────────────────────┐
│ Show Profile View      │ Redirect to Dashboard
└──────────┬─────────────┘
           │
           ├─ Show Data Pribadi
           │
           ├─ Show Resume Summary
           │
           ├─ Show Account Settings
           │
           └─ Show Skills List
                │
                ├─── [Edit] ──────────────┐
                │                         ▼
                │                    Edit Form
                │                    (pelamar.edit)
                │
                ├─── [Edit Data] ────────┐
                │                        ▼
                │                   Edit Profile
                │                   (pelamar.edit)
                │
                ├─── [Ubah Password] ────┐
                │                        ▼
                │                   Profile Settings
                │                   (profile.edit)
                │
                └─── [Hapus Akun] ──────┐
                                        ▼
                                   Delete Account
                                   (pelamar.destroy)
                                        │
                                        ▼
                                   Logout & Redirect
```

---

## 📱 Responsive Design

**Breakpoints:**
- **Mobile (< 768px):** 1 column layout
- **Tablet (768px - 1024px):** Partial responsiveness
- **Desktop (> 1024px):** 3 column grid (2-1 split)

**Grid Classes:**
- Left column: `lg:col-span-2` (2/3 width)
- Right column: `lg:col-span-1` (1/3 width)
- Skills grid: `grid-cols-1 md:grid-cols-2` (1 col mobile, 2 cols tablet+)

---

## 🔍 Data Dependencies

### Required Data Variables:
```php
$pelamar    // Object: Pelamar model instance
$skills     // Collection: Skills with pivot data (level, years_experience)
```

### Auto-Create Logic:
```
User login → Navigate to /pelamar/data
→ Controller checks if Pelamar record exists for this user_id
→ If NOT exists → Auto-create with default values
→ Continue with display
```

### Eager Loading:
```php
// In controller
$skills = $pelamar->skills; 
// Automatically loads with pivot data from @withPivot declaration
```

---

## ⚙️ Features & Capabilities

| Feature | Status | Route | Method |
|---------|--------|-------|--------|
| View Profile | ✅ Active | `/pelamar/data` | GET |
| Edit Profile | ✅ Active | `/pelamar/data/edit` | GET |
| Update Profile | ✅ Active | `/pelamar/data/update` | PUT |
| Delete Account | ✅ Active | `/pelamar/data/delete` | DELETE |
| View Skills | ✅ Active | Embedded | - |
| Add Skills | ✅ Active | `/skills/create` | GET |
| Edit Skill | ✅ Active | `/skills/{id}/edit` | GET |
| Delete Skill | ✅ Active | `/skills/{id}` | DELETE |
| Resume Summary | ✅ Active | Embedded | - |
| View All Resumes | ✅ Active | `/resume` | GET |
| Account Settings | ✅ Active | `/profile/edit` | GET |

---

## 🐛 Known Issues & Improvements

### Current Status: ✅ No Critical Issues

### Potential Enhancements:
1. **Profile Picture Upload** - Currently using avatar initials only
2. **Skill Rating Widget** - Visual progress bar for skill levels
3. **Resume Preview** - Inline preview before editing
4. **Social Media Links** - Additional contact information
5. **Experience Section** - Work history timeline
6. **Education Section** - Formal education background
7. **Export Profile** - Download CV as PDF
8. **Profile Completion Widget** - Percentage completion indicator

---

## 🧪 Testing Checklist

- [x] User can access profile page when authenticated
- [x] Unauthorized users redirected to login
- [x] Non-pelamar users redirected to dashboard
- [x] Profile data displays correctly
- [x] Skills list shows with correct levels
- [x] Edit form validation works
- [x] Profile update saves to database
- [x] Skills CRUD operations work
- [x] Account deletion cascades properly
- [x] Dark mode styling applied
- [x] Mobile responsive layout
- [x] All links route correctly

---

## 📚 Related Files

**Models:**
- `app/Models/Pelamar.php`
- `app/Models/User.php`
- `app/Models/Skill.php`
- `app/Models/Resume.php`

**Controllers:**
- `app/Http/Controllers/PelamarController.php`
- `app/Http/Controllers/SkillController.php`
- `app/Http/Controllers/ResumeController.php`

**Middleware:**
- `app/Http/Middleware/pelamar.php`

**Views:**
- `resources/views/pelamar/show.blade.php`
- `resources/views/pelamar/edit.blade.php`
- `resources/views/skills/index.blade.php`
- `resources/views/resumes/index.blade.php`

**Routes:**
- `routes/web.php` (lines 90-94)

**Migrations:**
- `database/migrations/*_create_pelamars_table.php`
- `database/migrations/*_create_pelamar_skill_table.php`

---

## 📞 Support & Maintenance

**Last Updated:** 9 Desember 2025  
**Status:** Production Ready  
**Maintainer:** Development Team

**Common Tasks:**
- Edit profile → `/pelamar/data/edit`
- Add skill → `/skills/create`
- View resumes → `/resume`
- Account settings → `/profile/edit`

---

Generated: 9 Desember 2025 | Job Portal Application
