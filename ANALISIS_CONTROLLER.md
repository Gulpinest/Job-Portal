# ANALISIS KESELURUHAN CONTROLLER - JOB PORTAL APPLICATION

**Tanggal Analisis:** November 24, 2025  
**Framework:** Laravel 11  
**Database:** MySQL  
**Total Controller:** 8 utama + Auth Controllers

---

## 📋 DAFTAR ISI

1. [RINGKASAN EKSEKUTIF](#ringkasan-eksekutif)
2. [ANALISIS RESUME CONTROLLER (PRIORITAS UTAMA)](#analisis-resume-controller)
3. [ANALISIS SKILL CONTROLLER](#analisis-skill-controller)
4. [ANALISIS PELAMAR CONTROLLER](#analisis-pelamar-controller)
5. [ANALISIS PELAMAR LOWONGAN CONTROLLER](#analisis-pelamar-lowongan-controller)
6. [ANALISIS LOWONGAN CONTROLLER](#analisis-lowongan-controller)
7. [ANALISIS LAMARAN CONTROLLER](#analisis-lamaran-controller)
8. [ANALISIS INTERVIEW SCHEDULE CONTROLLER](#analisis-interview-schedule-controller)
9. [ANALISIS ADMIN CONTROLLER](#analisis-admin-controller)
10. [MATRIX PERBANDINGAN CONTROLLER](#matrix-perbandingan-controller)
11. [REKOMENDASI IMPROVEMENT](#rekomendasi-improvement)

---

## RINGKASAN EKSEKUTIF

### Status Kesehatan Controller

| Aspek | Status | Catatan |
|-------|--------|---------|
| **Architecture** | ✅ Baik | Separation of concerns jelas |
| **Authorization** | ⚠️ Perlu Improvement | Resume/Skill auth bisa lebih robust |
| **Validation** | ✅ Baik | Validation rules komprehensif |
| **Error Handling** | ⚠️ Minimal | Perlu custom error handling |
| **Documentation** | ✅ Baik | Kebanyakan methods terdokumentasi |
| **Consistency** | ⚠️ Inconsistent | Perbedaan pattern antar controller |
| **Performance** | ✅ Baik | Eager loading digunakan correctly |

### Key Findings

1. **ResumeController:** Paling mature, full CRUD dengan file handling
2. **SkillController:** Well-structured, authorization check robust
3. **PelamarController:** Sederhana, auto-create pelamar record jika not exist
4. **PelamarLowonganController:** Smart filtering dengan skill matching
5. **LowonganController:** Complex, kombinasi Lowongan + LowonganSkill
6. **LamaranController:** Minimal, hanya store + duplicate check
7. **InterviewScheduleController:** Complete CRUD dengan security checks
8. **AdminController:** Dashboard + management functions

---

## ANALISIS RESUME CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/ResumeController.php`  
**Purpose:** Mengelola operasi CRUD untuk Resume/CV milik Pelamar  
**Scope:** Full CRUD + File Management  
**Status:** ✅ PRODUCTION READY

### 🏗️ Architecture & Design

#### Class Structure
```
ResumeController
├── index()      → List semua resume pelamar
├── create()     → Form create
├── store()      → Save ke DB + file upload
├── edit()       → Form edit
├── update()     → Update DB + optional file replace
└── destroy()    → Delete DB + file cleanup
```

#### Method Details

##### 1. **index()**
**Purpose:** Menampilkan daftar semua resume milik pelamar yang login

**Logic:**
```
1. Get pelamar dari Auth::user()->pelamar
2. Query: Resume::where('id_pelamar', $pelamar->id_pelamar)->latest()->get()
3. Return: view dengan resumes data
```

**Observations:**
- ✅ Direct eager load dari user relationship
- ✅ latest() sorting natural untuk UX
- ⚠️ Tidak ada pagination (fine untuk biasanya user punya 2-3 resume saja)

**Code Quality:** ★★★★★ (5/5)

---

##### 2. **create()**
**Purpose:** Menampilkan form untuk membuat resume baru

**Logic:**
- Langsung return view tanpa persiapan data khusus
- View bisa hardcode form fields atau fetch dari config

**Observations:**
- ✅ Simple dan clean
- ✅ Tidak ada persiapan data yang tidak perlu
- ✅ Biarkan view yang handle rendering

**Code Quality:** ★★★★★ (5/5)

---

##### 3. **store(Request $request)** ⭐ CRITICAL

**Purpose:** Validate dan save resume baru ke database + upload file

**Validation Rules:**
```php
$request->validate([
    'nama_resume' => 'required|string|max:255',
    'skill' => 'required|string|max:500',           // ← Diperluas dari 255
    'pendidikan_terakhir' => 'required|string|max:50',
    'ringkasan_singkat' => 'nullable|string|max:300',
    'file_resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
]);
```

**Logic Flow:**
```
1. VALIDASI request data
   ├─ nama_resume: required string, max 255 chars
   ├─ skill: required string, max 500 chars
   ├─ pendidikan_terakhir: required string, max 50
   ├─ ringkasan_singkat: nullable, max 300 chars
   └─ file_resume: required file, PDF/DOC/DOCX, max 2MB

2. GET pelamar dari Auth::user()->pelamar

3. UPLOAD file ke storage/public/resumes/
   → $filePath = $request->file('file_resume')->store('resumes', 'public')

4. CREATE resume record
   Resume::create([
       'id_pelamar' => $pelamar->id_pelamar,
       'nama_resume' => $request->nama_resume,
       'skill' => $request->skill,
       'pendidikan_terakhir' => $request->pendidikan_terakhir,
       'ringkasan_singkat' => $request->ringkasan_singkat,
       'file_resume' => $filePath,                    ← Store path, bukan file
   ])

5. REDIRECT ke resumes.index dengan success message
```

**Observations:**
- ✅ File upload ke public storage (accessible untuk download)
- ✅ Path disimpan ke DB (bukan binary file)
- ✅ All required fields divalidasi
- ✅ Error handling via Laravel validation
- ⚠️ Tidak check file integrity/malware (optional: add MIME validation)
- ⚠️ Tidak unique constraint pada nama_resume (user bisa buat multiple dengan nama sama)

**Potential Issues:**
1. **Duplicate File Names:** Jika upload file dengan nama sama, `store('resumes', 'public')` akan overwrite. ✅ Actually OK, Laravel generates unique names by default
2. **Storage Permissions:** Pastikan `storage/app/public/resumes/` writable
3. **Symlink:** Pastikan public/storage symlink ke storage/app/public sudah dibuat

**Code Quality:** ★★★★☆ (4.5/5)

---

##### 4. **edit(Resume $resume)** 

**Purpose:** Tampilkan form edit untuk resume tertentu

**Logic:**
```
1. BINDING Resume model from route (implicit)
2. AUTHORIZATION check
   → if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar)
   →    abort(403, 'ANDA TIDAK PUNYA AKSES UNTUK MENGEDIT RESUME INI')
3. RETURN view dengan data resume
```

**Observations:**
- ✅ Authorization check di place (prevent access to other's resume)
- ✅ Explicit error message (user-friendly)
- ✅ Route model binding digunakan (clean)
- ✅ Pesan error dalam bahasa lokal (user-centric)

**Security Analysis:**
- ✅ PROPER: Checking `id_pelamar` equality
- ✅ User tidak bisa tamper dengan route parameter
- ✅ 403 error adalah status code yang tepat

**Code Quality:** ★★★★★ (5/5)

---

##### 5. **update(Request $request, Resume $resume)** ⭐ CRITICAL

**Purpose:** Update resume data + optional file replacement

**Logic Flow:**
```
1. AUTHORIZATION CHECK (sama seperti edit)
   → if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar)
   →    abort(403)

2. VALIDATION (file_resume bersifat 'nullable')
   $validatedData = $request->validate([
       'nama_resume' => 'required|string|max:255',
       'skill' => 'required|string|max:500',
       'pendidikan_terakhir' => 'required|string|max:50',
       'ringkasan_singkat' => 'nullable|string|max:300',
       'file_resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
   ])

3. PREPARE data (hapus file_resume dari validatedData jika ada)
   $dataToUpdate = $validatedData;
   unset($dataToUpdate['file_resume']);

4. CHECK apakah ada file baru
   if ($request->hasFile('file_resume')) {
       // Hapus file lama
       Storage::disk('public')->delete($resume->file_resume);
       
       // Upload file baru
       $dataToUpdate['file_resume'] = $request->file('file_resume')->store('resumes', 'public');
   }

5. UPDATE database
   $resume->update($dataToUpdate);

6. REDIRECT ke resumes.index dengan success message
```

**Observations:**
- ✅ Smart handling: file 'nullable', jadi user bisa update text saja
- ✅ Old file di-cleanup sebelum upload yang baru
- ✅ Authorization check dalam place
- ✅ Validation yang ketat
- ⚠️ Tidak ada transaction - jika delete gagal tapi update sukses, data inconsistent
- ⚠️ Tidak ada fallback jika file lama sudah dihapus tapi upload baru gagal

**Potential Issues:**

| Issue | Severity | Solution |
|-------|----------|----------|
| File system error handling | Medium | Wrap dalam try-catch |
| Race condition pada file | Low | Use DB transaction |
| Orphaned files jika update gagal | Low | Add file cleanup middleware |

**Improved Code (Optional):**
```php
DB::transaction(function() {
    if ($request->hasFile('file_resume')) {
        Storage::disk('public')->delete($resume->file_resume);
        $dataToUpdate['file_resume'] = $request->file('file_resume')->store('resumes', 'public');
    }
    $resume->update($dataToUpdate);
});
```

**Code Quality:** ★★★★☆ (4/5)

---

##### 6. **destroy(Resume $resume)**

**Purpose:** Hapus resume dari database dan file dari storage

**Logic Flow:**
```
1. AUTHORIZATION CHECK
   → if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar)
   →    abort(403)

2. DELETE file jika ada
   if ($resume->file_resume) {
       Storage::disk('public')->delete($resume->file_resume);
   }

3. DELETE database record
   $resume->delete();

4. REDIRECT dengan success message
```

**Observations:**
- ✅ Authorization check
- ✅ Cleanup file SEBELUM delete record
- ✅ Null-safe check untuk file
- ⚠️ Tidak ada soft delete (bisa implement untuk audit trail)
- ⚠️ Jika ada foreign key dari Lamaran, delete bisa fail

**Error Scenarios:**

| Scenario | Current | Better |
|----------|---------|--------|
| File tidak ada | OK (null check) | ✅ Handled |
| Permission deny | 403 | ✅ Correct |
| File delete fail | Exception | ⚠️ Need handling |
| DB delete fail | Exception | ⚠️ Need handling |

**Code Quality:** ★★★★☆ (4/5)

---

### 📊 ResumeController - SWOT Analysis

#### Strengths ✅
1. **Clear CRUD pattern** - Setiap method punya purpose jelas
2. **Authorization** - Proper authorization checks di edit/update/delete
3. **File handling** - Smart file upload/storage/cleanup
4. **Validation** - Comprehensive validation rules
5. **User-friendly** - Clear error messages
6. **Production-ready** - No critical bugs

#### Weaknesses ⚠️
1. **No transactions** - File/DB operations tidak atomic
2. **Minimal error handling** - Relies on Laravel defaults
3. **No pagination** - index() bisa lambat dengan banyak resume
4. **No soft delete** - Deleted resumes tidak recoverable
5. **No duplicate name check** - User bisa buat multiple resume dengan nama sama
6. **No file validation** - Hanya MIME type, tidak content validation

#### Opportunities 🔧
1. Implement soft delete untuk audit trail
2. Add file storage optimization (compress PDF, dll)
3. Add resume preview feature
4. Add resume sharing (generate public link)
5. Add version control untuk resume
6. Integration dengan skill tagging

#### Threats 🚨
1. Storage space unlimited - bisa DDoS dengan upload besar
2. File path disclosure - download URL bisa diakses public
3. No backup mechanism - jika storage deleted, data hilang
4. Concurrent upload - race condition possible

---

### 🎯 ResumeController - Checklist

```
[✅] CRUD Operations Complete
[✅] Authorization Implemented
[✅] Validation Comprehensive
[✅] File Storage Handled
[✅] Error Messages User-Friendly
[⚠️] Transaction Safety (Partial)
[⚠️] Error Handling (Minimal)
[❌] File Integrity Check (Not Implemented)
[❌] Soft Delete (Not Implemented)
[❌] Pagination (Not Implemented)
[❌] Audit Trail (Not Implemented)
```

---

## ANALISIS SKILL CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/SkillController.php`  
**Purpose:** Manage Pelamar Skills dengan level dan experience tracking  
**Scope:** Full CRUD  
**Status:** ✅ PRODUCTION READY

### 🏗️ Architecture

#### Method Summary

```php
SkillController
├── index()       → List skills untuk pelamar login
├── create()      → Form create skill
├── store()       → Save skill baru
├── edit()        → Form edit skill
├── update()      → Update skill
└── destroy()     → Delete skill
```

### 📋 Detailed Analysis

#### **Data Structure**
```php
Skill {
    id_skill: int (primary key)
    id_pelamar: int (FK)
    nama_skill: string(100)
    level: enum('Beginner','Intermediate','Advanced','Expert')
    years_experience: int(0-70)
    timestamps: created_at, updated_at
}
```

#### **index() Method**
```php
public function index()
{
    $pelamar = Pelamar::where('id_user', Auth::id())->first();
    $skills = $pelamar ? $pelamar->skills : collect();
    return view('skills.index', compact('skills'));
}
```

**Analysis:**
- ✅ Safe null handling dengan collection fallback
- ✅ Eager load bisa ditambah untuk performance
- ⚠️ No pagination (fine, skill jarang banyak)
- **Score:** ★★★★☆ (4/5)

---

#### **store() Method** ⭐

**Validation:**
```php
[
    'nama_skill' => 'required|string|max:100',
    'level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
    'years_experience' => 'nullable|integer|min:0|max:70',
]
```

**Logic:**
```
1. Validate input
2. Get pelamar dari Auth
3. Check if pelamar exists
4. Create skill dengan default years_experience = 0
5. Redirect dengan success
```

**Observations:**
- ✅ Strict enum validation (prevent invalid level)
- ✅ Experience bounds check (0-70 tahun realistic)
- ✅ Null coalescing untuk default value
- ✅ Error handling if pelamar not found
- **Score:** ★★★★★ (5/5)

---

#### **edit() Method**

**Authorization:**
```php
if ($skill->id_pelamar !== $pelamar->id_pelamar) {
    abort(403, 'Akses ditolak.');
}
```

**Observations:**
- ✅ Proper authorization check
- ✅ Readable error message
- ✅ Route model binding
- **Score:** ★★★★★ (5/5)

---

#### **update() Method** ⭐

**Key Logic:**
```php
$pelamar = Pelamar::where('id_user', Auth::id())->first();

if ($skill->id_pelamar !== $pelamar->id_pelamar) {
    abort(403, 'Akses ditolak.');
}

$skill->update([
    'nama_skill' => $request->nama_skill,
    'level' => $request->level,
    'years_experience' => $request->years_experience ?? 0,
]);
```

**Observations:**
- ✅ Same validation sebagai store()
- ✅ Authorization check
- ✅ Default fallback untuk years_experience
- **Score:** ★★★★★ (5/5)

---

#### **destroy() Method**

```php
public function destroy(Skill $skill)
{
    $pelamar = Pelamar::where('id_user', Auth::id())->first();

    if ($skill->id_pelamar !== $pelamar->id_pelamar) {
        abort(403, 'Akses ditolak.');
    }

    $skill->delete();
    return redirect()->route('skills.index')->with('success', 'Skill berhasil dihapus.');
}
```

**Observations:**
- ✅ Clean authorization
- ✅ Direct delete (no soft delete needed untuk skill)
- **Score:** ★★★★★ (5/5)

---

### 💡 SkillController - Strengths

1. **Consistent Authorization Pattern** - Semua method check id_pelamar
2. **Strict Validation** - Enum validation untuk level
3. **Safe Defaults** - years_experience null-safe
4. **Error Handling** - Proper 403 untuk unauthorized access
5. **Scalable** - Bisa di-extend dengan skill endorsement, dll

---

## ANALISIS PELAMAR CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/PelamarController.php`  
**Purpose:** Manage Pelamar Profile  
**Scope:** Read, Update, Delete (Show adalah READ)  
**Status:** ✅ GOOD, tapi bisa improvement

### 🏗️ Methods

#### **show()** ⭐

**Logic:**
```php
$userId = Auth::id();
$pelamar = Pelamar::where('id_user', $userId)->first();

if (!$pelamar) {
    $pelamar = Pelamar::create([...])  // Auto-create
}

$skills = $pelamar->skills;
return view('pelamar.show', compact('pelamar', 'skills'));
```

**Interesting Feature:** Auto-create Pelamar record jika tidak ada

**Observations:**
- ✅ Auto-create pattern convenient
- ⚠️ But violates single responsibility principle
- ⚠️ Better di migration atau seeder

**Analysis:**
- User register → User dibuat
- Seharusnya saat register, Pelamar record juga dibuat
- Tidak perlu auto-create di show()

**Recommendation:**
Move auto-create ke RegisteredUserController:
```php
// In RegisteredUserController
if (user->role_id === PELAMAR_ROLE) {
    Pelamar::create(['id_user' => user->id, ...])
}
```

**Score:** ★★★☆☆ (3/5)

---

#### **edit()**

```php
$pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
return view('pelamar.edit', compact('pelamar'));
```

**Observations:**
- ✅ firstOrFail() - better error handling dari find()
- ✅ Direct pass ke view
- **Score:** ★★★★★ (5/5)

---

#### **update(Request $request)**

**Validation:**
```php
$request->validate([
    'nama_pelamar' => 'required|string|max:255',
    'status_pekerjaan' => 'nullable|string|max:255',
    'no_telp' => 'nullable|string|max:20',
    'alamat' => 'nullable|string|max:255',
    'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
    'tgl_lahir' => 'nullable|date',
]);
```

**Logic:**
```php
$pelamar->update($request->all());
```

**Observations:**
- ✅ Good validation rules
- ⚠️ `$request->all()` is risky (mass assignment)
- Better: `$request->validate([...])` then `$pelamar->update($validated)`
- **Score:** ★★★★☆ (4/5)

---

#### **destroy()**

```php
$pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
$pelamar->delete();
Auth::logout();
```

**Observations:**
- ✅ Delete pelamar
- ✅ Logout user
- ⚠️ No cascade delete check
- ⚠️ Resume, Skills, Lamarans akan orphaned

**Better Approach:**
```php
// Use cascading delete in migration
// Or soft delete untuk recovery

// Or explicit cleanup
$pelamar->skills()->delete();
$pelamar->resumes()->delete();
$pelamar->lamarans()->delete();
$pelamar->delete();
```

**Score:** ★★★☆☆ (3/5)

---

## ANALISIS PELAMAR LOWONGAN CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/PelamarLowonganController.php`  
**Purpose:** Manage job listings viewing untuk Pelamar  
**Scope:** List, Detail, Applications  
**Status:** ✅ EXCELLENT - Smart filtering logic

### 🏗️ Methods

#### **index(Request $request)** ⭐⭐⭐

**Logic (Simplified):**
```
1. Get pelamar skills
2. Build query dengan multiple filters:
   - match: Filter job by matching skills
   - search: Keyword search
   - status: Open/Closed
3. Paginate 9 per page
4. Return view dengan results
```

**Code Analysis:**

```php
$lowongans = Lowongan::with(['company', 'skills'])
    ->latest()
    ->when($request->filled('match') && $request->match === 'true', function ($query) use ($pelamarSkills) {
        $query->matchSkills($pelamarSkills);
    })
    ->when($request->filled('search'), function ($query) use ($request) {
        $query->search($request->search);
    })
    ->when($request->filled('status'), function ($query) use ($request) {
        $query->status($request->status);
    })
    ->paginate(9);
```

**Observations:**
- ✅ Conditional query scopes (when pattern)
- ✅ Eager load relationships (company, skills)
- ✅ Pagination dengan correct count
- ✅ Query is clean dan readable
- ✅ Pelamar skills di-cache di variable (efficient)
- **Score:** ★★★★★ (5/5)

---

#### **show(Lowongan $lowongan)**

```php
$lowongan->load('company', 'skills');
$resumes = Auth::user()->pelamar->resumes;
return view('lowongans.detail', compact('lowongan', 'resumes'));
```

**Observations:**
- ✅ Eager load relationships
- ✅ Load resumes untuk application dropdown
- ✅ Clean passing to view
- **Score:** ★★★★★ (5/5)

---

#### **lamaran_saya()**

```php
$user = Auth::user();
$lamarans = Lamaran::where('id_pelamar', $user->pelamar->id_pelamar)
                   ->with(['lowongan.company', 'resume'])
                   ->latest()
                   ->get();
return view('lowongans.lamaran_saya', compact('lamarans'));
```

**Observations:**
- ✅ Deep eager load (lowongan.company)
- ✅ Eager load resume untuk display
- ✅ Latest sorting (newest applications first)
- **Score:** ★★★★★ (5/5)

---

## ANALISIS LOWONGAN CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/LowonganController.php`  
**Purpose:** Company's job postings management  
**Scope:** Full CRUD + Skill Management  
**Status:** ⚠️ HAS BUGS - Critical issues found

### 🏗️ Methods

#### **index()**

**Logic:**
```php
$company = Auth::user()->company;
$lowongans = Lowongan::where('id_company', $company->id_company)->latest()->get();
return view('lowongans.index', compact('lowongans'));
```

**Observations:**
- ✅ Filter by company
- ✅ Latest sorting
- ⚠️ No pagination (bisa berat dengan banyak lowongan)
- **Score:** ★★★★☆ (4/5)

---

#### **create()**

```php
$allSkills = Skill::all();
$selectedSkills = [];
return view('lowongans.create', compact('allSkills', 'selectedSkills'));
```

**Observations:**
- ✅ Fetch all master skills
- ✅ Empty selection untuk create
- **Score:** ★★★★★ (5/5)

---

#### **store(Request $request)** ⭐⭐ CRITICAL BUGS

**Code:**
```php
$request->validate([
    'judul' => 'required|string|max:255',
    'posisi' => 'required|string|max:255',
    'deskripsi' => 'required|string',
    'status' => 'required|in:Open,Closed',
    'skills' => 'array',
    'skills.*' => 'string|max:255',
]);

$companyId = Auth::user()->company->id_company;

$lowongan = Lowongan::create([
    'id_company' => $company->id_company,  // ❌ BUG: Undefined variable $company
    'judul' => $request->judul,
    // ... fields
]);
```

**BUGS FOUND:**

| Bug | Line | Issue | Fix |
|-----|------|-------|-----|
| **Undefined Variable** | Line with `$company->id_company` | Variable `$company` tidak didefinisikan, hanya `$companyId` | Change ke `$companyId` |
| **Missing validation** | All fields | Form tidak validate lokasi_kantor, gaji, keterampilan | Add to validation |
| **Incomplete fields** | Lowongan::create | Missing tipe_kerja, persyaratan_tambahan | Add to request |

**Corrected Code:**
```php
$companyId = Auth::user()->company->id_company;

$lowongan = Lowongan::create([
    'id_company' => $companyId,  // ✅ Fixed
    'judul' => $request->judul,
    'posisi' => $request->posisi,
    'lokasi_kantor' => $request->lokasi_kantor,
    'gaji' => $request->gaji,
    'keterampilan' => $request->keterampilan,
    'tipe_kerja' => $request->tipe_kerja,
    'persyaratan_tambahan' => $request->persyaratan_tambahan,
    'deskripsi' => $request->deskripsi,
    'status' => $request->status,
]);
```

**Score:** ★★☆☆☆ (2/5) - HAS CRITICAL BUG

---

#### **edit(Lowongan $lowongan)** 

```php
if ($lowongan->id_company !== Auth::user()->company->id_company) {
    abort(403);
}

$allSkills = Skill::all();
$selectedSkills = $lowongan->skills->pluck('nama_skill')->toArray();
return view('lowongans.edit', compact('lowongan', 'allSkills', 'selectedSkills'));
```

**Observations:**
- ✅ Authorization check
- ✅ Eager load existing skills
- ✅ Convert to array untuk form population
- **Score:** ★★★★★ (5/5)

---

#### **update(Request $request, Lowongan $lowongan)** ⚠️

**Issue:** Same as store() - missing fields validation

```php
$lowongan->update($request->only(['judul', 'posisi', 'deskripsi', 'status']));
// ❌ Missing: lokasi_kantor, gaji, keterampilan, tipe_kerja, persyaratan_tambahan
```

**Should be:**
```php
$lowongan->update($request->validate([
    'judul' => 'required|string|max:255',
    'posisi' => 'required|string|max:255',
    'lokasi_kantor' => 'required|string|max:255',
    'gaji' => 'nullable|string|max:255',
    'keterampilan' => 'nullable|string',
    'tipe_kerja' => 'required|string',
    'persyaratan_tambahan' => 'nullable|string',
    'deskripsi' => 'required|string',
    'status' => 'required|in:Open,Closed',
]));
```

**Score:** ★★★☆☆ (3/5)

---

#### **destroy(Lowongan $lowongan)**

```php
if ($lowongan->id_company !== Auth::user()->company->id_company) {
    abort(403);
}

$lowongan->delete();
```

**Observations:**
- ✅ Authorization check
- ✅ Direct delete (cascade delete handles LowonganSkill)
- ✅ Related Lamaran akan orphaned tapi OK
- **Score:** ★★★★★ (5/5)

---

## ANALISIS LAMARAN CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/LamaranController.php`  
**Purpose:** Application submission untuk job listings  
**Scope:** Store only (no update/delete)  
**Status:** ✅ MINIMAL but FUNCTIONAL

### 🏗️ Method

#### **store(Request $request)** ⭐

**Logic:**
```
1. Validate id_lowongan dan id_resume exists
2. Check if already applied (prevent duplicate)
3. Create Lamaran record
4. Redirect back ke pelamar_index
```

**Code Analysis:**
```php
$request->validate([
    'id_lowongan' => 'required|exists:lowongans,id_lowongan',
    'id_resume' => 'required|exists:resumes,id_resume',
]);

$existingLamaran = Lamaran::where('id_lowongan', $request->id_lowongan)
                          ->where('id_pelamar', $user->pelamar->id_pelamar)
                          ->exists();

if ($existingLamaran) {
    return redirect()->back()->with('error', 'Anda sudah melamar lowongan ini.');
}

Lamaran::create([
    'id_lowongan' => $request->id_lowongan,
    'id_resume' => $request->id_resume,
    'id_pelamar' => $user->pelamar->id_pelamar,
    'cv'=> $request->id_resume,  // ❌ BUG: Redundant/confusing
    'status' => 'Diajukan',
]);
```

**BUGS FOUND:**

| Bug | Issue | Fix |
|-----|-------|-----|
| **Redundant Field** | `'cv'=> $request->id_resume'` - redundant dengan id_resume | Remove ini atau merge ke satu field |

**Observations:**
- ✅ Exists validation (FK check)
- ✅ Duplicate prevention
- ✅ Authorization implicit (id_pelamar dari Auth)
- ⚠️ No validation bahwa resume milik pelamar
- ⚠️ No validation bahwa lowongan masih Open
- ⚠️ Redundant cv field

**Potential Issue:**
```
User A bisa apply dengan resume milik User B
if User B somehow shares id_resume-nya

Better add:
Resume::where('id_resume', $request->id_resume)
      ->where('id_pelamar', $user->pelamar->id_pelamar)
      ->exists()
```

**Score:** ★★★☆☆ (3/5)

---

## ANALISIS INTERVIEW SCHEDULE CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/InterviewScheduleController.php`  
**Purpose:** Company manage interview schedules untuk applicants  
**Scope:** Full CRUD  
**Status:** ✅ GOOD - Proper authorization

### 🏗️ Methods Summary

#### **index()**

```php
$schedules = InterviewSchedule::whereHas('lowongan', function($query) use ($company) {
                                    $query->where('id_company', $company->id_company);
                                })->with('lowongan')
                                ->latest()
                                ->get();
```

**Observations:**
- ✅ whereHas untuk security (indirect filter via lowongan)
- ✅ Eager load lowongan
- ✅ Latest sorting
- **Score:** ★★★★★ (5/5)

---

#### **store(Request $request)** ⭐

**Validation:**
```php
$request->validate([
    'id_lowongan' => 'required|exists:lowongans,id_lowongan',
    'type' => 'required|string|max:255',
    'tempat' => 'nullable|string|max:255',
    'waktu_jadwal' => 'required|date_format:Y-m-d\TH:i',
    'catatan' => 'nullable|string',
]);
```

**Authorization:**
```php
$lowongan = Lowongan::find($request->id_lowongan);
if ($lowongan->id_company !== $company->id_company) {
    abort(403);
}
```

**Observations:**
- ✅ Proper datetime format validation
- ✅ Authorization check
- ✅ All required fields
- **Score:** ★★★★★ (5/5)

---

#### **update(Request $request, InterviewSchedule $interviewSchedule)** ⭐

**Double Authorization Check:**
```php
if ($interviewSchedule->id_company !== $company->id_company || 
    $lowongan->id_company !== $company->id_company) {
    abort(403);
}
```

**Observations:**
- ✅ EXCELLENT: Double check (schedule ownership + lowongan ownership)
- ✅ Prevents lateral movement attack
- **Score:** ★★★★★ (5/5)

---

#### **destroy(InterviewSchedule $interviewSchedule)**

```php
if ($interviewSchedule->id_company !== Auth::user()->company->id_company) {
    abort(403);
}

$interviewSchedule->delete();
```

**Observations:**
- ✅ Proper authorization
- **Score:** ★★★★★ (5/5)

---

## ANALISIS ADMIN CONTROLLER

### 📌 Overview

**File:** `app/Http/Controllers/AdminController.php`  
**Purpose:** Admin dashboard dan management functions  
**Scope:** Dashboard, Users management, Activity logs  
**Status:** ✅ GOOD - Well-structured

### 🏗️ Methods

#### **dashboard()** ✅

```php
$totalUsers = User::count();
$pelamars = User::where('role_id', 2)->count();
$companies = User::where('role_id', 3)->count();
$recentLogs = Log::with('user')->latest()->take(10)->get();
```

**Observations:**
- ✅ Stats aggregation
- ✅ Eager load user dalam logs
- ⚠️ Hardcoded role_id (should use Role constants)
- **Score:** ★★★★☆ (4/5)

---

#### **users(Request $request)** ✅

**Filtering:**
```php
if ($request->filled('role_id')) {
    $query->where('role_id', $request->role_id);
}

if ($request->filled('search')) {
    $query->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('email', 'like', '%' . $request->search . '%');
}

$users = $query->paginate(15);
```

**Observations:**
- ✅ Filter by role
- ✅ Search by name/email (OR logic)
- ✅ Pagination 15 per page
- **Score:** ★★★★★ (5/5)

---

#### **deleteUser(User $user)** ⭐

**Authorization:**
```php
if ($user->isAdmin()) {
    return redirect()->back()->with('error', 'Tidak dapat menghapus pengguna admin.');
}

if ($user->id === auth()->id()) {
    return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
}

$user->delete();
```

**Observations:**
- ✅ Prevent admin deletion
- ✅ Prevent self-deletion
- ✅ Clear error messages
- **Score:** ★★★★★ (5/5)

---

#### **logs(Request $request)** ✅

**Filtering + Search:**
```php
if ($request->filled('user_id')) {
    $query->where('id_user', $request->user_id);
}

if ($request->filled('search')) {
    $query->where('aksi', 'like', '%' . $request->search . '%');
}

$logs = $query->latest()->paginate(20);
$users = User::select('id', 'name')->get();
```

**Observations:**
- ✅ Filter by user
- ✅ Search by action
- ✅ Pagination 20 per page
- ✅ Load users untuk dropdown
- **Score:** ★★★★★ (5/5)

---

## MATRIX PERBANDINGAN CONTROLLER

### Overall Architecture Score

| Controller | CRUD | Auth | Validation | Error Handling | Score |
|------------|------|------|-----------|---|-------|
| Resume | ✅✅✅ | ✅✅ | ✅✅✅ | ⚠️ | ★★★★☆ |
| Skill | ✅✅✅ | ✅✅ | ✅✅✅ | ✅ | ★★★★★ |
| Pelamar | ✅✅ | - | ✅✅ | ⚠️ | ★★★★☆ |
| PelamarLowongan | ✅✅ | - | ✅ | ✅ | ★★★★★ |
| Lowongan | ✅✅✅ | ✅ | ⚠️ | ❌ | ★★☆☆☆ |
| Lamaran | ✅ | ⚠️ | ✅ | ✅ | ★★★☆☆ |
| InterviewSchedule | ✅✅✅ | ✅✅ | ✅✅✅ | ✅ | ★★★★★ |
| Admin | ✅✅ | ✅✅ | ✅ | ✅ | ★★★★★ |

---

## REKOMENDASI IMPROVEMENT

### 🔴 CRITICAL (Must Fix Immediately)

#### 1. **LowonganController::store() - Undefined Variable**

**File:** `app/Http/Controllers/LowonganController.php` Line ~40

**Issue:**
```php
$companyId = Auth::user()->company->id_company;
// ...
$lowongan = Lowongan::create([
    'id_company' => $company->id_company,  // ❌ $company undefined
]);
```

**Fix:**
```php
$lowongan = Lowongan::create([
    'id_company' => $companyId,  // ✅ Use defined variable
]);
```

**Impact:** Cannot create lowongan currently

---

#### 2. **LowonganController - Missing Field Validation & Population**

**Issue:** Form tidak validate lokasi_kantor, gaji, etc. tapi model expect-nya

**Fix:** Add to validation + request data

```php
$request->validate([
    // Existing...
    'lokasi_kantor' => 'required|string|max:255',
    'gaji' => 'nullable|string|max:255',
    'keterampilan' => 'nullable|string',
    'tipe_kerja' => 'required|string|max:50',
    'persyaratan_tambahan' => 'nullable|string',
]);
```

---

#### 3. **LamaranController - Missing Resume Authorization Check**

**Issue:** User bisa apply dengan resume milik orang lain

**Fix:**
```php
$resume = Resume::where('id_resume', $request->id_resume)
                ->where('id_pelamar', $user->pelamar->id_pelamar)
                ->firstOrFail();

if (!$resume) {
    return redirect()->back()->with('error', 'Resume tidak valid');
}
```

---

### 🟡 HIGH PRIORITY (Should Fix Soon)

#### 1. **ResumeController - Add Transaction for File Operations**

**Issue:** If file delete succeeds but DB update fails, inconsistency

**Fix:**
```php
DB::transaction(function() {
    if ($request->hasFile('file_resume')) {
        Storage::disk('public')->delete($resume->file_resume);
        $filePath = $request->file('file_resume')->store('resumes', 'public');
    }
    $resume->update($dataToUpdate);
});
```

---

#### 2. **PelamarController - Remove Auto-Create from show()**

**Issue:** Violates single responsibility

**Fix:** Create in RegisteredUserController instead

```php
// RegisteredUserController::store()
$user = User::create([...]);
if ($user->role_id == PELAMAR_ROLE) {
    Pelamar::create(['id_user' => $user->id, ...]);
}
```

---

#### 3. **LowonganController::index() - Add Pagination**

**Issue:** No pagination, could be slow with many lowongans

**Fix:**
```php
$lowongans = Lowongan::where('id_company', $company->id_company)
                     ->latest()
                     ->paginate(15);  // Add pagination
```

---

### 🟢 MEDIUM PRIORITY (Nice to Have)

#### 1. **Add Logging for Sensitive Operations**

Create middleware atau use Laravel's logging:
```php
Log::info('Resume deleted', ['resume_id' => $resume->id, 'user_id' => Auth::id()]);
```

---

#### 2. **Implement Soft Delete untuk Resume dan Lowongan**

```php
$table->softDeletes();  // Add to migration
```

---

#### 3. **Add File Storage Limits**

```php
// In middleware
if (Storage::disk('public')->size('resumes') > 5 * 1024 * 1024 * 1024) { // 5GB
    abort(503, 'Storage penuh');
}
```

---

#### 4. **Standardize Error Messages**

Current: Mixed Indonesian dan English error messages

---

### 📋 SUMMARY TABLE

| Issue | Controller | Severity | Effort | Impact |
|-------|-----------|----------|--------|--------|
| Undefined $company variable | Lowongan | 🔴 Critical | 5min | Create lowongan broken |
| Missing validation fields | Lowongan | 🔴 Critical | 10min | Data inconsistency |
| Missing resume auth check | Lamaran | 🔴 Critical | 10min | Security issue |
| No transaction in update | Resume | 🟡 High | 10min | Data corruption risk |
| Auto-create in show() | Pelamar | 🟡 High | 20min | Design issue |
| No pagination | Lowongan | 🟡 High | 5min | Performance issue |
| No logging | All | 🟢 Medium | 30min | Audit trail missing |
| No soft delete | Resume | 🟢 Medium | 15min | Recovery not possible |
| File size limits | Resume | 🟢 Medium | 15min | DDoS risk |
| Inconsistent messages | All | 🟢 Medium | 20min | UX issue |

---

## CONCLUSION

### Overall Assessment: ⭐⭐⭐⭐ (4/5)

**Strengths:**
- Good separation of concerns
- Authorization checks mostly in place
- Validation comprehensive
- Smart filtering logic (PelamarLowonganController)
- Admin controller well-structured

**Weaknesses:**
- LowonganController has critical bugs
- Inconsistent error handling
- Missing some security checks
- No transaction safety
- Minimal logging/audit trail

**Next Steps:**
1. ✅ Fix critical bugs in LowonganController (5 minutes)
2. ✅ Add missing validation (10 minutes)
3. ✅ Add resume authorization check (10 minutes)
4. ⏳ Refactor for transactions and error handling (1-2 hours)
5. ⏳ Add logging and audit trail (1 hour)

