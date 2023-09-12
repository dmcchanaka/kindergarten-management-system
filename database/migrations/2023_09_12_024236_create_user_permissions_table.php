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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id('up_id');
            $table->unsignedBigInteger('u_tp_id')->nullable();
            $table->foreign('u_tp_id')->references('u_tp_id')->on('user_type');
            $table->unsignedBigInteger('p_id')->nullable();
            $table->foreign('p_id')->references('p_id')->on('permissions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
