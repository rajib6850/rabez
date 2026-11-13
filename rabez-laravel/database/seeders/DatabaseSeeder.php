<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $catJerseys = Category::create([
            'name' => 'Football Jerseys',
            'slug' => 'jerseys',
            'product_type' => 'jersey',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catHoodies = Category::create([
            'name' => 'Winter Hoodies',
            'slug' => 'winter-hoodies',
            'product_type' => 'apparel',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Football Boots',
            'slug' => 'boots',
            'product_type' => 'footwear',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Tracksuits & Pants',
            'slug' => 'tracksuits',
            'product_type' => 'apparel',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
            'product_type' => 'accessory',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Default Settings
        Setting::set('store_name', 'RABEZ');
        Setting::set('store_tagline', 'Wear What You Stand For');
        Setting::set('store_location', 'Road-10, College Avenue, Barishal');
        Setting::set('hotline', '+8801612359652');
        Setting::set('whatsapp', '8801612359652');
        Setting::set('shipping_inside_dhaka', 70);
        Setting::set('shipping_outside_dhaka', 130);
        Setting::set('custom_print_fee', 250);
        Setting::set('free_shipping_min', 3500);

        // 2. 18 Products
        $products = [
            [
                'sku' => 'RMA-HOME-HS',
                'name' => 'Real Madrid 26/27 Home Half Sleeve Jersey',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1350,
                'price' => 1150,
                'short_desc' => 'Crisp classic white home kit with gold & maroon shoulder trim. Breathable player honeycomb jacquard.',
                'image' => '/assets/images/products/real-madrid-home-hs.jpg',
                'badges' => ['Player Edition', 'Hot'],
                'is_featured' => true,
            ],
            [
                'sku' => 'FCB-HOME-HS',
                'name' => 'FC Barcelona 26/27 Home Half Sleeve Jersey',
                'category_id' => $catJerseys->id,
                'club' => 'barcelona',
                'club_label' => 'FC Barcelona',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1350,
                'price' => 1150,
                'short_desc' => 'Iconic split Blaugrana vertical stripes with embroidered silicone crest and sweat-wicking knit.',
                'image' => '/assets/images/products/barcelona-home-hs.jpg',
                'badges' => ['Player Edition', 'Best Seller'],
                'is_featured' => true,
            ],
            [
                'sku' => 'ARG-MESSI-FS',
                'name' => "Argentina 3-Star Messi 10 Celebration Full Sleeve",
                'category_id' => $catJerseys->id,
                'club' => 'argentina',
                'club_label' => 'Argentina',
                'sleeve' => 'full-sleeve',
                'sleeve_label' => 'Full Sleeve',
                'regular_price' => 1500,
                'price' => 1300,
                'short_desc' => 'Albicedleste sky-blue & white stripes with golden 3 stars, Sol de Mayo sun emblem, and ribbed cuffs.',
                'image' => '/assets/images/products/messi-celebration-fs.jpg',
                'badges' => ['Full Sleeve', '3-Star Gold'],
                'is_featured' => true,
            ],
            [
                'sku' => 'ARS-HOME-HS',
                'name' => 'Arsenal 26/27 Home Half Sleeve Jersey',
                'category_id' => $catJerseys->id,
                'club' => 'arsenal',
                'club_label' => 'Arsenal',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1350,
                'price' => 1150,
                'short_desc' => 'Bold Scarlet Red body with pure white raglan sleeves and minimalist Gothic cannon crest.',
                'image' => '/assets/images/products/arsenal-home-hs.jpg',
                'badges' => ['Player Edition'],
                'is_featured' => true,
            ],
            [
                'sku' => 'MUN-HOME-HS',
                'name' => 'Manchester United 26/27 Home Half Sleeve Jersey',
                'category_id' => $catJerseys->id,
                'club' => 'man-utd',
                'club_label' => 'Man United',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1350,
                'price' => 1150,
                'short_desc' => 'Deep crimson red with gradient shadow texture and iconic Red Devil neck emblem.',
                'image' => '/assets/images/products/man-utd-home-hs.jpg',
                'badges' => ['Player Edition'],
                'is_featured' => true,
            ],
            [
                'sku' => 'BD-HOME-HS',
                'name' => 'Bangladesh National Team 26/27 Home Half Sleeve',
                'category_id' => $catJerseys->id,
                'club' => 'bangladesh',
                'club_label' => 'Bangladesh',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1250,
                'price' => 1150,
                'short_desc' => 'Emerald Green with Bengal Tiger geometric watermarks and striking red sleeve borders.',
                'image' => '/assets/images/products/bangladesh-home-hs.jpg',
                'badges' => ['National Kit', 'Patriotic'],
                'is_featured' => true,
            ],
            [
                'sku' => 'RMA-AWAY-FS',
                'name' => 'Real Madrid 26/27 Away Orange Full Sleeve',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'full-sleeve',
                'sleeve_label' => 'Full Sleeve',
                'regular_price' => 1500,
                'price' => 1300,
                'short_desc' => 'Vibrant electric sunset orange tribute edition with full-length articulated comfort sleeves.',
                'image' => '/assets/images/products/real-madrid-away-fs.jpg',
                'badges' => ['Full Sleeve', 'Away Kit'],
                'is_featured' => true,
            ],
            [
                'sku' => 'RMA-3RD-HS',
                'name' => 'Real Madrid 26/27 Charcoal & Gold 3rd Kit Half Sleeve',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1350,
                'price' => 1150,
                'short_desc' => 'Stealth charcoal base with embossed RMCF monogram pattern and brushed antique gold badges.',
                'image' => '/assets/images/products/real-madrid-3rd-hs.jpg',
                'badges' => ['3rd Kit'],
                'is_featured' => true,
            ],
            [
                'sku' => 'RMA-POLO-WHITE',
                'name' => 'Real Madrid 26/27 Signature Polo Collar Edition',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1400,
                'price' => 1150,
                'short_desc' => 'Smart polo collar with concealed 2-button placket. Engineered for stadium and casual streetwear.',
                'image' => '/assets/images/products/real-madrid-polo-white-hs.jpg',
                'badges' => ['Polo Edition'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RMA-TEAL-TREFOIL',
                'name' => 'Real Madrid Retro Teal Trefoil Collector Edition',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'half-sleeve',
                'sleeve_label' => 'Half Sleeve',
                'regular_price' => 1450,
                'price' => 1150,
                'short_desc' => 'Deep seafoam teal jacquard weave with heritage Trefoil logo and ribbed V-neck finish.',
                'image' => '/assets/images/products/real-madrid-teal-trefoil-hs.jpg',
                'badges' => ['Limited Drop'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RET-RMA-2002',
                'name' => 'Real Madrid 2002 Centenary Retro (Zidane #9 / #5)',
                'category_id' => $catJerseys->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'retro',
                'sleeve_label' => 'Retro Kit',
                'regular_price' => 1550,
                'price' => 1300,
                'short_desc' => 'Pure unbranded centenary white shirt from Glasgow UCL final. High-density flock print available.',
                'image' => '/assets/images/products/real-madrid-home-hs.jpg',
                'badges' => ['Retro Edition'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RET-FCB-2006',
                'name' => 'FC Barcelona 2006 Paris Champions Retro (Ronaldinho #10)',
                'category_id' => $catJerseys->id,
                'club' => 'barcelona',
                'club_label' => 'FC Barcelona',
                'sleeve' => 'retro',
                'sleeve_label' => 'Retro Kit',
                'regular_price' => 1550,
                'price' => 1300,
                'short_desc' => 'Golden yellow accents on deep blue/garnet stripes. Authentic felt typography.',
                'image' => '/assets/images/products/barcelona-home-hs.jpg',
                'badges' => ['Retro Edition'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RET-ARG-1986',
                'name' => 'Argentina 1986 Mexico Champions Retro (Maradona #10)',
                'category_id' => $catJerseys->id,
                'club' => 'argentina',
                'club_label' => 'Argentina',
                'sleeve' => 'retro',
                'sleeve_label' => 'Retro Kit',
                'regular_price' => 1550,
                'price' => 1300,
                'short_desc' => 'Cotton-poly blend vintage stripes with classic AFA shield and Maradona #10 back print.',
                'image' => '/assets/images/products/messi-celebration-fs.jpg',
                'badges' => ['Retro Legend'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RET-ARS-2004',
                'name' => 'Arsenal 2003/04 Invincibles O2 Retro (Henry #14)',
                'category_id' => $catJerseys->id,
                'club' => 'arsenal',
                'club_label' => 'Arsenal',
                'sleeve' => 'retro',
                'sleeve_label' => 'Retro Kit',
                'regular_price' => 1550,
                'price' => 1300,
                'short_desc' => 'The iconic 49-unbeaten jersey with yellow O2 flock sponsor print and white raglan sleeves.',
                'image' => '/assets/images/products/arsenal-home-hs.jpg',
                'badges' => ['Invincibles Retro'],
                'is_featured' => false,
            ],
            [
                'sku' => 'RET-MUN-2008',
                'name' => 'Man United 2008 Moscow Double Retro (Ronaldo #7)',
                'category_id' => $catJerseys->id,
                'club' => 'man-utd',
                'club_label' => 'Man United',
                'sleeve' => 'retro',
                'sleeve_label' => 'Retro Kit',
                'regular_price' => 1550,
                'price' => 1300,
                'short_desc' => 'Classic white back-stripe design with AIG sponsor and gold Premier League / UCL champions patches.',
                'image' => '/assets/images/products/man-utd-home-hs.jpg',
                'badges' => ['Retro Legend'],
                'is_featured' => false,
            ],
            [
                'sku' => 'HD-MESSI-BARCA-ARG',
                'name' => "Messi #10 Barca × Argentina 'The Best' Winter Hoodie",
                'category_id' => $catHoodies->id,
                'club' => 'argentina',
                'club_label' => 'Messi / Argentina × Barca',
                'sleeve' => 'winter-hoodie',
                'sleeve_label' => 'Winter Hoodie',
                'regular_price' => 1650,
                'price' => 1450,
                'short_desc' => 'Heavyweight 340 GSM premium cotton fleece hoodie with screen-printed Messi #10, dual Barca & Argentina crests, and kangaroo pocket.',
                'image' => '/assets/images/products/messi-barca-arg-hoodie.jpg',
                'badges' => ['Winter Drop', 'Best Seller'],
                'is_featured' => true,
            ],
            [
                'sku' => 'HD-RMA-FLEECE-26',
                'name' => 'Real Madrid 26/27 Winter Fleece Warmup Hoodie',
                'category_id' => $catHoodies->id,
                'club' => 'real-madrid',
                'club_label' => 'Real Madrid',
                'sleeve' => 'winter-hoodie',
                'sleeve_label' => 'Winter Hoodie',
                'regular_price' => 1650,
                'price' => 1450,
                'short_desc' => 'Thermal winter fleece hoodie with rubberized silicone Real Madrid crest, gold aglets, and ribbed cuffs.',
                'image' => '/assets/images/products/real-madrid-home-hs.jpg',
                'badges' => ['Winter Drop', 'Thermal Fleece'],
                'is_featured' => true,
            ],
            [
                'sku' => 'HD-ARG-CAMPEONES',
                'name' => 'Argentina 3-Star Campeones Heavyweight Hoodie',
                'category_id' => $catHoodies->id,
                'club' => 'argentina',
                'club_label' => 'Argentina',
                'sleeve' => 'winter-hoodie',
                'sleeve_label' => 'Winter Hoodie',
                'regular_price' => 1650,
                'price' => 1450,
                'short_desc' => 'Pitch black 340 GSM fleece hoodie featuring the golden 3-star AFA crest and Campeones del Mundo emblem.',
                'image' => '/assets/images/products/messi-celebration-fs.jpg',
                'badges' => ['Winter Drop', '3-Star Gold'],
                'is_featured' => true,
            ],
        ];

        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];

        foreach ($products as $pData) {
            $pData['slug'] = Str::slug($pData['name']);
            $product = Product::create($pData);

            foreach ($sizes as $s) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $s,
                    'stock_qty' => 25,
                    'is_in_stock' => true,
                ]);
            }
        }
    }
}
