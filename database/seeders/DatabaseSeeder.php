<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Buat 10 authors
        $authors = Author::factory(10)->create();

        // Buat 50 books dengan random authors
        Book::factory(50)->create([
            'author_id' => fn() => $authors->random()->id,
        ]);

        User::create([
            'name' => 'jedan',
            'email' => 'jedan@jedan.com',
            'password' => 'jedan',
            'role' => 'admin',
        ]);

        // buat kategori dulu
        Category::factory(10)->create();

        // buat buku; BookFactory akan otomatis attach categories
        Book::factory(30)->create();
    }
}
