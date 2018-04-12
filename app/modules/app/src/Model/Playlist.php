<?php
namespace App\Model;

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
        return $this->belongsToMany('App\\Model\\Song')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\\Model\\User'); //, 'user_id');
    }
}