<?php

use App\Models\Classe;
use App\Models\Cours;
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
        Schema::create('cours_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cours::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Classe::class)->constrained()->cascadeOnDelete();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_classes');
    }
};
