# 📐 Template Specification — adaylink

Dokumentasi spesifikasi untuk membuat template baru pada platform adaylink.

---

## 📁 Struktur File

Setiap template terdiri dari **3 file Blade** yang diletakkan di folder:

```
resources/views/templates/{folder_name}/
├── index.blade.php    → Homepage (landing page)
├── tour.blade.php     → Detail paket tour
└── page.blade.php     → Halaman statis (Tentang Kami, dll.)
```

### Konvensi Nama Folder
- Gunakan **huruf kecil**, tanpa spasi
- Contoh: `clean`, `adventure`, `minimalis`, `modern`, `card`, `quick`, `luxury`

---

## 🗄️ Registrasi Template

Daftarkan template di **`database/seeders/TemplateSeeder.php`**:

```php
SubscriptionPlan::updateOrCreate(
    ['name' => 'NamaTemplate'],
    [
        'folder_name' => 'namatemplate',      // nama folder, huruf kecil
        'tier' => 'Basic',                     // 'Basic' atau 'Premium'
        'thumbnail' => 'templates/nama.png',   // preview image (opsional)
        'is_active' => true,
    ]
);
```

Jalankan: `php artisan db:seed --class=TemplateSeeder`

---

## 📦 Variabel yang Tersedia

Semua variabel ini dikirim dari `PublicWebsiteController` dan bisa langsung digunakan di template.

### Website & Settings

| Variabel | Tipe | Deskripsi |
|----------|------|-----------|
| `$website` | `Website` | Model website (subdomain driver) |
| `$website->site_name` | `string` | Nama bisnis/website |
| `$website->subdomain` | `string` | Subdomain (contoh: `adi-trans`) |
| `$website->contact_whatsapp` | `string` | Nomor WhatsApp (format: `6281234567890`) |
| `$website->default_locale` | `string` | Bahasa default: `'id'` atau `'en'` |
| `$settings` | `WebsiteSetting` | Pengaturan template |
| `$settings->site_title` | `string` | Judul website untuk SEO |
| `$settings->primary_color` | `string` | Warna utama (hex: `#2563EB`) |
| `$settings->secondary_color` | `string` | Warna sekunder (hex: `#0F172A`) |
| `$settings->font_heading` | `string` | Font judul (contoh: `Space Grotesk`) |
| `$settings->font_body` | `string` | Font body (contoh: `Inter`) |
| `$settings->hero_title` | `string` | Judul hero section |
| `$settings->hero_subtitle` | `string` | Subtitle hero section |
| `$settings->hero_image_url` | `string` | URL gambar hero |
| `$settings->seo_meta_title` | `string` | Meta title SEO |
| `$settings->seo_meta_description` | `string` | Meta description SEO |
| `$settings->gallery_images` | `array` | Array URL gambar galeri |
| `$settings->translations` | `array` | JSON terjemahan konten settings |

### Konten

| Variabel | Tipe | Deskripsi | Tersedia di |
|----------|------|-----------|-------------|
| `$tourPackages` | `Collection<TourPackage>` | Semua paket tour | `index` |
| `$tour` | `TourPackage` | Detail tour yang sedang dilihat | `tour` |
| `$relatedTours` | `Collection<TourPackage>` | Tour terkait (maks 3) | `tour` |
| `$vehicles` | `Collection<Vehicle>` | Semua armada | `index` |
| `$reviews` | `Collection<Review>` | Review yang di-approve | `index` |
| `$galleryImages` | `Collection` | Koleksi gambar galeri | `index` |
| `$pages` | `Collection<Page>` | Halaman yang dipublikasi | `index`, `tour`, `page` |
| `$page` | `Page` | Detail halaman yang sedang dilihat | `page` |

### Fitur & Lokalisasi

| Variabel | Tipe | Deskripsi |
|----------|------|-----------|
| `$features` | `array` | Feature flags dari subscription plan |
| `$features['floating_whatsapp']` | `bool` | Tampilkan tombol WhatsApp melayang |
| `$features['social_share']` | `bool` | Tampilkan tombol share social media |
| `$features['gallery_lightbox']` | `bool` | Aktifkan galeri dengan lightbox |
| `$features['reviews']` | `bool` | Tampilkan section review |
| `$features['multilanguage']` | `bool` | Aktifkan language switcher |
| `$locale` | `string` | Locale pengunjung saat ini (`'id'` / `'en'`) |
| `$altLocale` | `string` | Locale alternatif |
| `$subdomain` | `string` | Subdomain dari URL |
| `$reviewSchema` | `?array` | Schema.org JSON-LD untuk review |

---

## 🏗️ Model Properties

### TourPackage

