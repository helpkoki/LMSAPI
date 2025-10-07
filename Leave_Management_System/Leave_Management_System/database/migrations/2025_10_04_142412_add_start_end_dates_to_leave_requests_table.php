<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->date('start_date')->nullable()->after('leave_type');
        $table->date('end_date')->nullable()->after('start_date');
        // Optionally, if you still have and want to remove 'dates_requested':
        // $table->dropColumn('dates_requested');
    });
}

public function down()
{
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->dropColumn(['start_date', 'end_date']);
        // Optionally re-add 'dates_requested' if dropped above.
    });
}

};
