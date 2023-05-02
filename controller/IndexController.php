<?php

namespace combats\controller;

use combats\model\PersonnagesManager;


class IndexController extends Controller
{
    protected $persoManager;

    public function __construct( array $params=[] )
    {
        $this->persoManager = new PersonnagesManager();
        //  $personnage = new Personnages();
        parent::__construct( $params );
    }

    /**
     *  Default action, called if no action is detected
     */
    public function defaultAction()
    {
        $nbPerso = $this->persoManager->countAll();
        $data = [
            "nbPerso"   => $nbPerso
        ];
        $this->render( 'index', $data );
    }


    /**
     *  Destroy session vars & redirect to home
     */
    public function logoutAction()
    {
        session_destroy();
        header('Location: .');
        exit();
    }

}













