<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Key;
use App\Models\Vehicle;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vehicle::class)->references('id')->on('vehicles');
            $table->foreignIdFor(Key::class)->references('id')->on('keys');
            $table->timestamps();

            $table->unique(['vehicle_id', 'key_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_keys');
    }
};
