<?php
namespace App\Model;

class User extends \MartynBiz\Slim\Module\Auth\Model\User
{
    public function playlists()
    {
        return $this->hasMany('App\\Model\\Playlist'); //, 'user_id');
    }
}
