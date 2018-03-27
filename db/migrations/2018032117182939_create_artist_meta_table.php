<?php

use Phinx\Migration\AbstractMigration;

class CreateArtistMetaTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'artist_meta', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string');
        $table->addColumn('value', 'text');
        $table->addColumn('artist_id', 'integer');

        // timestamps
        $table->addColumn('created_at', 'datetime' );
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ) );
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ) );

        //
        $table->addForeignKey('artist_id', 'artists', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $table->addIndex('artist_id');

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'artist_meta' );
    }
}
