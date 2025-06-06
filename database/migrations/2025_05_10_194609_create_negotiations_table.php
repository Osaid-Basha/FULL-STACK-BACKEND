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
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
             $table->foreignId('negotiation_id')->nullable()->constrained()->onDelete('cascade');
               $table->string('phone')->nullable();
        $table->string('email')->nullable();
       $table->text('message')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
