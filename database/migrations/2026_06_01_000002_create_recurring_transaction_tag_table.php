<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_transaction_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recurring_transaction_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('recurring_transaction_id')->references('id')->on('recurring_transactions')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_transaction_tag');
    }
};
