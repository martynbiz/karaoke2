<?php
namespace MartynBiz\Slim\Module\Karaoke\Model;

class User extends \MartynBiz\Slim\Module\Auth\Model\User
{
    public function playlists()
    {
        return $this->hasMany('MartynBiz\\Slim\\Module\\Karaoke\\Model\\Playlist'); //, 'user_id');
    }
}
