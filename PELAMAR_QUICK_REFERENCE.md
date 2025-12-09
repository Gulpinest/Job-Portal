# Profil Pelamar - Quick Reference Guide

## 🎯 Component Summary

| Component | Location | Type | Data Source | Status |
|-----------|----------|------|-------------|--------|
| **Authentication** | System-wide | Middleware | `pelamar.php` | ✅ Active |
| **Data Pribadi Card** | Left, Top | Display | `$pelamar` model | ✅ Working |
| **Resume Summary** | Right, Top | Widget | `$pelamar->resumes_count` | ✅ Working |
| **Account Settings** | Right, Middle | Links | Routes | ✅ Working |
| **Skills Section** | Full Width, Bottom | Grid | `$skills` collection | ✅ Working |

---

## 🔄 Data Flow Architecture

```
┌─────────────┐
│   User      │
│  (Logged)   │
└──────┬──────┘
       │ Navigates to /pelamar/data
       ▼
┌──────────────────────────────────┐
│   pelamar Middleware             │
│   - Check if authenticated       │
│   - Verify role is 'pelamar'     │
└──────┬───────────────────────────┘
       │ ✅ Passed
       ▼
┌──────────────────────────────────┐
│  PelamarController::show()        │
│  - Find/Create Pelamar record     │
│  - Load skills relationship       │
└──────┬───────────────────────────┘
       │ Returns data
       ▼
┌──────────────────────────────────┐
│  View: pelamar/show.blade.php     │
│  - Display 3-column layout        │
│  - Render cards & skills          │
│  - Handle interactions            │
└──────────────────────────────────┘
```

---

## 📦 Database Schema

```
┌─────────────────────────────────────┐
│         users (PK: id)              │
├─────────────────────────────────────┤
│ id, name, email, password, role_id  │
└─────────────────┬───────────────────┘
                  │ FK: id_user
                  ▼
┌─────────────────────────────────────┐
│    pelamars (PK: id_pelamar)        │
├─────────────────────────────────────┤
│ id_pelamar, id_user, nama_pelamar,  │
│ status_pekerjaan, no_telp, alamat,  │
│ jenis_kelamin, tgl_lahir            │
└──────────┬────────────────┬─────────┘
           │ 1:N            │ M:M
           ▼                ▼
    ┌────────────┐  ┌──────────────────┐
    │  resumes   │  │  pelamar_skill   │
    │ (resumes)  │  │ (pivot table)     │
    └────────────┘  ├──────────────────┤
                    │ id_pelamar (FK)  │
                    │ id_skill (FK)    │
                    │ level            │
                    │ years_experience │
                    └────────┬─────────┘
                             ▼
                        ┌────────────┐
                        │   skills   │
                        │ (master)   │
                        └────────────┘
```

---

## 🎨 UI Component Breakdown

### Card 1: Data Pribadi & Kontak
```html
┌─────────────────────────────────────────────┐
│ DATA PRIBADI & KONTAK                       │
├─────────────────────────────────────────────┤
│                                             │
│  [Avatar]  Nama Pelamar                     │
│            user@email.com                   │
│                                             │
│  Status Pekerjaan:        [Belum Diisi]     │
│  No. Telepon:             [+62...]          │
│  Alamat:                  [Jakarta]         │
│  Jenis Kelamin:           [Laki-laki]       │
│  Tanggal Lahir:           [01 Jan 2000]     │
│                                             │
│  [Edit Data Diri] ►                         │
│                                             │
└─────────────────────────────────────────────┘
```

### Card 2: Resume Summary
```html
┌─────────────────────────────────┐
│ RINGKASAN RESUME                │
├─────────────────────────────────┤
│                                 │
│ Total Resume Tersimpan:    [2]  │
│                                 │
│ Kelola semua CV dan resume      │
│ Anda untuk mempermudah proses   │
│ melamar.                        │
│                                 │
│ [Lihat Semua Resume] ►          │
│                                 │
└─────────────────────────────────┘
```

### Card 3: Account Settings
```html
┌─────────────────────────────────┐
│ PENGATURAN AKUN                 │
├─────────────────────────────────┤
│                                 │
│ [⚙ Ubah Password & Email]       │
│                                 │
│ [🗑 Hapus Akun Permanen]        │
│                                 │
└─────────────────────────────────┘
```

### Section: Skills & Keahlian
```html
┌──────────────────────────────────────────────────────┐
│ SKILL & KEAHLIAN                        [+ Tambah]   │
├──────────────────────────────────────────────────────┤
│                                                      │
│ ┌──────────────────────┐  ┌──────────────────────┐  │
│ │ JavaScript    [Adv]  │  │ React        [Adv]   │  │
│ │ 3 tahun              │  │ 2 tahun              │  │
│ │                      │  │                      │  │
│ │ [Edit]  [Hapus]      │  │ [Edit]  [Hapus]      │  │
│ └──────────────────────┘  └──────────────────────┘  │
│                                                      │
│ ┌──────────────────────┐  ┌──────────────────────┐  │
│ │ PHP          [Int]   │  │ Laravel      [Int]   │  │
│ │ 2 tahun              │  │ 1 tahun              │  │
│ │                      │  │                      │  │
│ │ [Edit]  [Hapus]      │  │ [Edit]  [Hapus]      │  │
│ └──────────────────────┘  └──────────────────────┘  │
│                                                      │
│ Kelola Semua Skill ►                                 │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## 🚀 How It Works - Step by Step

### 1️⃣ Access Profile
```
User clicks "Profil Saya" in navigation
    ↓
