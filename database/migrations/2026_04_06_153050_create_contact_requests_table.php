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
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('request_type', ['booking', 'general', 'collaboration', 'other'])->default('general');
            $table->enum('status', ['new', 'contacted', 'completed', 'archived'])->default('new');
            $table->string('preferred_language')->default('ru');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('request_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
