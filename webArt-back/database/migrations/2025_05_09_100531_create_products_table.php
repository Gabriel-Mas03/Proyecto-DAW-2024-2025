<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade'); // Creador del producto
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('size')->nullable();
            $table->integer('stock')->default(0);
            $table->string('category')->nullable();
            $table->string('URL01')->nullable(); // ruta o nombre de imagen
            $table->string('URL02')->nullable(); // ruta o nombre de imagen
            $table->string('URL03')->nullable(); // ruta o nombre de imagen
            $table->string('URL04')->nullable(); // ruta o nombre de imagen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

