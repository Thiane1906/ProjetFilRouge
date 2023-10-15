<?php

use App\Models\CoursClasse;
use App\Models\Salle;
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
        Schema::create('session_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CoursClasse::class);
            $table->date('date');
            $table->string('hr_debut');
            $table->string('hr_fin');
            $table->string('duree');
            $table->foreignIdFor(Salle::class);
            $table->boolean('etat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_cours');
    }
};
