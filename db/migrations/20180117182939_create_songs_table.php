<?php

use Phinx\Migration\AbstractMigration;

class CreateSongsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'songs', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string', array( 'limit' => 128 ));
        $table->addColumn('path', 'string');
        $table->addColumn('total_requests', 'integer', array( 'default' => 0 ));
        $table->addColumn('artist_id', 'integer', array( 'null' => true ));
        $table->addColumn('language_id', 'integer', array( 'null' => true ));
        $table->addColumn('has_meta', 'boolean', array( 'default' => 0 ));

        // timestamps
        $table->addColumn('created_at', 'datetime' );
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ) );
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ) );

        //
        $table->addForeignKey('artist_id', 'artists', 'id', array('delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'));
        $table->addIndex('artist_id');
        $table->addForeignKey('language_id', 'languages', 'id', array('delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'));
        $table->addIndex('language_id');

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'songs' );
    }
}
