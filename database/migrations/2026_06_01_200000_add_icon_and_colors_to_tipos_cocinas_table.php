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
        Schema::table('tipos_cocinas', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('name');
            $table->string('color_primary')->nullable()->after('icon');
            $table->string('color_secondary')->nullable()->after('color_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_cocinas', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color_primary', 'color_secondary']);
        });
    }
};
