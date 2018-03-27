<?php

use Phinx\Migration\AbstractMigration;

class CreatePlaylistsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'playlists', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string', array( 'limit' => 64 ));

        // timestamps
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $table->save();

        // create pivot table
        $pivotTable = $this->table( 'playlist_song', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $pivotTable->addColumn('playlist_id', 'integer');
        $pivotTable->addColumn('song_id', 'integer');

        $pivotTable->addIndex(array('playlist_id', 'song_id'));
        $pivotTable->addForeignKey('playlist_id', 'playlists', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $pivotTable->addForeignKey('song_id', 'songs', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));

        // timestamps
        $pivotTable->addColumn('created_at', 'datetime');
        $pivotTable->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $pivotTable->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $pivotTable->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'playlist_song' );
        $this->dropTable( 'playlists' );
    }
}
