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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->integer("thumbnail_id");
            $table->integer("owner_id");
            $table->integer("length")->default(0);
            $table->boolean("public");
            $table->string("youtube_id")->nullable(true);
            $table->boolean("processed")->default(false);
            $table->boolean("terminated")->default(0);
            $table->timestamp('terminated_at')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
