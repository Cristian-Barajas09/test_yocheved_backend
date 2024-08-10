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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment("Student name");
            $table->string("middle_name")->nullable()->comment("Student middle name");
            $table->string("last_name")->comment("Student last name");
            $table->string("email")->unique()->comment("Student email");
            $table->string("birth_date")->comment("Student birth date");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
