<?php

use App\Models\SessionCours;
use App\Models\User;
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
        Schema::create('abscences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('motif');
            $table->boolean('justifié')->default(true);
            $table->foreignIdFor(SessionCours::class);
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abscences');
    }
};
