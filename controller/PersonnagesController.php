<?php

namespace combats\controller;

use combats\model\PersonnagesManager;
use combats\model\Personnages;

class PersonnagesController extends Controller
{
    protected $persoManager;

    public function __construct( array $params=[] )
    {
        $this->persoManager = new PersonnagesManager();
        parent::__construct( $params );
    }

    /**
     *  Method executed by default if no action has been detected
     */
    public function defaultAction()
    {
        $this->listAllAction();
    }


    /**
     * List all the characters in an array from the "listAll" method of the "persoManager" class
     * The result is returned to the 'personnages' view by the "render" method
     */
    public function listAllAction()
    {
        $listAllPerso = $this->persoManager->listAll();
        $data = [
            'message'       => 'Liste des personnages',
            'listAllPerso'  => $listAllPerso
        ];
        $this->render( 'personnages', $data );
    }


    /**
     * Creating a character with the "createPerso" method
     */
    public function createAction()
    {
        $data = [];
        $perso = new Personnages([
                'nom' => $this->vars['nom']
        ]);
        if( $this->persoManager->createPerso( $perso ) ) {
            $data = [
                'message'   => 'Personnage créé',
                'error'     => false
            ];
        } else {
            $data = [
                'message'   => 'Erreur lors de la création',
                'error'     => true
            ];
        }
        $data += [
            'listAllPerso'  => $this->persoManager->listAll()
        ];
        $this->render( 'personnages', $data );
    }


    /**
     * Selecting a character for editing.
     * Retrieving character info with the 'getPerso' method
     * Saving character info in session
     * Returns to the view the action that will save the modifications
     */
    public function updateAction()
    {
        $data = [
            'listAllPerso'  => $this->persoManager->listAll()
        ];
        if( isset( $this->vars['id'] ) ) {
            if( $perso = $this->persoManager->getPerso( $this->vars['id'] ) ) {
                $_SESSION['perso'] = $perso;
                $data += [
                    'error'         => false,
                    'id'            => $perso->getId(),
                    'action'        => 'updateValid',
                    'nom'           => $perso->getNom()
                ];
            } else {
                $data += [
                    'error'     => true,
                    'message'   => 'Erreur lors de la récupération du perso'
                ];
            }
        }
        $this->render( 'personnages', $data );
    }


    /**
     * Save the changes with the "updatePerso" method
     */
    public function updateValidAction()
    {
        var_dump( $this->vars );

        if( isset( $this->vars['nom'] ) ) {
            $perso = $_SESSION['perso'];
            unset( $_SESSION['perso'] );
            $perso->setNom( $this->vars['nom'] );
            if( $this->persoManager->updatePerso( $perso ) ) {
                $data = [
                    'error'         => false,
                    'message'       => 'Personnage modifié'
                ];
            } else {
                $data = [
                    'error'         => true,
                    'message'       => 'Erreur lors de la modification'
                ];
            }
        }
        $data += [
            'listAllPerso'  => $this->persoManager->listAll()
        ];
        $this->render( 'personnages', $data );
    }


    /**
     * Delete a character with the "deletePerso" method
     */
    public function deleteAction()
    {
        if (isset( $this->vars['id'] ) ) {
            if( $this->persoManager->deletePerso( $this->vars['id'] ) ) {
                $data = [
                    'error'         => false,
                    'message'       => 'Personnage supprimé'
                ];
            } else {
                $data = [
                    'error'         => true,
                    'message'       => 'Erreur lors de la suppression'
                ];
            }
        }
        $data += [
            'listAllPerso'  => $this->persoManager->listAll()
        ];
        $this->render( 'personnages', $data );
    }

}