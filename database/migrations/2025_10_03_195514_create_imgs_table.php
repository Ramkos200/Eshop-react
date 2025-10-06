<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('imgs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('disk')->default('public');

            // Polymorphic relationships
            $table->morphs('imageable');

            // Image type: main, gallery, receipt, variant
            $table->enum('type', ['main', 'gallery', 'receipt', 'variant'])->default('gallery');

            // Order for sorting gallery images
            $table->integer('order')->default(0);

            $table->text('alt_text')->nullable();
            $table->text('caption')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['imageable_type', 'imageable_id', 'type']);
            $table->index(['type', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('imgs');
    }
};