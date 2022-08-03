<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Maker;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maker_models', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Maker::class)->references('id')->on('makers');
            $table->string('name');
            $table->year('year');
            $table->timestamps();

            $table->unique(['maker_id', 'name', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maker_models');
    }
};
