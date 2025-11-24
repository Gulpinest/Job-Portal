# Analisis Frontend-Backend: Halaman Lowongans (Company)

## 📋 Ringkasan Eksekutif

Terdapat **beberapa inkonsistensi signifikan** antara frontend dan backend yang perlu diselaraskan:

1. **Skill Handling Tidak Konsisten** - Backend menggunakan 2 sistem skill sekaligus
2. **Data Pelamar Placeholder** - Frontend menampilkan data dummy (hardcoded rand)
3. **Skills Display Format** - Menampilkan dari `keterampilan` (string), bukan dari `lowongan_skill` (relationship)
4. **Missing Relationship Data** - Tidak ada eager loading untuk performa optimal

---

## 🔴 ISSUE #1: Dual Skill System (KRITIS)

### Problem
Backend menggunakan **dua sistem skill sekaligus** yang tidak sinkron:

**System 1: `keterampilan` field (String)**
```
lowongans.keterampilan = "Laravel, API, MySQL" (comma-separated string)
```

**System 2: `lowongan_skill` table (Relationship)**
```
lowongan_skill (id, id_lowongan, nama_skill)
Menyimpan skill satu-satu
```

### Current Data Flow

**Di Create/Store:**
```php
// Backend menyimpan DULU ke keterampilan field
'keterampilan' => $request->keterampilan,  // String comma-separated

// LALU menyimpan ke lowongan_skill
foreach ($request->skills as $skill) {
    LowonganSkill::create([
        'id_lowongan' => $lowongan->id_lowongan,
        'nama_skill' => $skill,
    ]);
}
```

**Di Index View:**
```blade
// Frontend HANYA membaca dari keterampilan field (string)
$skills = is_string($lowongan->keterampilan) 
    ? array_map('trim', explode(',', $lowongan->keterampilan)) 
    : [];

// Tidak pernah menggunakan relationship:
$lowongan->skills (relationship tidak dipakai!)
```

### Impact
- ❌ Data dalam `lowongan_skill` table tidak digunakan di index view
- ❌ Redundant data storage
- ❌ Jika skill diedit, `keterampilan` field dan `lowongan_skill` bisa berbeda
- ❌ Performance issue - 2 tempat penyimpanan sama-sama tidak optimal

### Solution
**Gunakan SATU sistem: `lowongan_skill` relationship (hapus `keterampilan` field)**

---

## 🟠 ISSUE #2: Placeholder Data untuk Pelamar (HIGH)

### Problem
Frontend menampilkan data pelamar menggunakan placeholder:

```blade
@php
    $totalPelamar = $lowongan->pelamar_count ?? rand(5, 50);      // PLACEHOLDER!
    $pelamarBaru = $lowongan->pelamar_new_count ?? rand(1, 5);    // PLACEHOLDER!
@endphp
```

### Issues
- ❌ `$lowongan->pelamar_count` attribute tidak ada di model
- ❌ `$lowongan->pelamar_new_count` attribute tidak ada di model
- ❌ `rand(5, 50)` menghasilkan angka random setiap refresh (user melihat angka berbeda setiap kali)
- ❌ Tidak meaningful untuk company untuk tracking pelamar

### Expected Solution
Backend harus provide:
- `pelamar_count` - Jumlah total pelamar (dari Lamaran model)
- `pelamar_new_count` - Jumlah pelamar baru/tidak dibaca (status tertentu)

### Relationship Check
```php
// Model Lowongan has relationship:
public function lamarans(): HasMany
{
    return $this->hasMany(Lamaran::class, 'id_lowongan');
}

// Bisa gunakan:
$totalPelamar = $lowongan->lamarans()->count();
```

---

## 🟠 ISSUE #3: Skills Display Menggunakan Field String (HIGH)

### Current Implementation
```blade
{{-- Display dari keterampilan field --}}
$skills = is_string($lowongan->keterampilan) 
    ? array_map('trim', explode(',', $lowongan->keterampilan)) 
    : [];

@forelse (array_filter($skills) as $skill)
    <span>{{ $skill }}</span>
@endforelse
```

### Problem
- ❌ Mengambil dari field `keterampilan` (string), bukan dari relationship
- ❌ Tidak konsisten dengan system `lowongan_skill` yang sudah ada
- ❌ Sulit untuk query (WHERE skill = 'Laravel') - string parsing di app level
- ❌ Frontend harus split/parse string, bukan database yang handle

### Expected Implementation
```blade
{{-- Display dari lowongan_skill relationship --}}
@forelse ($lowongan->skills as $skill)
    <span>{{ $skill->nama_skill }}</span>
@endforelse
```

---

## 🟡 ISSUE #4: Missing Data Loading Optimization (MEDIUM)

### Problem
Index view menampilkan banyak data tapi tidak ada eager loading:

```php
public function index()
{
    $company = Auth::user()->company;
    $lowongans = Lowongan::where('id_company', $company->id_company)->latest()->get();
    // ❌ Tidak ada with('skills', 'lamarans')
    return view('lowongans.index', compact('lowongans'));
}
```

