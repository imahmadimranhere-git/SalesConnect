<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distributor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->integer('visit_order')->default(1);
            $table->timestamps();

            $table->unique(['distributor_id', 'shop_id', 'day_of_week'], 'unique_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_assignments');
    }
};