<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateContentBlocksTable extends Migration
{
    public function up()
    {
        Schema::create($this->prefix.'content_blocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('content_id');

            $table->string('alias');
            $table->string('kind');
            $table->json('content')->nullable();

            $table->index('content_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->prefix.'content_blocks');
    }
}
