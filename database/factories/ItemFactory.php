<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar kategori untuk variasi data
        $categories = [
            'Electronics',
            'Books',
            'Clothing',
            'Home Appliances',
            'Sports',
            'Toys',
            'Furniture',
            'Office',
            'Tools',
            'Food'
        ];

        return [
            'name' => $this->faker->randomElement($categories) . ' ' .
                $this->faker->word . ' ' .
                $this->faker->randomElement(['Pro', 'Premium', 'Edition', 'Series', 'X']),
            'description' => $this->faker->paragraph(3),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * State untuk item dengan deskripsi panjang
     */
    public function longDescription(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'description' => $this->faker->paragraph(10),
            ];
        });
    }

    /**
     * State untuk item milik user tertentu
     */
    public function forUser(int $userId): static
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }

    /**
     * State untuk item baru (baru dibuat)
     */
    public function recentlyCreated(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
    }
}
