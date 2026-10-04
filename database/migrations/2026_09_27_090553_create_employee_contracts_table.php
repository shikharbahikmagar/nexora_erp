<?php

use App\Enums\EmployeeContractStatus;
use App\Enums\EmployeeContractType;
use App\Enums\SalaryType;
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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('contract_number')->unique();

            $table->string('contract_type')->define(EmployeeContractType::PERMANENT->value);

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->date('probation_start_date')->nullable();
            $table->date('probation_end_date')->nullable();

            $table->string('designation')->nullable();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('employment_status')->default(EmployeeContractStatus::ACTIVE->value);

            $table->decimal('salary', 15, 2);
            $table->string('salary_type')->default(SalaryType::MONTHLY->value);

            $table->decimal('working_hours_per_week', 5, 2)->nullable();

            $table->string('work_schedule')->nullable();

            $table->unsignedInteger('notice_period_days')->nullable();

            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
