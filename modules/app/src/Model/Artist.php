<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use App\Traits\Meta;

    /**
    * @var array
    */
    protected $fillable = array(
        'name',
        'has_meta',
    );

    public function songs()
    {
        return $this->hasMany('App\\Model\\Song');
    }

    public function albums()
    {
        return $this->hasMany('App\\Model\\Album');
    }

    public function meta()
    {
        return $this->hasMany('App\\Model\\ArtistMeta');
    }

    public function tags()
    {
        return $this->belongsToMany('App\\Model\\Tag')->withTimestamps();
    }
}
