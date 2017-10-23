<?php

namespace App;

class UserView extends BaseModel
{
    protected $url;
    
    protected $readFrom = 'usersView';
    
    public function __construct()
    {
        $this->url = env('USER_CENTER_HTTP_REQUEST_URL');
        parent::__construct();
    }

    public function getThumbnailAvatarAttribute($val)
    {
        return $val ? $this->url.$val : '';
    }

    public function getAvatarAttribute($val)
    {
        return $val ? $this->url.$val : '';
    }


}
