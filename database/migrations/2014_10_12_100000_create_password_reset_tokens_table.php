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
        Schema::defaultStringLength(191);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->unique(); // Making email unique, but not primary
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            
            // Define email as primary key separately
            $table->primary('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
