<?php

use Phinx\Migration\AbstractMigration;

class CreateSongMetaTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'song_meta', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string');
        $table->addColumn('value', 'text');
        $table->addColumn('song_id', 'integer');

        // timestamps
        $table->addColumn('created_at', 'datetime' );
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ) );
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ) );

        //
        $table->addForeignKey('song_id', 'songs', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $table->addIndex('song_id');

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'song_meta' );
    }
}
