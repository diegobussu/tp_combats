<?php

namespace combats\model;

class PersonnagesManager extends Manager
{
    private $_perso;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Count all characters
     *
     * @return mixed
     */
    public function countAll()
    {
        $sql = "SELECT count(*) FROM personnages";
        $response = $this->_db->query( $sql );
        $nbPerso = $response->fetch();
        return $nbPerso[0];
    }



    /**
     * Return list array of all charaters
     *
     * @return array|false
     */
    public function listAll()
    {
        $sql = "SELECT * FROM personnages";
        $response = $this->_db->query( $sql );
        $listPerso = $response->fetchAll( \PDO::FETCH_ASSOC);
        return $listPerso;
    }



    /**
     * Return character object of player
     *
     * @param int $id
     * @return false|Personnages
     */
    public function getPerso( int $id )
    {
        $sql = "SELECT * FROM personnages WHERE id=:id";
        $req = $this->_db->prepare( $sql );
        if( $res = $req->execute([':id'=>$id] ) ) {
            $perso = $req->fetch(\PDO::FETCH_ASSOC);
            $this->_perso = new Personnages( $perso );
            return $this->_perso;
        } else {
            return false;
        }
    }



    /**
     * Create player object
     * @param Personnages $newPerso
     * @return bool
     */
    public function createPerso( Personnages $newPerso )
    {
        if( !empty( $newPerso->getNom() ) ) {
            $sql = 'INSERT INTO personnages (nom, degats, experience, niveau) 
                    VALUES (:nom, :degats, :exp, :niveau)';
            $req = $this->_db->prepare($sql);
            $state = $req->execute([
                ':nom' => $newPerso->getNom(),
                ':degats' => $newPerso->getDegats(),
                ':exp' => $newPerso->getExperience(),
                ':niveau' => $newPerso->getNiveau()
            ]);
            return $state;
        } else {
            return false;
        }

    }


    /**
     *
     * @param Personnages $perso
     * @return bool
     */
    public function updatePerso( Personnages $perso )
    {
        if( $perso ) {
            $sql = 'UPDATE personnages SET nom=:nom, degats=:degats, 
                    experience=:experience WHERE id=:id';
            $req = $this->_db->prepare($sql);
            $state = $req->execute([
                ':nom'          => $perso->getNom(),
                ':degats'       => $perso->getDegats(),
                ':experience'   => $perso->getExperience(),
                ':id'           => $perso->getId()
            ]);
            return $state;
        } else {
            return false;
        }
    }



    /**
     * @param int $id Id of charater to delete
     * @return bool
     */
    public function deletePerso( int $id )
    {
        if( isset( $id ) ) {
            $sql = 'DELETE FROM personnages WHERE id=:id';
            $req = $this->_db->prepare($sql);
            $state = $req->execute([
                ':id'   => $id
            ]);
            return $state;
        } else {
            return false;
        }
    }



    /**
     * Return list of charaters to hit
     * @param int $playerId
     * @return array|bool $listPersoToHit
     */
    public function getListToHit( int $playerId )
    {
        $listPersoToHit = false;
        if( isset( $playerId ) ) {
            $sql = "SELECT * FROM personnages WHERE id <> :playerId";
            $req = $this->_db->prepare($sql);
            $res = $req->execute([
                'playerId' => $playerId
            ]);
            $listPersoToHit = [];
            $listPerso = $req->fetchAll( \PDO::FETCH_ASSOC );
            if( !empty( $listPerso ) ) {
                foreach ( $listPerso as $key=>$val ) {
                    $listPersoToHit[] = new Personnages( $val );
                }
            }
        }
        return $listPersoToHit;
    }





}