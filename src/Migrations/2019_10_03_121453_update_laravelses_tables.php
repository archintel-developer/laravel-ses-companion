<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLaravelsesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laravel_ses_email_bounces', function (Blueprint $table) {
            $table->string('client_id')->nullable()->after('email');
            $table->foreign('client_id')->references('client_uuid')->on('clients');
        });
        Schema::table('laravel_ses_email_complaints', function (Blueprint $table) {
            $table->string('client_id')->nullable()->after('email');
            $table->foreign('client_id')->references('client_uuid')->on('clients');
        });
        Schema::table('laravel_ses_email_opens', function (Blueprint $table) {
            $table->string('client_id')->nullable()->after('url');
            $table->foreign('client_id')->references('client_uuid')->on('clients');
        });
        Schema::table('laravel_ses_sent_emails', function (Blueprint $table) {
            $table->string('client_id')->nullable()->after('email');
            $table->foreign('client_id')->references('client_uuid')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laravel_ses_email_bounces', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
        Schema::table('laravel_ses_email_complaints', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
        Schema::table('laravel_ses_email_opens', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
        Schema::table('laravel_ses_sent_emails', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
    }
}
