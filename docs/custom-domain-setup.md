# 🌐 Panduan Menyambungkan Custom Domain — adaylink

Dokumentasi ini menjelaskan langkah-langkah untuk menyambungkan domain custom Anda ke website adaylink.

---

## 📋 Prasyarat

1. **Paket Berlangganan** — Fitur Custom Domain tersedia di paket **Pro Agent** atau paket yang sudah diaktifkan fitur Custom Domain oleh admin.
2. **Domain Sendiri** — Anda harus sudah memiliki domain sendiri (misalnya dari Namecheap, GoDaddy, Cloudflare, Niagahoster, dll).
3. **Akses DNS Management** — Anda harus bisa mengatur DNS records di panel kontrol domain Anda.

---

## 🚀 Langkah-Langkah Menyambungkan Custom Domain

### Langkah 1: Masukkan Domain di Dashboard

1. Login ke dashboard adaylink Anda di `https://adaylink.com/dashboard/settings`
2. Scroll ke bagian **Custom Domain**
3. Masukkan domain Anda tanpa `http://` atau `https://`
   - ✅ Benar: `tour.bali-anda.com`
   - ✅ Benar: `www.bali-driver.com`
   - ❌ Salah: `https://tour.bali-anda.com`
   - ❌ Salah: `tour.bali-anda.com/`
4. Klik **Simpan Pengaturan**

### Langkah 2: Dapatkan Instruksi DNS

Setelah menyimpan, sistem akan menampilkan instruksi DNS yang berisi:

| Record | Type | Name | Value |
|--------|------|------|-------|
| CNAME | CNAME | `tour` (subdomain) atau `@` (root) | `adaylink.com` |
| TXT | TXT | `tour` (subdomain) atau `@` (root) | `adaylink-verify-xxxxxxxxxxxx` |

> ⚠️ **Penting**: Nilai TXT bersifat unik untuk setiap website. Jangan gunakan token dari website lain.

### Langkah 3: Tambahkan DNS Records

Login ke panel DNS domain Anda dan tambahkan record berikut:

#### Contoh untuk Subdomain (misal: `tour.bali-anda.com`)

| Type | Name/Host | Value/Target | TTL |
|------|-----------|-------------|-----|
| **CNAME** | `tour` | `adaylink.com` | Auto / 3600 |
| **TXT** | `tour` | `adaylink-verify-xxxxxxxxxxxx` | Auto / 3600 |

#### Contoh untuk Root Domain (misal: `bali-driver.com`)

| Type | Name/Host | Value/Target | TTL |
|------|-----------|-------------|-----|
| **CNAME** | `@` | `adaylink.com` | Auto / 3600 |
| **TXT** | `@` | `adaylink-verify-xxxxxxxxxxxx` | Auto / 3600 |

#### Contoh untuk www Subdomain (misal: `www.bali-driver.com`)

| Type | Name/Host | Value/Target | TTL |
|------|-----------|-------------|-----|
| **CNAME** | `www` | `adaylink.com` | Auto / 3600 |
| **TXT** | `www` | `adaylink-verify-xxxxxxxxxxxx` | Auto / 3600 |

### Langkah 4: Verifikasi Domain

1. Kembali ke dashboard adaylink → **Pengaturan Website**
2. Klik tombol **Verifikasi Domain**
3. Sistem akan memeriksa DNS records Anda

#### Jika Berhasil ✅
- Status berubah menjadi **Terverifikasi** (hijau)
- Domain Anda sekarang aktif dan bisa diakses oleh pengunjung
- Website tetap bisa diakses melalui subdomain adaylink (`namasubdomain.adaylink.com`)

#### Jika Gagal ❌
- Sistem akan menampilkan pesan error yang menjelaskan record mana yang belum ditemukan
- Pastikan DNS records sudah ditambahkan dengan benar
- Tunggu beberapa saat (DNS propagation bisa memakan waktu **5 menit hingga 48 jam**)
- Coba verifikasi lagi setelah beberapa saat

---

## 🔧 Panduan DNS untuk Provider Populer

### Cloudflare
1. Login ke dashboard Cloudflare
2. Pilih domain Anda
3. Klik **DNS** → **Records** → **Add Record**
4. Tambahkan **CNAME** record:
   - Name: `tour` (atau subdomain pilihan Anda)
   - Target: `adaylink.com`
   - Proxy status: **DNS only** (gray cloud, bukan orange)
