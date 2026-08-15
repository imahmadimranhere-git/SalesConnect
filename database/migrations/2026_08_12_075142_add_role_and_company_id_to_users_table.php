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
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('shopkeeper')->after('id');
        $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
        $table->string('phone')->nullable();
        $table->string('status')->default('active');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn(['role', 'company_id', 'phone', 'status']);
    });
}
};
