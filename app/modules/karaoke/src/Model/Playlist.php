<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
    );

    public function songs()
    {
        return $this->belongsToMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Song')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('MartynBiz\\Slim\\Module\\Karaoke\\Model\\User'); //, 'user_id');
    }
}
