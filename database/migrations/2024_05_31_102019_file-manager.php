<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Niyama\FileManager\Models\FileManagerModel;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_manager', function (Blueprint $table) {
            $table->id();

            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->enum('type', FileManagerModel::TYPE);
            $table->unsignedSmallInteger('position');
            $table->string('name');
            $table->string('original_name');
            $table->string('path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_manager');
    }
};
