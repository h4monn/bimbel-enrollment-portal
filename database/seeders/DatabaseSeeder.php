<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ClassRoom;
use App\Models\Program;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@bimbel.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password123',
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $programs = [
            [
                'name' => 'Matematika SD',
                'slug' => 'matematika-sd',
                'education_level' => 'SD',
                'description' => 'Latihan berhitung terstruktur dengan lembar kerja bertahap untuk siswa kelas 1 sampai 6 SD.',
                'duration' => '1 Semester',
                'price' => 1350000,
                'classes' => [
                    ['name' => 'SD Sore A', 'teacher_name' => 'Rina Kusuma', 'schedule_days' => 'Senin & Kamis', 'schedule_time' => '15.00 - 16.30', 'room' => 'Ruang 101', 'quota' => 15, 'enrolled_count' => 11],
                    ['name' => 'SD Sore B', 'teacher_name' => 'Hendra Wijaya', 'schedule_days' => 'Selasa & Jumat', 'schedule_time' => '15.00 - 16.30', 'room' => 'Ruang 102', 'quota' => 15, 'enrolled_count' => 8],
                ],
            ],
            [
                'name' => 'Matematika SMP',
                'slug' => 'matematika-smp',
                'education_level' => 'SMP',
                'description' => 'Pendalaman aljabar, geometri, dan pemecahan soal kurikulum nasional untuk kelas 7 hingga 9.',
                'duration' => '1 Semester',
                'price' => 1550000,
                'classes' => [
                    ['name' => 'SMP Sore A', 'teacher_name' => 'Budi Santoso', 'schedule_days' => 'Senin & Kamis', 'schedule_time' => '16.45 - 18.15', 'room' => 'Ruang 102', 'quota' => 14, 'enrolled_count' => 12],
                    ['name' => 'SMP Sabtu Pagi', 'teacher_name' => 'Budi Santoso', 'schedule_days' => 'Sabtu', 'schedule_time' => '09.00 - 12.00', 'room' => 'Ruang 103', 'quota' => 12, 'enrolled_count' => 12],
                ],
            ],
            [
                'name' => 'Inggris SMA',
                'slug' => 'inggris-sma',
                'education_level' => 'SMA',
                'description' => 'Pemahaman bacaan, tata bahasa, dan latihan soal ujian sekolah untuk siswa kelas 10 sampai 12.',
                'duration' => '1 Semester',
                'price' => 1650000,
                'classes' => [
                    ['name' => 'SMA Sore A', 'teacher_name' => 'Dian Pratiwi', 'schedule_days' => 'Selasa & Jumat', 'schedule_time' => '16.45 - 18.15', 'room' => 'Ruang 104', 'quota' => 14, 'enrolled_count' => 9],
                ],
            ],
            [
                'name' => 'Intensif UTBK',
                'slug' => 'intensif-utbk',
                'education_level' => 'UTBK',
                'description' => 'Pembahasan penalaran matematika dan literasi dengan evaluasi berkala untuk kelas 12 dan alumni.',
                'duration' => '4 Bulan',
                'price' => 2450000,
                'classes' => [
                    ['name' => 'UTBK Sore', 'teacher_name' => 'Agus Setiawan', 'schedule_days' => 'Senin, Rabu & Jumat', 'schedule_time' => '16.00 - 18.00', 'room' => 'Ruang 201', 'quota' => 12, 'enrolled_count' => 7],
                ],
            ],
        ];

        foreach ($programs as $item) {
            $classes = $item['classes'];
            unset($item['classes']);

            $program = Program::firstOrCreate(
                ['slug' => $item['slug']],
                $item + ['is_active' => true]
            );

            foreach ($classes as $classData) {
                ClassRoom::firstOrCreate(
                    ['program_id' => $program->id, 'name' => $classData['name']],
                    $classData + ['is_active' => true]
                );
            }
        }
    }
}
