<?php

use Phalcon\Mvc\Model;
class Artist_Event extends Model
{
    protected $artist_id;
    protected $event_id;

    public function initialize()
    {
        $this->belongsTo('artist_id', Artist::class, 'id',
         ['alias' => 'group']) ;

        $this->belongsTo('event_id', Event::class,'id',
        ['alias'=>'events']) ;
    }

}