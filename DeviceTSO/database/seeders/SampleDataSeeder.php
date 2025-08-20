<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Room;
use App\Models\Device;
use App\Models\ChecklistItem;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create floors
        $floor1 = Floor::create(['floor_name' => 'Lantai 1']);
        $floor2 = Floor::create(['floor_name' => 'Lantai 2']);

        // Create rooms
        $room1 = Room::create([
            'floor_id' => $floor1->floor_id,
            'room_name' => 'Direktur Marketing'
        ]);
        
        $room2 = Room::create([
            'floor_id' => $floor1->floor_id,
            'room_name' => 'Direktur IT'
        ]);

        // Create devices for Direktur Marketing
        $devices1 = [
            ['device_name' => 'TV Samsung 85', 'device_type' => 'SMART TV', 'serial_number' => 'SM-TV-001'],
            ['device_name' => 'Polycom', 'device_type' => 'VIDEO CONFERENCE', 'serial_number' => 'VC-POLY-001'],
            ['device_name' => 'Logitech', 'device_type' => 'MINI PC', 'serial_number' => 'PC-LOG-001'],
            ['device_name' => 'MiniPC', 'device_type' => 'MINI PC', 'serial_number' => 'PC-MINI-001'],
        ];

        foreach ($devices1 as $device) {
            Device::create([
                'room_id' => $room1->room_id,
                'device_name' => $device['device_name'],
                'device_type' => $device['device_type'],
                'serial_number' => $device['serial_number']
            ]);
        }

        // Create devices for Direktur IT
        $devices2 = [
            ['device_name' => 'TV Samsung 85', 'device_type' => 'SMART TV', 'serial_number' => 'SM-TV-002'],
            ['device_name' => 'Polycom', 'device_type' => 'VIDEO CONFERENCE', 'serial_number' => 'VC-POLY-002'],
            ['device_name' => 'Logitech', 'device_type' => 'MINI PC', 'serial_number' => 'PC-LOG-002'],
            ['device_name' => 'BenQ', 'device_type' => 'MINI PC', 'serial_number' => 'PC-BENQ-001'],
            ['device_name' => 'Smartboard', 'device_type' => 'SMARTBOARD', 'serial_number' => 'SB-001'],
        ];

        foreach ($devices2 as $device) {
            Device::create([
                'room_id' => $room2->room_id,
                'device_name' => $device['device_name'],
                'device_type' => $device['device_type'],
                'serial_number' => $device['serial_number']
            ]);
        }

        // Create checklist items
        $checklistItems = [
            // SMART TV checklist
            ['device_type' => 'SMART TV', 'question' => 'Apakah power berfungsi?'],
            ['device_type' => 'SMART TV', 'question' => 'Apakah audio berfungsi?'],
            ['device_type' => 'SMART TV', 'question' => 'Apakah video berfungsi?'],
            ['device_type' => 'SMART TV', 'question' => 'Apakah koneksi internet berfungsi?'],
            ['device_type' => 'SMART TV', 'question' => 'Apakah aplikasi berfungsi?'],
            
            // VIDEO CONFERENCE checklist
            ['device_type' => 'VIDEO CONFERENCE', 'question' => 'Apakah power berfungsi?'],
            ['device_type' => 'VIDEO CONFERENCE', 'question' => 'Apakah audio berfungsi?'],
            ['device_type' => 'VIDEO CONFERENCE', 'question' => 'Apakah video berfungsi?'],
            ['device_type' => 'VIDEO CONFERENCE', 'question' => 'Apakah koneksi berfungsi?'],
            ['device_type' => 'VIDEO CONFERENCE', 'question' => 'Apakah kabel berfungsi?'],
            
            // MINI PC checklist
            ['device_type' => 'MINI PC', 'question' => 'Apakah power berfungsi?'],
            ['device_type' => 'MINI PC', 'question' => 'Apakah PC berfungsi?'],
            ['device_type' => 'MINI PC', 'question' => 'Apakah aplikasi berfungsi?'],
            ['device_type' => 'MINI PC', 'question' => 'Apakah koneksi berfungsi?'],
            ['device_type' => 'MINI PC', 'question' => 'Apakah kabel berfungsi?'],
            
            // SMARTBOARD checklist
            ['device_type' => 'SMARTBOARD', 'question' => 'Apakah power berfungsi?'],
            ['device_type' => 'SMARTBOARD', 'question' => 'Apakah touch screen berfungsi?'],
            ['device_type' => 'SMARTBOARD', 'question' => 'Apakah koneksi berfungsi?'],
            ['device_type' => 'SMARTBOARD', 'question' => 'Apakah aplikasi berfungsi?'],
            
            // General checklist items
            ['device_type' => 'general', 'question' => 'Apakah device bersih?'],
            ['device_type' => 'general', 'question' => 'Apakah tidak ada kerusakan fisik?'],
            ['device_type' => 'general', 'question' => 'Apakah semua kabel terpasang dengan benar?'],
        ];

        foreach ($checklistItems as $item) {
            ChecklistItem::create([
                'device_type' => $item['device_type'],
                'question' => $item['question']
            ]);
        }
    }
}