```php
$tour->title              // string - Judul paket tour
$tour->slug               // string - URL slug
$tour->description        // string - Deskripsi lengkap (HTML)
$tour->price_start_from   // decimal - Harga mulai dari
$tour->thumbnail_url      // string - URL gambar thumbnail
$tour->duration_text       // string - Durasi (contoh: "2 Hari 1 Malam")
$tour->itinerary          // array - Daftar itinerari
$tour->includes           // array - Daftar yang termasuk
$tour->excludes           // array - Daftar yang tidak termasuk
$tour->notes              // string - Catatan tambahan
$tour->is_featured        // bool - Apakah tour unggulan
$tour->images             // Collection<ProductImage> - Gambar tambahan
$tour->translations       // array - JSON terjemahan
```

### Vehicle

```php
$vehicle->model_name      // string - Nama model kendaraan
$vehicle->capacity_people // int - Kapasitas penumpang
$vehicle->price_per_day   // decimal - Harga per hari
$vehicle->image_url       // string - URL gambar utama
$vehicle->images          // Collection<ProductImage> - Gambar tambahan
```

### Page

```php
$page->title              // string - Judul halaman
$page->slug               // string - URL slug
$page->content            // string - Konten halaman (HTML)
$page->is_published       // bool - Status publikasi
$page->sort_order         // int - Urutan tampil
$page->translations       // array - JSON terjemahan
```

### Review

```php
$review->reviewer_name    // string - Nama reviewer
$review->rating           // int - Rating (1-5)
$review->comment          // string - Komentar review
$review->created_at       // Carbon - Tanggal review
```

---

## 🔗 URL Patterns

```blade
{{-- Homepage --}}
{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/' }}

{{-- Tour detail --}}
{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug }}

{{-- Page --}}
{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/page/' . $page->slug : '/page/' . $page->slug }}

{{-- WhatsApp link --}}
https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}?text={{ urlencode('Pesan...') }}
```

---

## 🌐 Lokalisasi (i18n)

### HTML Lang Attribute
```blade
<html lang="{{ $locale ?? 'id' }}">
```

### Teks Statis — Gunakan `__()`
Semua teks statis (label, heading, tombol) **WAJIB** menggunakan helper `__()`:

```blade
{{-- Salah ❌ --}}
<h2>Paket Tour Kami</h2>
<a>Pesan Sekarang</a>

{{-- Benar ✅ --}}
<h2>{{ __('messages.tours') }}</h2>
<a>{{ __('messages.book_now') }}</a>
```

### Daftar Key yang Tersedia

Lihat file: `resources/lang/en/messages.php` dan `resources/lang/id/messages.php`

| Key | English | Indonesia |
|-----|---------|-----------|
| `messages.home` | Home | Beranda |
| `messages.tours` | Tour Packages | Paket Tour |
| `messages.vehicles` | Our Fleet | Armada Kami |
| `messages.gallery` | Gallery | Galeri |
| `messages.reviews` | Reviews | Ulasan |
| `messages.contact` | Contact Us | Hubungi Kami |
| `messages.book_now` | Book Now | Pesan Sekarang |
| `messages.view_details` | View Details | Lihat Detail |
| `messages.read_more` | Read More | Selengkapnya |
| `messages.starting_from` | Starting from | Mulai dari |
| `messages.includes` | Includes | Termasuk |
| `messages.excludes` | Excludes | Tidak Termasuk |
| `messages.itinerary` | Itinerary | Itinerari |
| `messages.notes` | Notes | Catatan |
| `messages.duration` | Duration | Durasi |
| `messages.price` | Price | Harga |
| `messages.people` | people | orang |
| `messages.per_day` | per day | per hari |
| `messages.our_fleet` | Our Fleet | Armada Kami |
| `messages.gallery_title` | Our Gallery | Galeri Kami |
| `messages.customer_reviews` | Customer Reviews | Ulasan Pelanggan |
| `messages.related_tours` | Related Tours | Tour Terkait |
| `messages.contact_whatsapp` | Contact via WhatsApp | Hubungi via WhatsApp |
| `messages.book_via_whatsapp` | Book via WhatsApp | Pesan via WhatsApp |
| `messages.your_name` | Your Name | Nama Anda |
| `messages.all_rights_reserved` | All Rights Reserved | Hak Dilindungi |
| `messages.powered_by` | Powered by | Didukung oleh |

### Menambah Key Baru
Jika perlu key baru, tambahkan di **kedua** file:
- `resources/lang/en/messages.php`
- `resources/lang/id/messages.php`

### Language Switcher Component
Tambahkan di area navbar:

```blade
<x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
```

Component ini otomatis hidden jika plan tidak punya fitur `multilanguage`.

---

## 🎨 Styling Guidelines

### CSS Custom Properties (WAJIB)
Gunakan CSS variables agar warna bisa dikustomisasi dari dashboard driver:

