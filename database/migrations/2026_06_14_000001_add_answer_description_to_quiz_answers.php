<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quiz_answers')) {
            return;
        }

        Schema::table('quiz_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_answers', 'answer_description')) {
                $table->text('answer_description')->nullable()->after('answer_image');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('quiz_answers')) {
            return;
        }

        Schema::table('quiz_answers', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_answers', 'answer_description')) {
                $table->dropColumn('answer_description');
            }
        });
    }
};