### N+1 Query Problem
Jika ada 10 lowongans, akan ada query:
- 1 query untuk ambil lowongans
- 10 query untuk ambil skills (per lowongan)
- 10 query untuk ambil lamarans count (per lowongan)
= **21 queries** 🔴 JADI 1 QUERY dengan eager loading!

### Solution
```php
$lowongans = Lowongan::where('id_company', $company->id_company)
    ->with('skills', 'lamarans')  // Eager load
    ->withCount('lamarans')        // Efficient count
    ->latest()
    ->get();
```

---

## 🟡 ISSUE #5: Missing Lamaran Model Check (MEDIUM)

### Problem
Backend tidak validate apakah **Lamaran model** sudah memiliki `status` field untuk filter "pelamar baru"

```blade
// Frontend ingin tampilin "Pelamar Baru" tapi:
$pelamarBaru = $lowongan->pelamar_new_count ?? rand(1, 5);
```

### Required Implementation
Lamaran model harus punya field untuk track status:
- `status` enum: 'Pending', 'Reviewing', 'Accepted', 'Rejected'
- `read_at` timestamp untuk track apakah sudah dibaca

Lalu bisa query:
```php
$lowongan->lamarans()->whereNull('read_at')->count()
// atau
$lowongan->lamarans()->where('status', 'Pending')->count()
```

---

## 📊 Tabel Perbandingan Current vs Expected

| Aspect | Current | Expected |
|--------|---------|----------|
| **Skill Storage** | 2 tempat: `keterampilan` + `lowongan_skill` | 1 tempat: `lowongan_skill` |
| **Skill Display** | String parsing di view | Relationship query |
| **Pelamar Count** | Placeholder `rand()` | Real count dari `lamarans()->count()` |
| **Pelamar Baru** | Placeholder `rand()` | Count status pending/unread |
| **Query Optimization** | No eager loading (N+1) | With eager loading |
| **Data Consistency** | ❌ Bisa berbeda | ✅ Single source of truth |

---

## ✅ Recommended Changes (Priority)

### PRIORITY 1: Fix Skill System (KRITIS)
1. Update create form: gunakan checkbox dari `allSkills` (bukan text input)
2. Update edit form: pre-select skills dari `lowongan_skill` relationship
3. Remove `keterampilan` field dari display (gunakan relationship)
4. Eventually: migrate data dari `keterampilan` → `lowongan_skill`

### PRIORITY 2: Fix Pelamar Count (HIGH)
1. Add attributes ke Lowongan model:
   ```php
   protected $appends = ['pelamar_count', 'pelamar_new_count'];
   
   public function getPelamarCountAttribute() {
       return $this->lamarans->count();
   }
   ```
2. Use eager loading with count:
   ```php
   ->withCount('lamarans')
   ->with(['lamarans' => function($q) { $q->whereNull('read_at'); }])
   ```

### PRIORITY 3: Optimize Queries (MEDIUM)
1. Add eager loading in index controller
2. Update view to use relationship: `$lowongan->skills`
3. Use `count()` from eager loaded data

### PRIORITY 4: Validate Lamaran Model (MEDIUM)
1. Check if `Lamaran` model has `status` and `read_at` fields
2. Add if missing
3. Implement proper filtering logic

---

## 🔧 Code Changes Needed

### Backend Changes Required

**1. LowonganController::index()**
```php
$lowongans = Lowongan::where('id_company', $company->id_company)
    ->with('skills')
    ->withCount('lamarans')
    ->latest()
    ->get();
```

**2. Lowongan Model**
```php
protected $appends = ['pelamar_baru_count'];

public function getPelamarBaruCountAttribute() {
    return $this->lamarans()
        ->where('status', 'Pending')
        ->orWhereNull('read_at')
        ->count();
}
```

### Frontend Changes Required

**1. Index View - Skills Display**
```blade
@forelse ($lowongan->skills as $skill)
    <span class="px-3 py-0.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full">
        {{ $skill->nama_skill }}
    </span>
@endforelse
```

**2. Index View - Pelamar Count**
```blade
<span class="text-xl font-extrabold text-gray-900">{{ $lowongan->lamarans_count }}</span>
<span class="text-xl font-extrabold text-red-600">{{ $lowongan->pelamar_baru_count }}</span>
```

---

## 📌 Summary Checklist

- [ ] **CRITICAL**: Unify skill system (use only `lowongan_skill`)
- [ ] **HIGH**: Implement real pelamar count (not placeholder)
- [ ] **HIGH**: Implement pelamar baru count logic
- [ ] **MEDIUM**: Add eager loading to controller
- [ ] **MEDIUM**: Update view to use relationships
- [ ] **MEDIUM**: Verify Lamaran model has required fields
- [ ] **LOW**: Remove `keterampilan` field eventually (data migration needed)

---

## 🎯 Testing Checklist

After implementing changes:
- [ ] Skills display correctly from relationship
- [ ] Pelamar count updates when new lamaran added
- [ ] Pelamar baru count only shows unread/pending
- [ ] No random numbers appear on page
- [ ] Page loads faster (fewer queries)
- [ ] Edit form pre-selects correct skills
- [ ] Create form uses skill checkboxes

