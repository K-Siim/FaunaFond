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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to pets table
            $table->foreignId('pet_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // Reminder type: vaccine, medication, or veterinary visit
            $table->enum('type', ['vaktsiin', 'ravim', 'arstivisiit'])
                ->index();
            
            // Name of vaccine, medication, or clinic
            $table->string('name', 255);
            
            // Date and time of reminder
            $table->date('date')
                ->index();
            
            $table->time('time')
                ->default('09:00');
            
            // Track if notification has been sent
            $table->boolean('notification_sent')
                ->default(false)
                ->index();
            
            // Timestamps for audit trail
            $table->timestamps();
            
            // Add composite index for common queries
            $table->index(['pet_id', 'date']);
            $table->index(['pet_id', 'type']);
            $table->index(['date', 'notification_sent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};