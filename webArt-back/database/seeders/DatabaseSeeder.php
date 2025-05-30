<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Payment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'Bielux',
            'name' => 'Gabriel',
            'last_name' => 'Mas Ballester',
            'email' => 'gabrielmasballester@gmail.com',
            'password' => bcrypt('12345678'), // Contraseña encriptada
            'role' => 'admin', // Puedes cambiar el rol a 'worker' o 'client' según sea necesario
            'email_verified_at' => now(), // Marca el correo como verificado
        ]);

        Product::factory()->count(5)->create([
            'name' => 'Prenda de prueba',
            'description' => 'This is a test product.',
            'price' => 1.00,
            'size' => 'M',
            'stock' => 10,
            'category' => 'camiseta',
            'URL01' => 'image/clothes/cami01/00.PNG',
            'URL02' => 'image/paintings/drop00/01.jpg',
            'URL03' => 'image/paintings/drop00/02.jpg',
            'URL04' => 'image/paintings/drop00/03.jpg',
        ]);
        Product::factory()->count(5)->create([
            'name' => 'Prenda de prueba',
            'description' => 'This is a test product.',
            'price' => 1.00,
            'size' => 'M',
            'stock' => 10,
            'category' => 'bolsa',
            'URL01' => 'image/clothes/cami01/00.PNG',
            'URL02' => 'image/paintings/drop00/01.jpg',
            'URL03' => 'image/paintings/drop00/02.jpg',
            'URL04' => 'image/paintings/drop00/03.jpg',
        ]);

        Gallery::factory()->create([
            'name' => 'Onada',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop00/00.jpg',
            'url02' => 'image/paintings/drop00/01.jpg',
            'url03' => 'image/paintings/drop00/02.jpg',
            'url04' => 'image/paintings/drop00/03.jpg',
        ]);
        Gallery::factory()->create([
            'name' => 'CapRoig',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop01/00.jpg',
            'url02' => 'image/paintings/drop01/01.jpg',
            'url03' => 'image/paintings/drop01/02.jpg',
            'url04' => 'image/paintings/drop01/03.jpg',
        ]);
        Gallery::factory()->create([
            'name' => 'Mediterrani',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop02/00.jpg',
            'url02' => 'image/paintings/drop02/01.jpg',
            'url03' => 'image/paintings/drop02/02.jpg',
            'url04' => 'image/paintings/drop02/03.jpg',
        ]);
        Gallery::factory()->create([
            'name' => 'Caliu',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop03/00.jpg',
            'url02' => 'image/paintings/drop03/01.jpg',
            'url03' => 'image/paintings/drop03/02.jpg',
            'url04' => 'image/paintings/drop03/03.jpg',
        ]);
        Gallery::factory()->create([
            'name' => 'Embat',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop04/00.jpg',
            'url02' => 'image/paintings/drop04/01.jpg',
            'url03' => 'image/paintings/drop04/02.jpg',
            'url04' => 'image/paintings/drop04/03.jpg',
        ]);
        Gallery::factory()->create([
            'name' => 'Tramuntana',
            'description' => 'This is a test gallery.',
            'url01' => 'image/paintings/drop05/00.jpg',
            'url02' => 'image/paintings/drop05/01.jpg',
            'url03' => 'image/paintings/drop05/02.jpg',
            'url04' => 'image/paintings/drop05/03.jpg',
        ]);
    }
}
