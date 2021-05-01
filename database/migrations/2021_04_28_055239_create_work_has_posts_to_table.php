<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkHasPostsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_has_posts', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
            $table->bigInteger('work_id')->unsigned();
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('cascade');
            $table->timestamps();
            $table->primary(['post_id', 'work_id'], 'work_posts_post_id_work_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_has_posts');
    }
}
