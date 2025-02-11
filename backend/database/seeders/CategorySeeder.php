<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('../txt/temakorok.csv');


        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data[] = [
                    'category' => $row[0],
                    'level' => $row[1],
                    'text' => ""
                ];
            }
            fclose($handle);
        }


        if (Category::count() === 0) {
            Category::factory()->createMany($data);
        }


      
    }
}
