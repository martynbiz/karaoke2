<?php
namespace App\Model;

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
        return $this->hasMany('App\\Model\\Song'); //, 'user_id');
    }
}
