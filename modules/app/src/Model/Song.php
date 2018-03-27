<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use \App\Traits\Meta;

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'path',
        'artist_id',
        'has_meta',
        'language_id',
    );

    public function artist()
    {
        return $this->belongsTo('App\\Model\\Artist'); //, 'user_id');
    }

    public function language()
    {
        return $this->belongsTo('App\\Model\\Language'); //, 'user_id');
    }

    public function meta()
    {
        return $this->hasMany('App\\Model\\SongMeta'); //, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\\Model\\Tag')->withTimestamps(); //, 'user_id');
    }

    public function playlists()
    {
        return $this->belongsToMany('App\\Model\\Playlist')->withTimestamps();;
    }
}
