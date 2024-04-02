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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time'); // Changed to dateTime
            $table->time('time_in'); // Changed to time
            $table->time('time_out'); // Changed to time
            $table->bigInteger('user_id')->unsigned()->constrained()->onDelete('cascade'); 
            $table->string('name'); // Name field added
            $table->string('description');
            $table->string('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs'); // Changed table name to logs
    }
};
