<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhance workshop_modules table
        if (Schema::hasTable('workshop_modules')) {
            Schema::table('workshop_modules', function (Blueprint $table) {
                if (! Schema::hasColumn('workshop_modules', 'workshop_id')) {
                    $table->foreignId('workshop_id')->nullable()->constrained('workshops')->cascadeOnDelete();
                }
                if (! Schema::hasColumn('workshop_modules', 'module_id')) {
                    $table->foreignId('module_id')->nullable()->constrained('modules')->cascadeOnDelete();
                }
                if (! Schema::hasColumn('workshop_modules', 'sequence')) {
                    $table->unsignedInteger('sequence')->default(1);
                }
            });
        }

        // 2. Enhance workshop_teachers table
        if (Schema::hasTable('workshop_teachers')) {
            Schema::table('workshop_teachers', function (Blueprint $table) {
                if (! Schema::hasColumn('workshop_teachers', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                }
            });

            // Backfill user_id from teacher_id
            DB::table('workshop_teachers')->whereNull('user_id')->update([
                'user_id' => DB::raw('teacher_id'),
            ]);
        }

        // 3. Enhance module_progress table
        if (Schema::hasTable('module_progress')) {
            Schema::table('module_progress', function (Blueprint $table) {
                if (! Schema::hasColumn('module_progress', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                }
                if (! Schema::hasColumn('module_progress', 'progress')) {
                    $table->unsignedInteger('progress')->default(0);
                }
            });

            // Backfill user_id and progress
            DB::table('module_progress')->whereNull('user_id')->update([
                'user_id' => DB::raw('teacher_id'),
            ]);
            DB::table('module_progress')->where('status', 'completed')->update([
                'progress' => 100,
            ]);
            DB::table('module_progress')->where('status', 'in_progress')->where('progress', 0)->update([
                'progress' => 50,
            ]);
        }

        // 4. Enhance student_results table
        if (Schema::hasTable('student_results')) {
            Schema::table('student_results', function (Blueprint $table) {
                if (! Schema::hasColumn('student_results', 'total_questions')) {
                    $table->unsignedInteger('total_questions')->default(100);
                }
                if (! Schema::hasColumn('student_results', 'status')) {
                    $table->string('status')->nullable();
                }
            });

            // Backfill status from result
            DB::table('student_results')->whereNull('status')->where('result', 'passed')->update([
                'status' => 'Passed',
            ]);
            DB::table('student_results')->whereNull('status')->where('result', 'failed')->update([
                'status' => 'Failed',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('workshop_modules')) {
            Schema::table('workshop_modules', function (Blueprint $table) {
                $table->dropForeign(['workshop_id']);
                $table->dropForeign(['module_id']);
                $table->dropColumn(['workshop_id', 'module_id', 'sequence']);
            });
        }

        if (Schema::hasTable('workshop_teachers')) {
            Schema::table('workshop_teachers', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('module_progress')) {
            Schema::table('module_progress', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id', 'progress']);
            });
        }

        if (Schema::hasTable('student_results')) {
            Schema::table('student_results', function (Blueprint $table) {
                $table->dropColumn(['total_questions', 'status']);
            });
        }
    }
};
