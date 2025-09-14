<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Driving with Basic Troubleshooting',
                'duration' => '6 months',
                'description' => 'Learn driving skills with basic vehicle troubleshooting'
            ],
            [
                'name' => 'Shielded Metal Arc Welding and Fabrication',
                'duration' => '8 months',
                'description' => 'Master welding techniques and metal fabrication'
            ],
            [
                'name' => 'Electrical Installation',
                'duration' => '10 months',
                'description' => 'Learn electrical installation and maintenance'
            ],
            [
                'name' => 'Computer Basic Literacy',
                'duration' => '4 months',
                'description' => 'Fundamentals of computer operation and software'
            ]
        ];

        foreach ($programs as $program) {
            DB::table('programs')->updateOrInsert(
                ['name' => $program['name']],
                $program
            );
        }
    }
}
