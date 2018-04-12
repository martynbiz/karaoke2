<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArtistMeta extends Model
{
    /**
     * @var string
     */
    protected $table = 'artist_meta';

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'value',
        'artist_id',
    );

    public function songs()
    {
        return $this->belongsToMany('App\\Model\\Song')->withTimestamps(); //, 'user_id');
    }
}
