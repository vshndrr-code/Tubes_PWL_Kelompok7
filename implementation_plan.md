# Menambahkan Role Auditor (Financial & Content Auditor)

Rencana kerja ini dibagi menjadi **5 tahap pengerjaan** secara bertahap untuk memudahkan pelacakan, pengujian, dan integrasi yang aman. Kami juga akan menyertakan `AuditorSeeder` untuk mempermudah login selama testing.

---

## Akun Pengujian Auditor (Seeder)
Akun berikut akan dibuat secara otomatis melalui seeder pada Tahap 1:
- **Email:** `auditor@moma.com`
- **Password:** `password`
- **Role:** `auditor`

---

## 5 Tahap Pengerjaan (Phased Execution Plan)

### Tahap 1: Database Migration & Seeder Auditor
Fokus pada penyiapan struktur database untuk role baru dan pembuatan akun auditor default.

#### [MODIFY] [User.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/app/Models/User.php)
- Menambahkan `'role'` ke attribute `#[Fillable([...])]`.
- Menambahkan helper method `isAuditor()` untuk mempermudah pengecekan hak akses.

#### [NEW] [xxxx_add_role_to_users_table.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/database/migrations/)
- Membuat migrasi terpisah untuk menambahkan kolom `role` enum `['user', 'auditor']` default `user` setelah kolom `password` pada tabel `users`.

#### [NEW] [AuditorSeeder.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/database/seeders/AuditorSeeder.php)
- Membuat seeder untuk memasukkan akun auditor default `auditor@moma.com`.

---

### Tahap 2: Middleware & Routing Hak Akses
Membuat sistem pengaman (gatekeeper) agar rute auditor tidak dapat diakses oleh user biasa (HTTP 403).

#### [NEW] [EnsureUserIsAuditor.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/app/Http/Middleware/EnsureUserIsAuditor.php)
- Middleware yang memeriksa apakah `auth()->user()->isAuditor()` benar. Jika tidak, kembalikan respon `abort(403, 'Access Denied')`.

#### [MODIFY] [app.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/bootstrap/app.php)
- Daftarkan middleware auditor dengan alias `auditor` di method `withMiddleware()`.

#### [MODIFY] [web.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/routes/web.php)
- Daftarkan grup rute `/auditor/*` yang dilindungi oleh middleware `['auth', 'verified', 'auditor']`:
  - `GET /auditor/dashboard` (dashboard)
  - `POST /auditor/categories` (pembuatan kategori global)
  - `GET /auditor/tags` (halaman moderasi tag)
  - `DELETE /auditor/tags/{tag}` (proses hapus tag)

---

### Tahap 3: Logika Backend & Kontroler Auditor
Membuat logika pengolahan data untuk menghitung statistik global, menyimpan kategori baru, dan menghapus tag.

#### [NEW] [AuditorController.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/app/Http/Controllers/AuditorController.php)
- **`dashboard()`**: Mengambil data agregat global:
  1. `User::where('role', 'user')->count()` (Masyarakat)
  2. `Transaction::count()` (Total Transaksi)
  3. `SavingsGoals::sum('current_amount')` (Global Savings)
- **`storeCategory()`**: Validasi data input form kategori global baru (`name`, `type`, `icon`, `color`) dan menyimpannya dengan `user_id = null`.
- **`tags()`**: Mengambil daftar seluruh tag beserta pembuatnya (`Tag::with('user')->get()`).
- **`destroyTag()`**: Melakukan verifikasi peran lalu menghapus tag terpilih.

---

### Tahap 4: Antarmuka Auditor (Blade Views)
Membuat halaman visual khusus untuk panel kontrol auditor.

#### [MODIFY] [app.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/layouts/app.blade.php)
- Menampilkan link navigasi ke halaman dashboard auditor di sidebar secara kondisional (`@if(auth()->user()->isAuditor())`).

#### [NEW] [dashboard.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/auditor/dashboard.blade.php)
- Menampilkan 3 kartu statistik makro di bagian atas.
- Menyediakan formulir input kategori SDGs baru (dropdown tipe, dropdown ikon FontAwesome siap pakai, dan pemilih warna HEX HTML5).

#### [NEW] [tags.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/auditor/tags.blade.php)
- Menampilkan seluruh tag dalam tabel modern lengkap dengan tombol hapus merah dan konfirmasi popup JS.

---

### Tahap 5: Integrasi & Rendering Dinamis Kategori (User View)
Menghubungkan input dinamis dari auditor agar teraplikasi dengan benar di seluruh halaman pengguna.

#### [MODIFY] [app.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/layouts/app.blade.php)
- Menambahkan FontAwesome CDN stylesheet di `<head>` agar ikon kategori dapat dirender dari database.

#### [MODIFY] [index.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/categories/index.blade.php)
- Mengubah warna latar dan ikon statis menjadi dinamis:
  - Background: `style="background-color: {{ $category->color ?? '#6b7280' }}"`
  - Ikon: `<i class="fa-solid fa-{{ $category->icon ?? 'tag' }} text-white text-2xl"></i>`

#### [MODIFY] [category-selector.blade.php](file:///d:/coolyeah/semester%202/PWL/MOMA/MOMA/resources/views/components/category-selector.blade.php)
- Mengubah mapping emoji statis menjadi render ikon FontAwesome dinamis:
  - `<i :class="'fa-solid fa-' + (category.icon || 'tag') + ' text-white text-xl'"></i>`

---

## Verification Plan

### Automated Tests
- Menjalankan migrasi: `php artisan migrate`
- Menjalankan seeder auditor: `php artisan db:seed --class=AuditorSeeder`

### Manual Verification
1. Coba login menggunakan akun `auditor@moma.com` dengan password `password`.
2. Buka rute `/auditor/dashboard` dengan akun user biasa dan pastikan terblokir (403).
3. Buka dengan akun auditor dan pastikan statistik dan form berfungsi.
4. Buat kategori global baru, pastikan langsung bisa dipilih dan tampil dinamis dengan ikon/warna FontAwesome di halaman user biasa.
5. Lakukan moderasi penghapusan tag dan pastikan transaksi yang menggunakan tag tersebut tidak rusak.
