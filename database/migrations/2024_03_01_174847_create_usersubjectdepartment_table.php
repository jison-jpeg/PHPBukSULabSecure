<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // Likely should be unsignedBigInteger
            $table->string('departmentName');
            $table->string('departmentDescription');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Likely should be unsignedBigInteger
            $table->string('subjectName');
            $table->string('subjectCode');
            $table->text('subjectDescription')->nullable();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); 
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Likely should be unsignedBigInteger
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('role');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birthdate');
            $table->string('phone')->nullable();
            $table->foreignId('college_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('department_id')->nullable()->constrained('departments')->onDelete('set null'); // Updated data type
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('departments');
    }
};