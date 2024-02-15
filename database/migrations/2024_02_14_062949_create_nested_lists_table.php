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
        Schema::create('nested_list', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_edited')->default(config('constants.STATUSES.DISABLED'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nested_list');
    }
};
