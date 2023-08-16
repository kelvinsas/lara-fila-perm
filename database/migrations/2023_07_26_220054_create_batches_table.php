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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->date('date');
            $table->integer('status')->default(1);
            $table->bigInteger('discard')->nullable()->default(0);
            $table->bigInteger('approved')->nullable()->default(0);
            $table->bigInteger('defect')->nullable()->default(0);
            $table->foreignId('product_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('producer_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
