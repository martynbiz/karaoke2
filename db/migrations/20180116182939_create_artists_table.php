<?php

use Phinx\Migration\AbstractMigration;

class CreateArtistsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'artists', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string', array( 'limit' => 64 ));
        $table->addColumn('has_meta', 'boolean', array( 'default' => 0 ));

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
        $this->dropTable( 'artists' );
    }
}