Request to GET /pelamar/data
    ↓
pelamar Middleware checks role
    ↓
✅ Is pelamar → Continue
❌ Not pelamar → Redirect to dashboard
```

### 2️⃣ Load Profile Data
```
PelamarController::show() runs:
    ├─ $userId = Auth::id()                    // Get user ID
    ├─ $pelamar = Pelamar::where(...)->first() // Find pelamar
    ├─ if (!$pelamar) create()                 // Auto-create if needed
    └─ $skills = $pelamar->skills             // Load skills
    ↓
Pass to view('pelamar.show', ['pelamar', 'skills'])
```

### 3️⃣ Render HTML
```
View renders 3 sections:
    ├─ Left Column (2/3):
    │  ├─ Data Pribadi Card with edit button
    │  └─ Linked to route('pelamar.edit')
    │
    ├─ Right Column (1/3):
    │  ├─ Resume Summary widget
    │  └─ Account Settings (password/delete)
    │
    └─ Full Width:
       ├─ Skills list with edit/delete
       └─ Link to full skills management
```

### 4️⃣ User Interactions

**Edit Profile:**
```
Click "Edit Data Diri"
    → GET /pelamar/data/edit
    → Show form with current values
    → User modifies data
    → POST to PUT /pelamar/data/update
    → Validation & save to database
    → Redirect back with success message
```

**Manage Skills:**
```
Click "Edit" on skill
    → GET /skills/{id}/edit
    → Show skill form
    → Modify level/experience
    → Save and redirect

Click "Hapus" on skill
    → DELETE request to /skills/{id}
    → Remove from pivot table
    → Refresh and redirect
```

**Delete Account:**
```
Click "Hapus Akun Permanen"
    → JavaScript confirmation popup
    → DELETE to /pelamar/data/delete
    → Database record deleted
    → User logged out
    → Redirect to home page
```

---

## 🔐 Security Measures

| Measure | Implementation | Status |
|---------|-----------------|--------|
| **Authentication** | pelamar middleware | ✅ Active |
| **Authorization** | Role-based access | ✅ Active |
| **CSRF Protection** | `@csrf` in forms | ✅ Active |
| **Validation** | Server-side validation | ✅ Active |
| **Passwords** | Hashed (bcrypt) | ✅ Active |
| **Cascading Deletes** | Foreign key constraints | ✅ Active |

---

## 💾 Database Operations

### Create (Auto on first visit)
```php
Pelamar::create([
    'id_user' => Auth::id(),
    'nama_pelamar' => 'Belum diisi',
    // ... other fields
]);
```

### Read (Display)
```php
$pelamar = Pelamar::where('id_user', Auth::id())->first();
$skills = $pelamar->skills()->get();
```

### Update (Save changes)
```php
$pelamar->update($request->validated());
```

### Delete (Remove account)
```php
$pelamar->delete();
Auth::logout();
```

---

## 📊 Key Statistics

| Metric | Value |
|--------|-------|
| Total Files Involved | 12+ |
| Database Tables | 5 (users, pelamars, skills, resumes, pelamar_skill) |
| Routes | 4 (CRUD operations) |
| View Components | 3 + 1 section |
| Relationships | 4 (1-to-1, 1-to-many x2, many-to-many) |
| Validation Rules | 6 |
| Middleware Checks | 2 |

---

## ⚠️ Common Issues & Solutions

### Issue: "Pelamar record not created"
**Solution:** Check middleware - might be redirecting before creation

### Issue: "Skills not displaying"
**Solution:** Verify `pelamar_skill` pivot table has records and load relationship

### Issue: "Edit form not saving"
**Solution:** Check validation rules - might be rejecting input format

### Issue: "Delete not working"
**Solution:** Ensure cascade delete is configured in foreign keys

---

## ✅ Testing Scenarios

1. **Happy Path:**
   - Login as pelamar → Access profile → View all data → ✅ Pass

2. **Data Modification:**
   - Edit profile info → Submit → Verify in DB → ✅ Pass

3. **Skill Management:**
   - Add skill → Edit skill → Delete skill → ✅ Pass

4. **Account Deletion:**
   - Delete account → Logout → Cannot login → ✅ Pass

5. **Authorization:**
   - Try access as non-pelamar → Redirect → ✅ Pass

---

## 🔗 Quick Links

- **Main Route:** `route('pelamar.profil')` → `/pelamar/data`
- **Edit Route:** `route('pelamar.edit')` → `/pelamar/data/edit`
- **Update Route:** `route('pelamar.update')` → `/pelamar/data/update` (PUT)
- **Delete Route:** `route('pelamar.destroy')` → `/pelamar/data/delete` (DELETE)
- **Skills Mgmt:** `route('skills.create')` → `/skills/create`

---

**Last Updated:** 9 December 2025  
**System Status:** ✅ Fully Operational  
**Version:** 1.0.0
