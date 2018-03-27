<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SongMeta extends Model
{
    /**
     * @var string
     */
    protected $table = 'song_meta';

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'value',
        'song_id',
    );

    public function songs()
    {
        return $this->belongsToMany('App\\Model\\Song')->withTimestamps(); //, 'user_id');
    }
}
