<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('brand');
            $table->timestamps();
        });

        // for ($i = 0; $i < 50000; $i++) {
        //     \App\Models\Vehicle::create([
        //         'name' => 'Vehicle ' . $i,
        //         'brand' => 'Brand ' . $i,
        //     ]);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
