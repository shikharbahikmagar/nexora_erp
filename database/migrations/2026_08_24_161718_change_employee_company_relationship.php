<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('company_user_id')
                ->after('id')
                ->constrained('company_users')
                ->cascadeOnDelete();

            $table->dropIndex(['company_id', 'branch_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['company_id']);
            $table->dropUnique(['user_id']);

            $table->dropColumn([
                'user_id',
                'company_id',
            ]);

            $table->index(['company_user_id', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->after('user_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->dropIndex(['company_user_id', 'branch_id']);
            $table->dropForeign(['company_user_id']);
            $table->dropColumn('company_user_id');
            $table->index(['company_id', 'branch_id']);
        });
    }
};
