<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->isbn13(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'author_id' => Author::factory(),
            'publisher' => fake()->company(),
            'year' => fake()->numberBetween(1950, 2024),
            'status' => fake()->randomElement(['available', 'borrowed']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Book $book) {
            // pastikan ada kategori minimal 5
            if (Category::count() < 5) {
                Category::factory(10)->create();
            }

            // attach 1-3 random categories
            $ids = Category::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
            $book->categories()->attach($ids);
        });
    }
}
