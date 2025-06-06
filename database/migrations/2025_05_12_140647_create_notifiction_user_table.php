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
        Schema::create('notification_user', function (Blueprint $table) {

    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
    $table->boolean('is_read')->default(false);
    $table->timestamp('read_at')->nullable();
    $table->string('status')->nullable();
     $table->primary(['user_id', 'notification_id']);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_user');
    }
};
