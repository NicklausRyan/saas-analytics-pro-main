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
     */    public function up()
    {
        // Add 'session', 'bounce', 'session_start', and 'session_duration' as valid values in the stats table's name enum
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours', 'session', 'bounce', 'session_start', 'session_duration')");
        
        // Update the config file for stats types
        $types = config('stats.types');
        if (!in_array('session', $types)) {
            $types[] = 'session';
        }
        if (!in_array('bounce', $types)) {
            $types[] = 'bounce';
        }
        if (!in_array('session_start', $types)) {
            $types[] = 'session_start';
        }
        if (!in_array('session_duration', $types)) {
            $types[] = 'session_duration';
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */    public function down()
    {
        // Revert the enum to its original state
        \DB::statement("ALTER TABLE `stats` MODIFY COLUMN `name` ENUM('browser', 'os', 'device', 'visitors', 'pageviews', 'country', 'city', 'page', 'referrer', 'resolution', 'language', 'landing_page', 'event', 'campaign', 'continent', 'visitors_hours', 'pageviews_hours')");
        
        // Update the config file for stats types to remove our additions
        $types = config('stats.types');
        $types = array_diff($types, ['session', 'bounce', 'session_start', 'session_duration']);
    }
};
