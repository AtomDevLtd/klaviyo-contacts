<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKlaviyoSyncDatetimeToContactListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_lists', function (Blueprint $table) {
            $table->timestamp('klaviyo_sync_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_lists', function (Blueprint $table) {
            $table->dropColumn('klaviyo_sync_datetime');
        });
    }
}
