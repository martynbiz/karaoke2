<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'is_valid'
    );

    public function songs()
    {
        return $this->belongsToMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Song')->withTimestamps(); //, 'user_id');
    }
}
