<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

use Illuminate\Database\Eloquent\Model;

use MartynBiz\Slim\Module\Karaoke\Traits\GetMetaByName;

class Song extends Model
{
    use GetMetaByName;

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
        return $this->belongsTo('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Artist'); //, 'user_id');
    }

    public function language()
    {
        return $this->belongsTo('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Language'); //, 'user_id');
    }

    public function meta()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\SongMeta'); //, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Tag')->withTimestamps(); //, 'user_id');
    }

    public function playlists()
    {
        return $this->belongsToMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Playlist')->withTimestamps();;
    }
}
