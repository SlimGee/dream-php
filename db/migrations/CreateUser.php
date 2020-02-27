<?php

namespace Db\Migration;

use Dream\Database\Migration\Base as Migration;
use Dream\Database\Migration\Table;

/**
 *
 */
class CreateUser extends Migration
{
    public function change()
    {
        $this->create_table('gogzs', function (Table $table)
        {
            $table->int('id')->primary_key();
            $table->string('name')->null(false);
            $table->bool('loged')->default(1);
            $table->date('dob');
            $table->int('user_id')->references('users');
            $table->decimal('salaray');
            $table->time('arrival');
            $table->timestamps();
            return $table;
        });
    }
}
