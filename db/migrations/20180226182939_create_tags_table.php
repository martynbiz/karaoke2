<?php

use Phinx\Migration\AbstractMigration;

class CreateTagsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'tags', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string', array( 'limit' => 64 ));
        $table->addColumn('is_valid', 'boolean', array( 'default' => 1 ));

        // timestamps
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $table->save();


        // create pivot table
        $songPivotTable = $this->table( 'song_tag', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $songPivotTable->addColumn('tag_id', 'integer');
        $songPivotTable->addColumn('song_id', 'integer');

        $songPivotTable->addIndex(array('tag_id', 'song_id'));
        $songPivotTable->addForeignKey('tag_id', 'tags', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $songPivotTable->addForeignKey('song_id', 'songs', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));

        // timestamps
        $songPivotTable->addColumn('created_at', 'datetime');
        $songPivotTable->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $songPivotTable->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $songPivotTable->save();


        // create pivot table
        $artistPivotTable = $this->table( 'artist_tag', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $artistPivotTable->addColumn('tag_id', 'integer');
        $artistPivotTable->addColumn('artist_id', 'integer');

        $artistPivotTable->addIndex(array('tag_id', 'artist_id'));
        $artistPivotTable->addForeignKey('tag_id', 'tags', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $artistPivotTable->addForeignKey('artist_id', 'artists', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));

        // timestamps
        $artistPivotTable->addColumn('created_at', 'datetime');
        $artistPivotTable->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $artistPivotTable->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $artistPivotTable->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'song_tag' );
        $this->dropTable( 'artist_tag' );
        $this->dropTable( 'tags' );
    }
}
