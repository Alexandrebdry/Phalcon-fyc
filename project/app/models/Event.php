<?php

use Phalcon\Mvc\Model;
class Event extends Model
{

    protected $id;
    protected $date;
    protected $sells_date;
    protected $name;
    protected $place_id;

    public function initialize() {

        $this->hasManyToMany(
            'id', Artist_Event::class , 'event_id',
            'artist_id', Artist::class , 'id',
            ['reusable' => true , 'alias' => 'group']
        ) ;
        $this->hasMany('id', Artist_Event::class, 'event_id',
            ['reusable' => true, 'alias' => 'group']
        );

        $this->belongsTo('place_id', Place::class, 'id',
        ['alias'=>'place']) ;

        $this->hasMany('id', Visitor::class, 'v_event_id', [
            'reusable' => true ,
            'alias' => 'UserVisitors'
        ]);

        $this->hasManyToMany('id', Visitor::class,
            'v_event_id', 'v_user_id', User::class,'id', [
            'reusable' => true ,
            'alias' => 'visitors'
        ]);
    }


}