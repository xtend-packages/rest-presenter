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
        Schema::create('webhook_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->string('target_url');
            $table->string('subscribable_type')->nullable();
            $table->unsignedBigInteger('subscribable_id')->nullable();
            $table->string('secret')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['subscribable_type', 'subscribable_id']);
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_subscriptions');
    }
};
