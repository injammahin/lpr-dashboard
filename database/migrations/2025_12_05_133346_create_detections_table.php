<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detections', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            
            $table->string('plate');
            $table->timestamp('ts'); 
            $table->string('date_str');
            $table->string('time_str');
            
            $table->string('file_path')->unique();
            $table->bigInteger('file_size')->nullable();
            
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detections');
    }
};
