<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();

            $table->string('image', 255)->nullable();
            $table->string('banner', 255)->nullable();

            $table->string('meta_title', 255)->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();

            $table->boolean('noindex')->default(0);
            $table->boolean('nofollow')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
