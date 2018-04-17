<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
    );

    public function songs()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Song'); //, 'user_id');
    }
}
