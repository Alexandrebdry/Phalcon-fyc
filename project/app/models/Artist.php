<?php

use Phalcon\Mvc\Model;

class Artist extends Model
{
    public $id ;
    public $name ;
    public $style ;

    public function initialize()
    {
        $this->hasMany('id', Artist_Event::class, 'artist_id') ;
        $this->hasManyToMany(
            'id', Artist_Event::class ,  'artist_id',
            'event_id', Event::class , 'id',
            ['reusable' => true , 'alias' => 'group']
        );

    }


}