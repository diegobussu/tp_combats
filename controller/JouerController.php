<?php

namespace combats\controller;

use combats\model\PersonnagesManager;
use combats\model\Personnages;

class JouerController extends Controller
{
    protected $persoManager;
    protected $usedPerso;
    protected $listAllPerso;
    protected $listPersoToHit;

    public function __construct( array $params=[] )
    {
        $this->persoManager = new PersonnagesManager();
        if( isset( $_SESSION['perso'] ) ) {
            $this->usedPerso = $_SESSION['perso'];
            $this->listPersoToHit = $this->persoManager->getListToHit($this->usedPerso->getId() );
        }
        $this->listAllPerso = $this->persoManager->listAll();
        parent::__construct( $params );
    }

    public function defaultAction()
    {
        // TODO: Implement defaultAction() method.
        $this->gameAction();
    }

    /**
     * Home page method for game player
     */
    public function gameAction()
    {
        $data = [
            'listAllPerso'  => $this->listAllPerso,
        ];
        $this->render('jouer', $data);

    }



    /**
     * Select playable character in list array of charaters
     */
    public function utiliserAction()
    {
        if( isset( $this->vars['id'] ) ) {
            unset( $_SESSION['perso'] );
            $this->usedPerso = $this->persoManager->getPerso( $this->vars['id'] );
            $_SESSION['perso'] = $this->usedPerso;
            $this->listPersoToHit = $this->persoManager->getListToHit( $this->vars['id'] );
            $data = [
                'usedPerso'         => $this->usedPerso,
                'listAllPerso'      => $this->listAllPerso,
                'listPersoToHit'    => $this->listPersoToHit
            ];
            $this->render('jouer', $data);
        }
    }



    /**
     * Hit a charater
     */
    public function frapperAction()
    {
        $persoToHit = $this->persoManager->getPerso( $this->vars['idhit'] );
        $retour = $persoToHit->ajoutDegats();

        switch ($retour) {
            case Personnages::PERSONNAGE_FRAPPE :
                if( $this->persoManager->updatePerso( $persoToHit ) ) {
                    $this->usedPerso->ajoutExperience();
                    if ($this->persoManager->updatePerso($this->usedPerso)) {
                        $message = 'Le personnage <b>' . $persoToHit->getNom() . '</b> a bien été frappé !';
                        $message .= '<br/>Il a reçu ' . Personnages::QTE_DEGATS . ' point de dégat.';
                    }
                }
                break;

            case Personnages::PERSONNAGE_TUE :
                if( $this->persoManager->updatePerso($this->usedPerso) ) {
                    if( $this->persoManager->deletePerso($persoToHit->getId()) ) {
                        $message = 'Vous avez tué ce personnage !';
                    }
                }
                break;
        }

        if( $this->persoManager->updatePerso( $persoToHit ) ) {
            $this->usedPerso->ajoutExperience();
            if( $this->persoManager->updatePerso( $this->usedPerso ) ) {
                $this->listPersoToHit = $this->persoManager->getListToHit( $this->usedPerso->getId() );
                $data = [
                    'usedPerso'         => $this->usedPerso,
                    'listAllPerso'      => $this->listAllPerso,
                    'listPersoToHit'    => $this->listPersoToHit,
                    'message'           => $message
                ];
                $this->render('jouer', $data);
            }
        }
    }


}