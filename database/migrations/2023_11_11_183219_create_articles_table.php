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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Source::class)->constrained();
            $table->string('load_resource')->index();
            $table->string('author')->fulltext()->nullable();
            $table->string('title')->fulltext();
            $table->text('description')->nullable();
            $table->longText('content')->fulltext();
            $table->string('url')->nullable();
            $table->text('url_to_image')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
