<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@teknik.ac.id'],
            [
                'name' => 'Administrator Fakultas Teknik',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'department' => 'Tata Usaha Teknik',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@teknik.ac.id'],
            [
                'name' => 'Staf Laboratorium Teknik',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'phone' => '081234567891',
                'department' => 'Unit Laboratorium',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'mahasiswa@teknik.ac.id'],
            [
                'name' => 'Ahmad Fauzi (Mahasiswa)',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'phone' => '081234567892',
                'department' => 'Teknik Elektro',
            ]
        );

        $roomsData = [
            [
                'name' => 'Laboratorium IoT & Robotika',
                'code' => 'LAB-IOT-01',
                'slug' => 'laboratorium-iot-robotika',
                'capacity' => 30,
                'location' => 'Gedung F Lantai 3, Universitas Muhammadiyah Ponorogo',
                'description' => 'Laboratorium riset kendali cerdas, mikrokontroler, dan sistem robotika otomasi industri.',
                'facilities' => ['Smart Screen 75"', '3D Printer Farm', 'Solder Station', 'AC Dual Unit', 'High Speed WiFi'],
                'status' => 'available',
                'qr_identifier' => 'room-iot-robotika-dekanat3',
            ],
            [
                'name' => 'Laboratorium Jaringan & Keamanan Siber',
                'code' => 'LAB-NET-02',
                'slug' => 'laboratorium-jaringan-keamanan-siber',
                'capacity' => 40,
                'location' => 'Gedung F Lantai 2, Universitas Muhammadiyah Ponorogo',
                'description' => 'Fasilitas praktikum infrastruktur jaringan enterprise, virtualisasi cloud, dan ethical hacking.',
                'facilities' => ['Server Rack Enterprise', 'Cisco Router Simulator', 'Proyektor Ultra Short Throw', 'AC Multi-Split'],
                'status' => 'available',
                'qr_identifier' => 'room-cybersecurity-gkb2',
            ],
            [
                'name' => 'Studio Gambar Rekayasa & CAD',
                'code' => 'STD-CAD-01',
                'slug' => 'studio-gambar-rekayasa-cad',
                'capacity' => 25,
                'location' => 'Gedung F Lantai 1, Universitas Muhammadiyah Ponorogo',
                'description' => 'Studio perancangan mekanikal 3D CAD, Finite Element Analysis (FEA), dan komputasi fluida.',
                'facilities' => ['25 Workstation GPU RTX', 'Plotter A0 Epson', 'Layar Sentuh Interaktif', 'Sound System'],
                'status' => 'available',
                'qr_identifier' => 'room-studio-cad-mesin1',
            ],
            [
                'name' => 'Ruang Sidang Senat & Teleconference',
                'code' => 'SDG-SENAT-01',
                'slug' => 'ruang-sidang-senat-teleconference',
                'capacity' => 50,
                'location' => 'Gedung F Lantai 4, Universitas Muhammadiyah Ponorogo',
                'description' => 'Ruang sidang formal dekanat, ujian tesis, dan telekonferensi internasional.',
                'facilities' => ['Mikrofon Delegasi Conference', 'Dual Camera PTZ 4K', 'Peredam Akustik', 'Podium Smart Screen'],
                'status' => 'available',
                'qr_identifier' => 'room-sidang-senat-dekanat4',
            ],
            [
                'name' => 'Smart Meeting Room Kolaborasi',
                'code' => 'SMT-MEET-03',
                'slug' => 'smart-meeting-room-kolaborasi',
                'capacity' => 16,
                'location' => 'Gedung F Lantai 2, Universitas Muhammadiyah Ponorogo',
                'description' => 'Ruangan pertemuan modern untuk brainstorming proyek, presentasi tim riset, dan mentoring industri.',
                'facilities' => ['Interactive Whiteboard 86"', 'Wireless Presentation', 'Coffee Maker Bar', 'Ergonomic Chairs'],
                'status' => 'available',
                'qr_identifier' => 'room-smart-meeting-riset2',
            ],
        ];

        foreach ($roomsData as $data) {
            $room = Room::firstOrCreate(
                ['code' => $data['code']],
                $data
            );

            if ($room->code === 'LAB-IOT-01') {
                $booking = Booking::firstOrCreate(
                    ['booking_code' => 'BK-INIT-IOT01'],
                    [
                        'user_id' => $student->id,
                        'room_id' => $room->id,
                        'borrower_name' => 'Ahmad Fauzi',
                        'borrower_nim' => '22041001',
                        'borrower_phone' => '081234567892',
                        'title' => 'Praktikum Kendali Drone Otonom',
                        'purpose' => 'Pengujian sensor lidar dan uji terbang navigasi obstacle avoidance.',
                        'start_time' => Carbon::today()->setTime(13, 0),
                        'end_time' => Carbon::today()->setTime(15, 30),
                        'status' => 'confirmed',
                    ]
                );

                BookingLog::firstOrCreate(
                    ['booking_id' => $booking->id, 'action' => 'created'],
                    [
                        'user_id' => $student->id,
                        'payload' => ['seeded' => true],
                    ]
                );
            }
        }

        // Seed Jadwal Kuliah & Praktikum Rutin Mingguan
        $iotRoom = Room::where('code', 'LAB-IOT-01')->first();
        if ($iotRoom) {
            Schedule::firstOrCreate(
                [
                    'room_id' => $iotRoom->id,
                    'day_of_week' => 1, // Senin
                    'start_time' => '08:00:00',
                ],
                [
                    'end_time' => '10:30:00',
                    'title' => 'Pemrograman Sistem Tertanam (Kelas A)',
                    'lecturer_name' => 'Dr. Ir. Budi Raharjo, M.T.',
                    'lecturer_code' => 'NIP. 197805122005011002',
                    'phone' => '081234567890',
                    'status' => 'active',
                    'purpose' => 'Praktikum pemrograman mikrokontroler STM32 dan FreeRTOS.',
                ]
            );

            Schedule::firstOrCreate(
                [
                    'room_id' => $iotRoom->id,
                    'day_of_week' => 3, // Rabu
                    'start_time' => '13:00:00',
                ],
                [
                    'end_time' => '15:30:00',
                    'title' => 'Robotika Industri & Kendali Cerdas',
                    'lecturer_name' => 'Dr. Ir. Budi Raharjo, M.T.',
                    'lecturer_code' => 'NIP. 197805122005011002',
                    'phone' => '081234567890',
                    'status' => 'active',
                    'purpose' => 'Implementasi Inverse Kinematics dan penglihatan komputer pada Robot Arm.',
                ]
            );
        }

        $netRoom = Room::where('code', 'LAB-NET-02')->first();
        if ($netRoom) {
            Schedule::firstOrCreate(
                [
                    'room_id' => $netRoom->id,
                    'day_of_week' => 2, // Selasa
                    'start_time' => '08:30:00',
                ],
                [
                    'end_time' => '11:00:00',
                    'title' => 'Infrastruktur Cloud & Jaringan Enterprise',
                    'lecturer_name' => 'Hendra Kusuma, M.Kom.',
                    'lecturer_code' => 'NIP. 198503202010121004',
                    'phone' => '081234567891',
                    'status' => 'active',
                    'purpose' => 'Konfigurasi routing BGP enterprise dan cluster Kubernetes.',
                ]
            );

            Schedule::firstOrCreate(
                [
                    'room_id' => $netRoom->id,
                    'day_of_week' => 4, // Kamis
                    'start_time' => '10:00:00',
                ],
                [
                    'end_time' => '12:30:00',
                    'title' => 'Ethical Hacking & Keamanan Siber Lanjut',
                    'lecturer_name' => 'Hendra Kusuma, M.Kom.',
                    'lecturer_code' => 'NIP. 198503202010121004',
                    'phone' => '081234567891',
                    'status' => 'active',
                    'purpose' => 'Simulasi penetration testing dan audit keamanan server web.',
                ]
            );
        }

        $cadRoom = Room::where('code', 'STD-CAD-01')->first();
        if ($cadRoom) {
            Schedule::firstOrCreate(
                [
                    'room_id' => $cadRoom->id,
                    'day_of_week' => 5, // Jumat
                    'start_time' => '08:00:00',
                ],
                [
                    'end_time' => '10:30:00',
                    'title' => 'Pemodelan CAD 3D & Analisis Elemen Hingga (FEA)',
                    'lecturer_name' => 'Ir. Agung Prabowo, M.T.',
                    'lecturer_code' => 'NIP. 198109152008121001',
                    'phone' => '081234567893',
                    'status' => 'active',
                    'purpose' => 'Simulasi tegangan mekanikal dan perancangan poros transmisi daya.',
                ]
            );
        }
    }
}
