<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema;

class RemovePersonalInfoColumnsFromLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Check if columns exist before dropping for safety
            if (Schema::hasColumn('leave_requests', 'first_name')) {
                $table->dropColumn(['first_name', 'last_name', 'department', 'designation']);
            }
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
        });
    }
}
