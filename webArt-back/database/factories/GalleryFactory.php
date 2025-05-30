<?php
namespace Database\Factories;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory{
    protected $model = Gallery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->word(),
            "description" => $this->faker->sentence(),
            "url01" => $this->faker->imageUrl(),
            "url02" => $this->faker->imageUrl(),
            "url03" => $this->faker->imageUrl(),
            "url04" => $this->faker->imageUrl(),
            ];
    }
}