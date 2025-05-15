<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add 'session_start' and 'session_duration' as valid values in the stats table's name enum
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours', 'session', 'bounce', 'session_start', 'session_duration')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the enum to its original state with just session and bounce added
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours', 'session', 'bounce')");
    }
};
