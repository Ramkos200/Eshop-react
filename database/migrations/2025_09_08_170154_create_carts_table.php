<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_token')->nullable()->unique();
            $table->string('status')->default('active'); // active, abandoned, converted
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['guest_token', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};