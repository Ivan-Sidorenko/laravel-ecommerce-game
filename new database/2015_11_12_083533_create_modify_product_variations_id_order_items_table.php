<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifyProductVariationsIdOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function($t) {
          $t->string('product_variation_id');
          $t->dropColumn('banner');
          $t->dropColumn('slug');
          $t->dropColumn('is_featured');
          $t->dropColumn('is_active');
          $t->dropColumn('mixability');
          $t->dropColumn('tf_title');
          $t->dropColumn('tf_text');
          $t->dropColumn('tf_image');
          $t->dropColumn('tf_reviews_enabled');
          $t->dropColumn('vfm_score');
          $t->dropColumn('vfm_text');
          $t->dropColumn('hiw_ingredients');
          $t->dropColumn('hiw_active_ingredient_count');
          $t->dropColumn('hiw_text');
          $t->dropColumn('benefit_1');
          $t->dropColumn('benefit_2');
          $t->dropColumn('benefit_3');
          $t->string('subsku');
          $t->string('size')->nullable();
          $t->integer('flavor_id')->nullable();
          $t->integer('servings')->nullable();
          $t->integer('weight')->nullable();
          $t->integer('width')->nullable();
          $t->integer('length')->nullable();
          $t->integer('depth')->nullable();
          $t->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function($table) {
            $table->dropColumn('product_variation_id');
            $table->string('banner')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->string('banner')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('mixability')->nullable();
            $table->string('tf_title')->nullable();
            $table->string('tf_text')->nullable();
            $table->string('tf_image')->nullable();
            $table->boolean('tf_reviews_enabled')->nullable();
            $table->integer('vfm_score')->nullable();
            $table->string('vfm_text')->nullable();
            $table->string('hiw_ingredients')->nullable();
            $table->integer('hiw_active_ingredient_count')->nullable();
            $table->longtext('hiw_text')->nullable();
            $table->string('benefit_1')->nullable();
            $table->string('benefit_2')->nullable();
            $table->string('benefit_3')->nullable();
            $table->dropColumn('subsku');
            $table->dropColumn('size');
            $table->dropColumn('flavor_id');
            $table->dropColumn('servings');
            $table->dropColumn('weight');
            $table->dropColumn('width');
            $table->dropColumn('length');
            $table->dropColumn('depth');
            $table->dropColumn('image');
        });
    }
}
