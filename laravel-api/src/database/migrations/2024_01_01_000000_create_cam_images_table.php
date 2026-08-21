<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cam_images', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 100)->index();
            $table->string('filename');
            $table->string('path');
            $table->unsignedInteger('size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cam_images');
    }
};
