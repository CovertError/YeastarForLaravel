<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYeastarTokensTable extends Migration
{
    public function up()
    {
        Schema::create('yeastar_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('access_token');
            $table->timestamp('access_token_expire_time');
            $table->string('refresh_token');
            $table->timestamp('refresh_token_expire_time');

        });
    }

    public function down()
    {
        Schema::dropIfExists('yeastar_tokens');
    }
}