<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'groceries',
                'description' => 'Regular grocery items.',
                'needed' => true,
                'slug' => \Illuminate\Support\Str::slug('groceries'),
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'transportation',
                'description' => 'Work and other essential activities based transportation.',
                'needed' => true,
                'slug' => \Illuminate\Support\Str::slug('transportation'),
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'medicines',
                'description' => 'Allergy related mostly and a few other items occasionally.',
                'needed' => true,
                'slug' => \Illuminate\Support\Str::slug('medicines'),
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'other',
                'description' => 'Whatever don\'t fall into any of the already existing categories.',
                'needed' => false,
                'slug' => \Illuminate\Support\Str::slug('other'),
                'created_at' => \Carbon\Carbon::now(),
            ],
        ];

        \App\Category::insert($data);
    }
}
