<?php

use Phalcon\Mvc\Model;
class User extends Model
{
    protected $id;
    protected $name;
    protected $email;
    public $token ;
    protected $password;

    public function initialize()
    {
        $this->hasMany('id', Visitor::class, 'v_user_id', ['alias' => 'visitors']);
        $this->hasManyToMany(
            'id',
            Visitor::class,
            'v_user_id',
            'v_event_id',
            Event::class,
            'id',
            ['alias' => 'events']
        );
    }


}