```css
:root {
    --bg: #FFFFFF;
    --fg: {{ $secondaryColor }};
    --muted: #64748B;
    --accent: {{ $primaryColor }};
    --accent-soft: #EFF6FF;
    --card: #F8FAFC;
    --border: #E2E8F0;
    --surface: #F1F5F9;
    --font-heading: '{{ $fontHeading }}', sans-serif;
    --font-body: '{{ $fontBody }}', sans-serif;
}
```

### Setup PHP di Awal Template
```blade
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#2563EB';
    $secondaryColor = $settings->secondary_color ?? '#0F172A';
    $fontHeading = $settings->font_heading ?? 'Space Grotesk';
    $fontBody = $settings->font_body ?? 'Inter';
@endphp
```

### Font Loading
```blade
<link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontHeading) }}:wght@400;500;600;700&family={{ urlencode($fontBody) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
```

### Icon Library
```blade
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
```

---

## ✅ Checklist Template

Gunakan checklist ini saat membuat template baru:

### Struktur
- [ ] Folder `resources/views/templates/{nama}/` dibuat
- [ ] `index.blade.php` — Homepage dengan hero, tour list, vehicles, gallery, reviews
- [ ] `tour.blade.php` — Detail tour dengan itinerary, includes/excludes, booking
- [ ] `page.blade.php` — Halaman statis dengan header dan konten
- [ ] Template terdaftar di `TemplateSeeder.php`

### Lokalisasi
- [ ] `<html lang="{{ $locale ?? 'id' }}">`
- [ ] Semua teks statis menggunakan `{{ __('messages.key') }}`
- [ ] `<x-language-switcher>` component di navbar
- [ ] Tidak ada teks bahasa yang di-hardcode

### Styling
- [ ] CSS variables menggunakan `$primaryColor`, `$secondaryColor`, dll.
- [ ] Font menggunakan `$fontHeading` dan `$fontBody`
- [ ] Responsive (mobile-first atau media queries)
- [ ] Hamburger menu untuk mobile

### Fitur Kondisional
- [ ] Section gallery hanya tampil jika `$features['gallery_lightbox']` true
- [ ] Section reviews hanya tampil jika `$features['reviews']` true
- [ ] Floating WhatsApp hanya tampil jika `$features['floating_whatsapp']` true
- [ ] Social share buttons hanya tampil jika `$features['social_share']` true
- [ ] Language switcher hanya tampil jika `$features['multilanguage']` true

### SEO
- [ ] `<title>` menggunakan `$settings->site_title` atau `$website->site_name`
- [ ] `<meta name="description">` menggunakan `$settings->seo_meta_description`
- [ ] Schema.org JSON-LD untuk review (jika fitur aktif)

### Navigation
- [ ] Menu navigasi dengan link ke homepage
- [ ] Link ke halaman statis dari `$pages` collection
- [ ] Breadcrumb di halaman tour dan page
- [ ] Mobile drawer/hamburger menu

### Konten
- [ ] Tour cards menampilkan: thumbnail, title, price, duration
- [ ] Vehicle cards menampilkan: image, model_name, capacity, price
- [ ] WhatsApp booking link yang berfungsi
- [ ] Gallery dengan lightbox effect (jika fitur aktif)

---

## 📋 Template Tier

| Tier | Deskripsi | Akses Plan |
|------|-----------|------------|
| `Basic` | Template standar | Semua plan |
| `Premium` | Template premium | Plan dengan `allowed_template_tier` = `Premium` atau `All` |

---

## 🔧 Contoh Template Minimum

### `index.blade.php` (Minimal)

```blade
@php
    $homeUrl = isset($subdomain) && $subdomain ? '/s/' . $subdomain : '/';
    $primaryColor = $settings->primary_color ?? '#2563EB';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale ?? 'id' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_title ?? $website->site_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        :root { --accent: {{ $primaryColor }}; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ $homeUrl }}">{{ $website->site_name }}</a>
        <x-language-switcher :locale="$locale ?? 'id'" :alt-locale="$altLocale ?? 'en'" :subdomain="$subdomain ?? ''" :features="$features" />
    </nav>

    <h1>{{ $settings->hero_title ?? $website->site_name }}</h1>
    <p>{{ $settings->hero_subtitle }}</p>

    <section id="tours">
        <h2>{{ __('messages.tours') }}</h2>
        @foreach($tourPackages as $tour)
            <div>
                <h3>{{ $tour->title }}</h3>
                <p>{{ __('messages.starting_from') }}: Rp {{ number_format($tour->price_start_from, 0, ',', '.') }}</p>
                <a href="{{ isset($subdomain) && $subdomain ? '/s/' . $subdomain . '/tour/' . $tour->slug : '/tour/' . $tour->slug }}">{{ __('messages.view_details') }}</a>
            </div>
        @endforeach
    </section>

    @if($features['floating_whatsapp'] && $website->contact_whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $website->contact_whatsapp) }}" 
           style="position:fixed;bottom:24px;right:24px;">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
</body>
</html>
```
