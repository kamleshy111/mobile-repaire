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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->defolut(0);
            $table->unsignedBigInteger('user_id')->defolut(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_contact')->nullable();
            $table->string('device_model')->nullable();
            $table->string('issue')->nullable();
            $table->text('issue_description')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();
            $table->string('patern_lock')->nullable();
            $table->timestamp('date_time')->nullable();
            $table->timestamp('deliver_date')->nullable();
            $table->decimal('received_amount', 8, 2);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
