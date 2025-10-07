<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('leave_requests', 'dates_requested')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropColumn('dates_requested');
            });
        }
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Recreate the column if rolling back
            $table->date('dates_requested')->nullable();
        });
    }
};
