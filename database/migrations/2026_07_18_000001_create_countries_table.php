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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso2', 2)->unique();
            $table->string('iso3', 3)->unique()->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('currency_code', 10)->nullable();
            $table->string('currency_name')->nullable();
            $table->string('region')->nullable();
            $table->string('capital')->nullable();
            
            // Cached indicator values from World Bank / REST Countries
            $table->double('gdp')->nullable(); // In USD
            $table->double('gdp_growth')->nullable(); // Annual %
            $table->double('inflation')->nullable(); // Annual %
            $table->bigInteger('population')->nullable();
            $table->double('exports')->nullable();
            $table->double('imports')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
