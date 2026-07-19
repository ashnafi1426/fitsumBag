<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->string('facebook_url')->nullable()->after('business_hours');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('location')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'instagram_url', 'twitter_url', 'location']);
        });
    }
};
