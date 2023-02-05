<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class ArtistController extends Controller
{

    public function getCollection() {
        $artists = Artist::find() ;
        if( count($artists) == 0 ) {
            return $this->response->setStatusCode(404, "Not Found") ;
        }
        return $this->response->setJsonContent($artists) ;
    }

    public function get($id) {
        $artist = Artist::findFirstById($id) ;
        if( ! is_null($artist) && $artist != false)
            return $this->response->setJsonContent($artist) ;
        return $this->response->setStatusCode(404) ;
    }

    public function post() {
        try {
            $data = $this->request->getRawBody() ;
            if($data) $data = json_decode($data) ;
            else return $this->response->setStatusCode(400) ;

            if( isset($data->name) && isset($data->style) ) {
                $artist_exist = Artist::findFirstByName($data->name) ;

                if( $artist_exist === false || $artist_exist === null) {
                    $artist = new Artist() ;
                    $artist->name = $data->name ;
                    $artist->style = $data->style ;
                    $artist->save() ;

                    $this->response->setStatusCode(201) ;
                    return $this->response->setJsonContent($artist) ;
                }

                return $this->response->setStatusCode(400, 'Artist already exist') ;
            }
            return $this->response->setStatusCode(400,'Bad Request') ;
        } catch (Exception $e) {
            $this->response->setStatusCode(500) ;
            return $this->response->setContent($e) ;
        }

    }

    public function patch($id) {
        try {
            $artist = Artist::findFirstById($id) ;
            if(is_null($artist))
                return $this->response->setStatusCode(404) ;

            $data = $this->request->getRawBody() ;
            if($data) $data = json_decode($data) ;
            else return $this->response->setStatusCode(400) ;

            if( isset($data) ) {
                foreach($data as $key => $val) {
                    $artist->$key = $val ;
                }
                $artist->save() ;
                return $this->response->setJsonContent($artist) ;
            }

        } catch (Exception $e) {
            return $this->response->setStatusCode(500) ;
        }
    }

    public function delete($id) {
        try {

            $artist = Artist::findFirstById($id) ;
            if(is_null($artist))
                return $this->response->setStatusCode(404) ;

            $artist->delete() ;
            return $this->response->setStatusCode(204) ;

        } catch (Exception $e) {
            return $this->response->setStatusCode(500) ;
        }
    }
}