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
            $table->id();
            $table->string('departmentName');
            $table->string('departmentDescription');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subjectName');
            $table->string('subjectCode');
            $table->string('sectionCode');
            $table->text('subjectDescription')->nullable();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); 
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('role');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birthdate')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('college_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
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