5. Tambahkan **TXT** record:
   - Name: `tour` (sama dengan CNAME)
   - Content: token verifikasi dari adaylink

### Namecheap
1. Login ke Namecheap
2. Domain List → Manage → **Advanced DNS**
3. Klik **ADD NEW RECORD**
4. Tambahkan **CNAME Record**:
   - Host: `tour`
   - Value: `adaylink.com`
   - TTL: Automatic
5. Tambahkan **TXT Record**:
   - Host: `tour`
   - Value: token verifikasi dari adaylink
   - TTL: Automatic

### GoDaddy
1. Login ke GoDaddy
2. My Products → DNS → **Manage DNS**
3. Klik **Add New Record**
4. Tambahkan **CNAME**:
   - Name: `tour`
   - Value: `adaylink.com`
   - TTL: Default
5. Tambahkan **TXT**:
   - Name: `tour`
   - Value: token verifikasi dari adaylink
   - TTL: Default

### Niagahoster
1. Login ke Niagahoster
2. Kelola Domain → **DNS Management**
3. Klik **Tambah DNS Record**
4. Tambahkan **CNAME**:
   - Name: `tour`
   - Value: `adaylink.com`
5. Tambahkan **TXT**:
   - Name: `tour`
   - Value: token verifikasi dari adaylink

---

## ❓ FAQ (Pertanyaan Umum)

### Berapa lama DNS propagation?
Biasanya **5-30 menit**, tetapi bisa memakan waktu hingga **48 jam** tergantung provider DNS Anda.

### Apakah website lama (subdomain) tetap bisa diakses?
**Ya**, website Anda tetap bisa diakses melalui `subdomain.adaylink.com` meskipun custom domain sudah aktif.

### Bisa menggunakan root domain (tanpa www)?
**Ya**, Anda bisa menggunakan root domain seperti `bali-driver.com`. Namun, beberapa provider DNS tidak mendukung CNAME untuk root domain. Dalam kasus ini, gunakan subdomain seperti `www.bali-driver.com` atau `tour.bali-driver.com`.

### Bagaimana jika ingin mengganti domain?
1. Hapus domain lama melalui tombol hapus di dashboard
2. Masukkan domain baru
3. Simpan pengaturan
4. Ulangi proses verifikasi DNS untuk domain baru

### Apakah SSL/HTTPS tersedia?
Ya, pastikan domain Anda sudah menggunakan HTTPS. Jika menggunakan Cloudflare, aktifkan **Flexible SSL** di pengaturan SSL.

### Kenapa verifikasi gagal?
Kemungkinan penyebab:
1. **DNS belum propagate** — Tunggu beberapa menit/jam
2. **Record salah** — Periksa kembali Name dan Value di DNS records
3. **Proxy aktif di Cloudflare** — Matikan proxy (gray cloud) untuk CNAME record
4. **Token salah** — Pastikan TXT value persis sama dengan yang ditampilkan di dashboard

---

## 🏗️ Arsitektur Teknis (untuk Developer)

### Alur Routing Custom Domain

```
Request masuk (host: tour.bali-anda.com)
    │
    ▼
ResolveCustomDomain Middleware
    ├── Host == platform domain? → Pass through (normal routes)
    ├── Host == custom domain (verified)?
    │   ├── Load website + relasi
    │   ├── Validasi: is_active, user not blocked, subscription active
    │   ├── Set $request->attributes->set('website', $website)
    │   ├── Set $request->attributes->set('is_custom_domain', true)
    │   └── Continue to controller
    └── Host not found → Pass through (404)
```

### Database Schema

```sql
-- websites table
custom_domain          VARCHAR(255) NULL        -- Domain custom (misal: tour.bali.com)
custom_domain_dns_token VARCHAR(255) NULL       -- Token verifikasi DNS
custom_domain_verified_at TIMESTAMP NULL         -- Waktu verifikasi berhasil
```

### File Terkait

| File | Fungsi |
|------|--------|
| `app/Http/Middleware/ResolveCustomDomain.php` | Middleware resolver custom domain |
| `app/Livewire/Driver/WebsiteSettings.php` | UI logic: save, verify, remove domain |
| `app/Models/Website.php` | Model dengan field custom_domain |
| `app/Models/SubscriptionPlan.php` | Feature flag `custom_domain` |
| `routes/web.php` | Custom domain routes |
| `bootstrap/app.php` | Middleware registration |

---

*Dokumentasi terakhir diperbarui: Mei 2026*
