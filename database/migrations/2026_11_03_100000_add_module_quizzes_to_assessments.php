
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add module_id only if it does not already exist.
        // This handles the partial changes from the failed migration.
        if (!Schema::hasColumn('assessments', 'module_id')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->foreignId('module_id')
                    ->nullable()
                    ->after('workshop_id')
                    ->constrained('modules')
                    ->cascadeOnDelete();
            });
        }

        // Create the replacement index BEFORE dropping the old one.
        // The new index also supports the workshop_id foreign key.
        $indexes = collect(DB::select('SHOW INDEX FROM assessments'))
            ->pluck('Key_name');

        if (!$indexes->contains('assessments_workshop_id_module_id_unique')) {
            DB::statement(
                'ALTER TABLE assessments ADD UNIQUE INDEX assessments_workshop_id_module_id_unique (workshop_id, module_id)'
            );
        }

        // Drop the old unique index only after the replacement exists.
        $indexes = collect(DB::select('SHOW INDEX FROM assessments'))
            ->pluck('Key_name');

        if ($indexes->contains('assessments_workshop_id_unique')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropUnique('assessments_workshop_id_unique');
            });
        }

        foreach (DB::table('assessments')->whereNull('module_id')->get(['id', 'workshop_id']) as $assessment) {
            $moduleId = DB::table('workshop_modules')
                ->where('workshop_id', $assessment->workshop_id)
                ->orderByDesc('sequence')
                ->value('module_id');

            if (!$moduleId) {
                $courseId = DB::table('workshops')
                    ->where('id', $assessment->workshop_id)
                    ->value('course_id');

                $moduleId = DB::table('modules')
                    ->where('course_id', $courseId)
                    ->orderByDesc('sequence')
                    ->value('id');
            }

            if ($moduleId) {
                DB::table('assessments')
                    ->where('id', $assessment->id)
                    ->update(['module_id' => $moduleId]);

                DB::table('module_progress')
                    ->where('workshop_id', $assessment->workshop_id)
                    ->where('module_id', $moduleId)
                    ->where('status', 'completed')
                    ->update([
                        'progress' => 75,
                        'status' => 'in_progress',
                        'completed_at' => null,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropUnique('assessments_workshop_id_module_id_unique');
            $table->dropConstrainedForeignId('module_id');
            $table->unique('workshop_id');
        });
    }
};
