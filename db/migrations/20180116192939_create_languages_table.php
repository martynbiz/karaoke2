<?php

use Phinx\Migration\AbstractMigration;

class CreateLanguagesTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'languages', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string', array( 'limit' => 64 ));

        // timestamps
        $table->addColumn('created_at', 'datetime' );
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ) );
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ) );

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'languages' );
    }
}
