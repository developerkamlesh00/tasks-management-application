<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title',250)->nullable(false);
            $table->string('description')->nullable(false);
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('estimated_deadline')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('workers_visibility')->default(false);
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('manager_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
