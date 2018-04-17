<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

use Illuminate\Database\Eloquent\Model;

use MartynBiz\Slim\Module\Karaoke\Traits\GetMetaByName;

class Artist extends Model
{
    use GetMetaByName;

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'has_meta',
    );

    public function songs()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Song');
    }

    public function albums()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Album');
    }

    public function meta()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\ArtistMeta');
    }

    public function tags()
    {
        return $this->belongsToMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Tag')->withTimestamps();
    }
}
