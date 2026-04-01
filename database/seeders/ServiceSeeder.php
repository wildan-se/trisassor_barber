<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'             => 'Classic Haircut',
                'slug'             => 'classic-haircut',
                'description'      => 'Konsultasi gaya, pencucian rambut, potongan presisi, pijat ringan, dan styling akhir dengan pomade premium.',
                'price'            => 85000,
                'duration_minutes' => 60,
                'image'            => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?q=80&w=800',
                'is_featured'      => false,
                'is_active'        => true,
                'sort_order'       => 1,
            ],
            [
                'name'             => 'The "Gentleman"',
                'slug'             => 'the-gentleman',
                'description'      => 'Paket komplit: Classic Haircut digabung dengan Hot Towel Shave untuk tampilan segar maksimal dan relaksasi total. Pilihan terfavorit pelanggan kami.',
                'price'            => 130000,
                'duration_minutes' => 90,
                'image'            => 'https://images.unsplash.com/photo-1593702288056-ccbfcf3eb235?q=80&w=800',
                'is_featured'      => true,
                'is_active'        => true,
                'sort_order'       => 2,
            ],
            [
                'name'             => 'Hot Towel Shave',
                'slug'             => 'hot-towel-shave',
                'description'      => 'Cukur kumis dan jenggot ala pria sejati dengan handuk hangat, krim cukur mewah, dan aftershave menenangkan.',
                'price'            => 60000,
                'duration_minutes' => 45,
                'image'            => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&w=800',
                'is_featured'      => false,
                'is_active'        => true,
                'sort_order'       => 3,
            ],
            [
                'name'             => 'Hair Coloring',
                'slug'             => 'hair-coloring',
                'description'      => 'Pewarnaan rambut profesional. Dari penutupan uban natural hingga warna-warna fashion yang berani. Harga mulai Rp 150.000 tergantung teknik.',
                'price'            => 150000,
                'duration_minutes' => 120,
                'image'            => 'https://images.unsplash.com/photo-1521490212873-1961e6afb063?q=80&w=800',
                'is_featured'      => false,
                'is_active'        => true,
                'sort_order'       => 4,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
        }

        $this->command->info('✅ ' . count($services) . ' services seeded.');
    }
}
