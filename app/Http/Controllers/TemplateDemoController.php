<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\TourPackage;
use App\Models\Vehicle;
use App\Models\Website;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class TemplateDemoController extends Controller
{
    /**
     * Show the demo index page for a given template.
     */
    public function show(Request $request, string $template)
    {
        if (! View::exists("templates.{$template}.index")) {
            abort(404, 'Template not found.');
        }

        $locale = $request->query('lang', 'id');
        if (! in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        $data = $this->getDemoData($template, $locale);

        return view("templates.{$template}.index", array_merge($data, [
            'locale' => $locale,
            'altLocale' => $altLocale,
        ]));
    }

    /**
     * Show the demo tour detail page for a given template.
     */
    public function showTour(Request $request, string $template, string $slug)
    {
        if (! View::exists("templates.{$template}.tour")) {
            abort(404, 'Template not found.');
        }

        $locale = $request->query('lang', 'id');
        if (! in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        $data = $this->getDemoData($template, $locale);
        $tour = $this->findDemoTour($slug, $locale);

        if (! $tour) {
            abort(404, 'Demo tour not found.');
        }

        $relatedTours = $this->getDemoTours($locale)->filter(fn ($t) => $t->slug !== $slug)->take(3);

        return view("templates.{$template}.tour", array_merge($data, [
            'tour' => $tour,
            'relatedTours' => $relatedTours,
            'locale' => $locale,
            'altLocale' => $altLocale,
        ]));
    }

    /**
     * Show the demo page detail for a given template.
     */
    public function showPage(Request $request, string $template, string $slug)
    {
        if (! View::exists("templates.{$template}.page")) {
            abort(404, 'Template not found.');
        }

        $locale = $request->query('lang', 'id');
        if (! in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        $data = $this->getDemoData($template, $locale);
        $page = $this->findDemoPage($slug, $locale);

        if (! $page) {
            abort(404, 'Demo page not found.');
        }

        return view("templates.{$template}.page", array_merge($data, [
            'page' => $page,
            'locale' => $locale,
            'altLocale' => $altLocale,
        ]));
    }

    /**
     * Build all demo data arrays.
     */
    private function getDemoData(string $template, string $locale): array
    {
        $website = $this->getDemoWebsite($locale);
        $settings = $this->getDemoSettings($template);
        $tourPackages = $this->getDemoTours($locale);
        $vehicles = $this->getDemoVehicles($locale);
        $pages = $this->getDemoPages($locale);
        $reviews = $this->getDemoReviews();
        $galleryImages = $this->getDemoGalleryImages($locale);

        $features = [
            'floating_whatsapp' => true,
            'social_share' => true,
            'gallery_lightbox' => true,
            'reviews' => true,
            'multilanguage' => true,
        ];

        $reviewSchema = $this->getDemoReviewSchema($locale);

        return [
            'website' => $website,
            'settings' => $settings,
            'vehicles' => $vehicles,
            'tourPackages' => $tourPackages,
            'features' => $features,
            'reviews' => $reviews,
            'reviewSchema' => $reviewSchema,
            'galleryImages' => $galleryImages,
            'pages' => $pages,
            'subdomain' => 'demo',
        ];
    }

    private function getDemoWebsite(string $locale): Website
    {
        $siteName = $locale === 'en' ? 'Bali Paradise Tours' : 'Bali Paradise Tours';
        $website = new Website([
            'site_name' => $siteName,
            'subdomain' => 'demo',
            'logo_url' => null,
            'contact_whatsapp' => '8123456789',
            'default_locale' => $locale,
            'is_active' => true,
        ]);
        // Prevent DB queries for relationships
        $website->setRelation('user', new \App\Models\User());
        $website->user->setRelation('plan', new \App\Models\SubscriptionPlan());

        return $website;
    }

    private function getDemoSettings(string $template): WebsiteSetting
    {
        return new WebsiteSetting([
            'template_id' => null,
            'site_title' => 'Bali Paradise Tours',
            'primary_color' => '#40ac98',
            'secondary_color' => '#1a1a2e',
            'font_family' => 'Inter',
            'font_heading' => 'Playfair Display',
            'font_body' => 'Inter',
            'hero_title' => 'Discover Bali\'s Hidden Paradise',
            'hero_subtitle' => 'Experience the beauty of Bali with our expert local drivers and curated tour packages. Your unforgettable journey starts here.',
            'hero_image_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1920&h=800&fit=crop',
            'seo_meta_title' => 'Bali Paradise Tours - Your Gateway to Bali',
            'seo_meta_description' => 'Explore Bali with our professional tour drivers. Customizable tour packages, comfortable vehicles, and unforgettable experiences.',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1573790387438-4da905039392?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?w=600&h=400&fit=crop',
            ],
        ]);
    }

    private function getDemoTours(string $locale): \Illuminate\Support\Collection
    {
        $tours = [
            [
                'title' => $locale === 'en' ? 'Ubud Rice Terrace & Waterfall Tour' : 'Tour Sawah Teras Ubud & Air Terjun',
                'slug' => 'ubud-rice-terrace-tour',
                'short_description' => $locale === 'en'
                    ? 'Explore the stunning Tegalalang Rice Terrace, visit Tegenungan Waterfall, and experience Balinese culture in Ubud.'
                    : 'Jelajahi keindahan Teras Tegalalang, kunjungi Air Terjun Tegenungan, dan rasakan budaya Bali di Ubud.',
                'description' => $locale === 'en'
                    ? '<p>This full-day tour takes you through the most iconic spots in Ubud and surrounding areas. Start your journey at the breathtaking Tegalalang Rice Terrace, a UNESCO World Heritage candidate. Then visit the magnificent Tegenungan Waterfall where you can take a refreshing dip. Continue to the Sacred Monkey Forest and end your day watching the sunset at Tanah Lot Temple.</p><p>Our experienced local driver will share fascinating stories about Balinese culture and traditions throughout the journey.</p>'
                    : '<p>Tour sehari penuh ini membawa Anda ke tempat-tempat paling ikonik di Ubud dan sekitarnya. Mulai perjalanan Anda di Teras Tegalalang yang menakjubkan, kandidat Warisan Dunia UNESCO. Kemudian kunjungi Air Terjun Tegenungan yang megah di mana Anda bisa berenang. Lanjutkan ke Sacred Monkey Forest dan akhiri hari Anda menikmati sunset di Pura Tanah Lot.</p><p>Driver lokal berpengalaman kami akan berbagi cerita menarik tentang budaya dan tradisi Bali sepanjang perjalanan.</p>',
                'price' => 450000,
                'duration' => $locale === 'en' ? '8-10 hours' : '8-10 jam',
                'location' => 'Ubud, Bali',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&h=400&fit=crop',
                'itinerary' => [
                    ['day' => 1, 'title' => $locale === 'en' ? 'Full Day Tour' : 'Tour Sehari Penuh', 'description' => $locale === 'en' ? 'Visit Tegalalang Rice Terrace, Tegenungan Waterfall, Monkey Forest, and Tanah Lot Temple' : 'Kunjungi Teras Tegalalang, Air Terjun Tegenungan, Monkey Forest, dan Pura Tanah Lot'],
                ],
                'includes' => $locale === 'en'
                    ? ['Air-conditioned vehicle', 'English-speaking driver', 'Mineral water', 'Parking fees']
                    : ['Kendaraan AC', 'Driver berbahasa Inggris', 'Air mineral', 'Biaya parkir'],
                'excludes' => $locale === 'en'
                    ? ['Entrance tickets', 'Meals', 'Personal expenses']
                    : ['Tiket masuk', 'Makan', 'Pengeluaran pribadi'],
                'notes' => $locale === 'en'
                    ? ['Wear comfortable shoes', 'Bring sunscreen and hat', 'Camera recommended']
                    : ['Kenakan sepatu yang nyaman', 'Bawa sunscreen dan topi', 'Kamera direkomendasikan'],
            ],
            [
                'title' => $locale === 'en' ? 'Nusa Penida Day Trip' : 'Perjalanan Nusa Penida Sehari',
                'slug' => 'nusa-penida-day-trip',
                'short_description' => $locale === 'en'
                    ? 'Discover the breathtaking Kelingking Beach, Angel\'s Billabong, and Crystal Bay in Nusa Penida.'
                    : 'Temukan pantai Kelingking yang menakjubkan, Angel\'s Billabong, dan Crystal Bay di Nusa Penida.',
                'description' => $locale === 'en'
                    ? '<p>Nusa Penida is a hidden gem just a short boat ride from Bali. This tour takes you to the most spectacular viewpoints and beaches on the island. Visit the iconic Kelingking Beach with its T-Rex shaped cliff, swim in the natural infinity pool at Angel\'s Billabong, and snorkel with manta rays at Manta Point.</p>'
                    : '<p>Nusa Penida adalah permata tersembunyi yang hanya berjarak perjalanan kapal singkat dari Bali. Tour ini membawa Anda ke pemandangan dan pantai paling spektakuler di pulau ini. Kunjungi Kelingking Beach yang ikonik dengan tebing berbentuk T-Rex, berenang di kolam infinity alami Angel\'s Billabong, dan snorkeling dengan pari manta di Manta Point.</p>',
                'price' => 650000,
                'duration' => $locale === 'en' ? '10-12 hours' : '10-12 jam',
                'location' => 'Nusa Penida',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600&h=400&fit=crop',
                'itinerary' => [
                    ['day' => 1, 'title' => $locale === 'en' ? 'Full Day Adventure' : 'Petualangan Sehari Penuh', 'description' => $locale === 'en' ? 'Kelingking Beach, Angel\'s Billabong, Broken Beach, Crystal Bay' : 'Kelingking Beach, Angel\'s Billabong, Broken Beach, Crystal Bay'],
                ],
                'includes' => $locale === 'en'
                    ? ['Hotel pickup & drop-off', 'Speedboat tickets', 'AC vehicle in Penida', 'Lunch', 'Snorkeling gear']
                    : ['Antar-jemput hotel', 'Tiket speedboat', 'Kendaraan AC di Penida', 'Makan siang', 'Alat snorkeling'],
                'excludes' => $locale === 'en'
                    ? ['Personal expenses', 'Underwater camera']
                    : ['Pengeluaran pribadi', 'Kamera underwater'],
                'notes' => $locale === 'en'
                    ? ['Not recommended for pregnant women', 'Bring motion sickness medicine', 'Wear swimwear']
                    : ['Tidak disarankan untuk ibu hamil', 'Bawa obat mabuk laut', 'Kenakan pakaian renang'],
            ],
            [
                'title' => $locale === 'en' ? 'Mount Batur Sunrise Trekking' : 'Trekking Sunrise Gunung Batur',
                'slug' => 'mount-batur-sunrise-trekking',
                'short_description' => $locale === 'en'
                    ? 'Witness an unforgettable sunrise from the summit of Mount Batur, an active volcano in Bali.'
                    : 'Saksikan sunrise yang tak terlupakan dari puncak Gunung Batur, gunung berapi aktif di Bali.',
                'description' => $locale === 'en'
                    ? '<p>Start your adventure at 2 AM and trek through the darkness to reach the summit of Mount Batur (1,717m) just in time for a spectacular sunrise. Watch as the sun illuminates Mount Agung and the surrounding caldera. Enjoy a simple breakfast cooked by volcanic steam while taking in the breathtaking views.</p>'
                    : '<p>Mulai petualangan Anda jam 2 pagi dan trek melewati kegelapan untuk mencapai puncak Gunung Batur (1.717m) tepat waktu untuk sunrise yang spektakuler. Saksikan matahari menyinari Gunung Agung dan kaldera sekitarnya. Nikmati sarapan sederhana yang dimasak dengan uap vulkanik sambil menikmati pemandangan yang menakjubkan.</p>',
                'price' => 500000,
                'duration' => $locale === 'en' ? '6-8 hours' : '6-8 jam',
                'location' => 'Kintamani, Bali',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=600&h=400&fit=crop',
                'itinerary' => [
                    ['day' => 1, 'title' => $locale === 'en' ? 'Sunrise Trek' : 'Trek Sunrise', 'description' => $locale === 'en' ? '2 AM pickup, 4 AM summit, sunrise at 6 AM, breakfast, descend, optional hot springs' : 'Jam 2 pickup, jam 4 puncak, sunrise jam 6, sarapan, turun, opsional pemandian air panas'],
                ],
                'includes' => $locale === 'en'
                    ? ['Professional trekking guide', 'Flashlight', 'Breakfast on volcano', 'Hotel pickup & drop-off']
                    : ['Guide trekking profesional', 'Senter', 'Sarapan di gunung', 'Antar-jemput hotel'],
                'excludes' => $locale === 'en'
                    ? ['Hot springs entrance (optional)', 'Personal expenses']
                    : ['Tiket masuk pemandian air panas (opsional)', 'Pengeluaran pribadi'],
                'notes' => $locale === 'en'
                    ? ['Good fitness level required', 'Bring warm jacket', 'Wear hiking shoes']
                    : ['Diperlukan kebugaran yang baik', 'Bawa jaket hangat', 'Kenakan sepatu hiking'],
            ],
            [
                'title' => $locale === 'en' ? 'Uluwatu Temple & Kecak Dance' : 'Pura Uluwatu & Tari Kecak',
                'slug' => 'uluwatu-kecak-dance',
                'short_description' => $locale === 'en'
                    ? 'Visit the stunning Uluwatu Temple perched on a cliff and watch the mesmerizing Kecak Fire Dance at sunset.'
                    : 'Kunjungi Pura Uluwatu yang menakjubkan di tepi tebing dan saksikan Tari Kecak Api yang memukau saat sunset.',
                'description' => $locale === 'en'
                    ? '<p>Experience the magical combination of Bali\'s most dramatic temple location and its most iconic cultural performance. Perched on a 70-meter cliff overlooking the Indian Ocean, Uluwatu Temple offers breathtaking views. As the sun sets, watch the hypnotic Kecak Fire Dance performed by dozens of men chanting in unison.</p>'
                    : '<p>Rasakan kombinasi magis lokasi pura paling dramatis Bali dan pertunjukan budaya paling ikoniknya. Bertengger di tebing 70 meter menghadap Samudra Hindia, Pura Uluwatu menawarkan pemandangan yang menakjubkan. Saat matahari terbenam, saksikan Tari Kecak Api yang hipnotis yang ditampilkan oleh puluhan pria bernyanyi bersama.</p>',
                'price' => 350000,
                'duration' => $locale === 'en' ? '5-6 hours' : '5-6 jam',
                'location' => 'Uluwatu, Bali',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?w=600&h=400&fit=crop',
                'itinerary' => [
                    ['day' => 1, 'title' => $locale === 'en' ? 'Afternoon to Evening' : 'Siang hingga Malam', 'description' => $locale === 'en' ? 'Padang Padang Beach, Uluwatu Temple, Kecak Dance, Jimbaran Seafood Dinner' : 'Pantai Padang Padang, Pura Uluwatu, Tari Kecak, Makan Seafood Jimbaran'],
                ],
                'includes' => $locale === 'en'
                    ? ['AC vehicle', 'English driver', 'Kecak Dance ticket', 'Mineral water']
                    : ['Kendaraan AC', 'Driver berbahasa Inggris', 'Tiket Tari Kecak', 'Air mineral'],
                'excludes' => $locale === 'en'
                    ? ['Uluwatu entrance fee', 'Dinner at Jimbaran']
                    : ['Tiket masuk Uluwatu', 'Makan malam di Jimbaran'],
                'notes' => $locale === 'en'
                    ? ['Bring sarong for temple', 'Monkey warning: secure belongings', 'Best for sunset']
                    : ['Bawa sarung untuk pura', 'Peringatan monyet: amankan barang', 'Terbaik untuk sunset'],
            ],
        ];

        return collect($tours)->map(function ($data) {
            $tour = new TourPackage([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'price' => $data['price'],
                'duration' => $data['duration'],
                'location' => $data['location'],
                'thumbnail_url' => $data['thumbnail_url'],
                'itinerary' => $data['itinerary'],
                'includes' => $data['includes'],
                'excludes' => $data['excludes'],
                'notes' => $data['notes'],
                'is_active' => true,
            ]);

            $images = collect([
                $data['thumbnail_url'],
                'https://images.unsplash.com/photo-1573790387438-4da905039392?w=600&h=400&fit=crop',
                'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?w=600&h=400&fit=crop',
            ])->map(fn ($url, $i) => new ProductImage([
                'url' => $url,
                'alt_text' => $data['title'] . ' - Image ' . ($i + 1),
            ]));

            $tour->setRelation('images', $images);

            return $tour;
        });
    }

    private function findDemoTour(string $slug, string $locale): ?TourPackage
    {
        return $this->getDemoTours($locale)->first(fn ($t) => $t->slug === $slug);
    }

    private function getDemoVehicles(string $locale): \Illuminate\Support\Collection
    {
        $vehicles = [
            [
                'model_name' => $locale === 'en' ? 'Toyota Avanza 2024' : 'Toyota Avanza 2024',
                'capacity' => 6,
                'description' => $locale === 'en'
                    ? 'Comfortable and spacious MPV, perfect for families or small groups. Equipped with air conditioning and Bluetooth audio.'
                    : 'MPV yang nyaman dan luas, sempurna untuk keluarga atau grup kecil. Dilengkapi AC dan audio Bluetooth.',
                'image_url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0afa?w=600&h=400&fit=crop',
            ],
            [
                'model_name' => $locale === 'en' ? 'Toyota Innova Reborn' : 'Toyota Innova Reborn',
                'capacity' => 7,
                'description' => $locale === 'en'
                    ? 'Premium MPV with leather seats and extra legroom. Ideal for longer journeys and VIP transfers.'
                    : 'MPV premium dengan jok kulit dan ruang kaki ekstra. Ideal untuk perjalanan jauh dan transfer VIP.',
                'image_url' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600&h=400&fit=crop',
            ],
            [
                'model_name' => $locale === 'en' ? 'Toyota HiAce Commuter' : 'Toyota HiAce Commuter',
                'capacity' => 15,
                'description' => $locale === 'en'
                    ? 'Perfect for large groups and tour parties. Spacious interior with ample luggage space.'
                    : 'Sempurna untuk grup besar dan rombongan. Interior luas dengan ruang bagasi yang memadai.',
                'image_url' => 'https://images.unsplash.com/photo-1546776310-eef45dd6d63c?w=600&h=400&fit=crop',
            ],
        ];

        return collect($vehicles)->map(function ($data) {
            $vehicle = new Vehicle([
                'model_name' => $data['model_name'],
                'capacity' => $data['capacity'],
                'description' => $data['description'],
                'image_url' => $data['image_url'],
            ]);

            $vehicle->setRelation('images', collect([
                new ProductImage(['url' => $data['image_url'], 'alt_text' => $data['model_name']]),
            ]));

            return $vehicle;
        });
    }

    private function getDemoPages(string $locale): \Illuminate\Support\Collection
    {
        $pages = [
            [
                'title' => $locale === 'en' ? 'About Us' : 'Tentang Kami',
                'slug' => 'about',
                'content' => $locale === 'en'
                    ? '<p>Bali Paradise Tours has been providing exceptional tour experiences since 2015. Our team of experienced local drivers and guides are passionate about sharing the beauty and culture of Bali with visitors from around the world.</p><p>We believe that the best way to experience Bali is through the eyes of a local. That\'s why all our drivers are Balinese natives who know every hidden gem, secret beach, and authentic warung on the island.</p><p>Our mission is simple: to create unforgettable memories while ensuring your safety and comfort throughout your journey.</p>'
                    : '<p>Bali Paradise Tours telah memberikan pengalaman tour yang luar biasa sejak 2015. Tim driver dan guide lokal berpengalaman kami bersemangat untuk berbagi keindahan dan budaya Bali dengan pengunjung dari seluruh dunia.</p><p>Kami percaya bahwa cara terbaik untuk merasakan Bali adalah melalui mata lokal. Itulah mengapa semua driver kami adalah putra-putri Bali yang tahu setiap permata tersembunyi, pantai rahasia, dan warung autentik di pulau ini.</p><p>Misi kami sederhana: menciptakan kenangan yang tak terlupakan sambil memastikan keselamatan dan kenyamanan Anda sepanjang perjalanan.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => $locale === 'en' ? 'Terms & Conditions' : 'Syarat & Ketentuan',
                'slug' => 'terms',
                'content' => $locale === 'en'
                    ? '<h3>Booking & Payment</h3><p>A 50% deposit is required to confirm your booking. The remaining balance can be paid on the day of the tour. We accept cash and bank transfer.</p><h3>Cancellation Policy</h3><p>Free cancellation up to 24 hours before the tour. Cancellations within 24 hours will be charged 50% of the tour price.</p><h3>Refund Policy</h3><p>Full refund if the tour is cancelled due to bad weather or force majeure. No refund for no-shows.</p>'
                    : '<h3>Pemesanan & Pembayaran</h3><p>Deposit 50% diperlukan untuk mengkonfirmasi pemesanan Anda. Sisa pembayaran dapat dibayar pada hari tour. Kami menerima tunai dan transfer bank.</p><h3>Kebijakan Pembatalan</h3><p>Pembatalan gratis hingga 24 jam sebelum tour. Pembatalan dalam 24 jam akan dikenakan biaya 50% dari harga tour.</p><h3>Kebijakan Pengembalian Dana</h3><p>Pengembalian dana penuh jika tour dibatalkan karena cuaca buruk atau force majeure. Tidak ada pengembalian dana untuk no-show.</p>',
                'sort_order' => 2,
            ],
        ];

        return collect($pages)->map(fn ($data) => new Page([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'is_published' => true,
            'sort_order' => $data['sort_order'],
        ]));
    }

    private function findDemoPage(string $slug, string $locale): ?Page
    {
        return $this->getDemoPages($locale)->first(fn ($p) => $p->slug === $slug);
    }

    private function getDemoReviews(): \Illuminate\Support\Collection
    {
        $reviews = [
            ['name' => 'Sarah M.', 'rating' => 5, 'comment' => 'Absolutely amazing experience! Our driver Wayan was so knowledgeable and friendly. The sunrise trek was unforgettable.', 'date' => '2025-12-15'],
            ['name' => 'Thomas K.', 'rating' => 5, 'comment' => 'Best tour in Bali! The Nusa Penida trip was well organized. Kelingking Beach is even more beautiful in person.', 'date' => '2025-11-28'],
            ['name' => 'Lisa & Mark', 'rating' => 4, 'comment' => 'Great day trip to Ubud. The rice terraces are stunning. Only wish we had more time at the waterfall.', 'date' => '2025-11-10'],
            ['name' => 'Yuki T.', 'rating' => 5, 'comment' => 'The Uluwatu sunset tour was magical. Kecak dance was mesmerizing. Highly recommend!', 'date' => '2025-10-22'],
            ['name' => 'Pierre D.', 'rating' => 5, 'comment' => 'Professional service from start to finish. The vehicle was clean and comfortable. Will definitely book again.', 'date' => '2025-10-05'],
            ['name' => 'Maria S.', 'rating' => 4, 'comment' => 'Lovely tour, great value for money. Our driver spoke excellent English and took us to amazing local restaurants.', 'date' => '2025-09-18'],
        ];

        return collect($reviews)->map(function ($data) {
            $review = new Review([
                'reviewer_name' => $data['name'],
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'is_approved' => true,
            ]);
            $review->created_at = \Carbon\Carbon::parse($data['date']);

            return $review;
        });
    }

    private function getDemoGalleryImages(string $locale): \Illuminate\Support\Collection
    {
        $images = [
            ['url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&h=600&fit=crop', 'alt' => 'Bali Temple'],
            ['url' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=800&h=600&fit=crop', 'alt' => 'Bali Beach'],
            ['url' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=800&h=600&fit=crop', 'alt' => 'Bali Volcano'],
            ['url' => 'https://images.unsplash.com/photo-1573790387438-4da905039392?w=800&h=600&fit=crop', 'alt' => 'Bali Rice Fields'],
            ['url' => 'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?w=800&h=600&fit=crop', 'alt' => 'Bali Sunset'],
            ['url' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?w=800&h=600&fit=crop', 'alt' => 'Bali Culture'],
        ];

        return collect($images);
    }

    private function getDemoReviewSchema(string $locale): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'TouristAttraction',
            'name' => 'Bali Paradise Tours',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.8',
                'reviewCount' => '6',
            ],
        ]);
    }
}
