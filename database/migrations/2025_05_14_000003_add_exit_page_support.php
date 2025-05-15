<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddExitPageSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the stats.name enum to include exit_page
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours', 'session', 'bounce', 'session_start', 'session_duration', 'exit_page')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the stats.name enum back to exclude exit_page
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours', 'session', 'bounce', 'session_start', 'session_duration')");
    }
}