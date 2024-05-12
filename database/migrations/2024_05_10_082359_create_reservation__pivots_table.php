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
        Schema::create('reservation__pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constraiend('users')->onDelete('cascade');
            $table->foreignId('book_id')->constraiend('books')->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->enum('status',['Borrowed','Returned','Applay'])->default('Applay');
            $table->softDeletes(); // This adds the 'deleted_at' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation__pivots');
    }
};
