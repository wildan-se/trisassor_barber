<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\BarberSchedule;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    public function run(): void
    {
        $barbers = [
            [
                'name'      => 'Captain Mike',
                'slug'      => 'captain-mike',
                'specialty' => 'Classic & Pompadour',
                'bio'       => 'Dengan pengalaman lebih dari 10 tahun, Captain Mike adalah maestro gaya klasik. Spesialisasinya adalah Pompadour, Slick Back, dan semua varian Classic Cut yang memperlihatkan karakter kuat pria modern.',
                'instagram' => 'captainmike_barber',
                'is_active' => true,
            ],
            [
                'name'      => 'Reza Fade',
                'slug'      => 'reza-fade',
                'specialty' => 'Skin Fade & Modern',
                'bio'       => 'Reza adalah spesialis fade yang presisinya tidak tertandingi. Dari skin fade hingga mid fade, setiap transisi dikerjakan dengan teliti untuk hasil yang bersih dan tajam.',
                'instagram' => 'rezafade_official',
                'is_active' => true,
            ],
            [
                'name'      => 'Andre Color',
                'slug'      => 'andre-color',
                'specialty' => 'Hair Coloring & Styling',
                'bio'       => 'Andre adalah seniman warna di balik transformasi terbaik Trisassor. Mulai dari pewarnaan natural hingga fashion colors yang berani, Andre memastikan setiap warna teraplikasi sempurna.',
                'instagram' => 'andrecolor_hair',
                'is_active' => true,
            ],
        ];

        foreach ($barbers as $barberData) {
            $barber = Barber::updateOrCreate(
                ['slug' => $barberData['slug']],
                $barberData
            );

            // Seed jadwal: Senin-Jumat 10:00-21:00, Sabtu-Minggu 09:00-22:00
            $weekdays  = [1, 2, 3, 4, 5]; // Senin-Jumat
            $weekends  = [0, 6];          // Minggu, Sabtu

            foreach ($weekdays as $day) {
                BarberSchedule::updateOrCreate(
                    ['barber_id' => $barber->id, 'day_of_week' => $day],
                    ['start_time' => '10:00:00', 'end_time' => '21:00:00', 'is_available' => true]
                );
            }

            foreach ($weekends as $day) {
                BarberSchedule::updateOrCreate(
                    ['barber_id' => $barber->id, 'day_of_week' => $day],
                    ['start_time' => '09:00:00', 'end_time' => '22:00:00', 'is_available' => true]
                );
            }
        }

        $this->command->info('✅ ' . count($barbers) . ' barbers seeded with schedules.');
    }
}
