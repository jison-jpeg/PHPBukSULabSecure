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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->constrained()->onDelete('cascade');
            $table->foreignId('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->bigInteger('subject_id')->unsigned()->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('time_attended')->nullable();
            $table->string('percentage')->default('0');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
