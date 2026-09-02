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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('company_user_id')
                ->unique()
                ->constrained('company_users')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Employee identification
            $table->string('employee_code')->unique();

            // Personal information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();

            $table->string('profile_photo')->nullable();

            // Contact
            $table->string('personal_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();

            // Employment
            $table->date('joining_date');

            $table->string('employment_type');
            $table->string('employment_status')->default('active');

            $table->date('probation_end_date')->nullable();
            $table->date('confirmation_date')->nullable();

            $table->date('resignation_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_id');
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
