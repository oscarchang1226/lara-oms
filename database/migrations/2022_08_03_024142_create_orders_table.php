<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\VehicleKey;
use App\Models\Technician;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VehicleKey::class)->references('id')->on('vehicle_keys');
            $table->foreignIdFor(Technician::class)->references('id')->on('technicians');
            $table->timestamps();

            $table->unique(['vehicle_key_id', 'technician_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
