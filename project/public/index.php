<?php

declare(strict_types=1);

use Phalcon\Di\FactoryDefault ;
use Phalcon\Mvc\Micro ;

use Phalcon\Http\Response ;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    $di = new FactoryDefault();

    include APP_PATH . '/config/services.php';
    include APP_PATH . '/config/router.php';

    $config = $di->getConfig();

    include APP_PATH . '/config/loader.php';
    $micro = new Micro() ;

    $config = $di->getConfig();
    $micro->setDI($di) ;

    $micro->post('/api/login',function() use($micro){
       try {
           include APP_PATH . '/routes/security.php' ;
           $data = $this->request->getRawBody() ;
           if($data) $data = json_decode($data) ;
           else return $this->response->setStatusCode(400) ;

           if ( ! isset($data->email) || ! isset($data->password) ) return $this->response->setStatusCode(400) ;

           $user = User::findFirstByEmail($data->email) ;
           if(!$user) return $this->response->setStatusCode(404) ;

              if($user->password === $this->security->hash(password) ) {
                $user->token = generateJWT($user->id, $user->email) ;
                $user->save() ;
                return $this->response->setJsonContent($user) ;
              }
                return $this->response->setStatusCode(401) ;
       } catch(Exception $e) {
           $micro->response->setStatusCode(500)->send() ;
           return $micro->response ;
       }
    });

    $micro->post('/api/register',function() use($micro){
        try {

            $data = $this->request->getRawBody() ;
            if($data) $data = json_decode($data) ;
            else return $this->response->setStatusCode(400) ;

            if ( ! isset($data->email) || ! isset($data->password) || !isset($data->password_confirm) ) return $this->response->setStatusCode(400) ;

            $user = User::findFirstByEmail($data->email) ;
            if($user) return $this->response->setStatusCode(400) ;

            if($data->password !== $data->password_confirm) return $this->response->setStatusCode(400) ;

            $user = new User() ;
            $user->email = $data->email ;
            $user->password = $this->security->hash($data->password) ;
            $user->save() ;

            $this->response->setStatusCode(201) ;
            return $this->response->setJsonContent($user) ;


        } catch (Exception $ex) {
            $res = $micro->response ;
            return $res->setJsonContent($ex->getMessage())->send();
        }
    });

    $micro->before(function () use ($micro) {
        include APP_PATH . '/routes/security.php' ;

        if( $_SERVER['REQUEST_URI'] === '/api/login') return true ;
        if( $_SERVER['REQUEST_URI'] === '/api/register') return true ;

        if( ! is_null($micro->request->getHeaders()['Authorization']) ) {
            $token = $micro->request->getHeaders()['Authorization'] ;
            $token = str_replace('Bearer ', '', $token) ;

            if(verifyJWT($token) ) return true ;
            return  $micro->response->setStatusCode(401)->send() ;
        }
        return $micro->response->setStatusCode(401)->send() ;

    });


    $micro->notFound( function () use ($micro) {
        return $micro
            ->response
            ->setStatusCode(404, 'Not Found')
            ->sendHeaders()
            ->setContent('This route does not exist. Not Found ')
            ->send()
        ;
    });
    $micro->get($config->application->ApiUri , function () use ($micro) {
        return $micro->response->setJsonContent(' Hello worlds! ')->send();
    });

    $micro->mount(include APP_PATH . '/routes/artists.php');

    $micro->handle($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    echo $e->getMessage();
}