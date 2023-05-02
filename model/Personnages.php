<?php

namespace combats\model;

class Personnages
{
    protected $_id;
    protected $_nom;
    protected $_degats = 0;
    protected $_experience = 0;
    protected $_niveau = 0;

    protected $_limitExp;

    const PERSONNAGE_TUE = 1; // Constant returned by the `frapper` method if the character was killed by hitting it.
    const PERSONNAGE_FRAPPE = 2; // Constant returned by the `frapper` method if the character has been hit.

    const BASE_EXPERIENCE = 40;
    const QTE_DEGATS = 1;
    const STEP_EXP = 5;


    public function __construct( array $data )
    {
        $this->hydrate( $data );
        $this->setLimitExp();
    }


    /**
     * Fill each property with the values present in $data
     *
     * @param array $data
     */
    public function hydrate( array $data )
    {
        foreach ( $data as $key=>$value ) {
            $method = 'set' . ucfirst($key);
            if( method_exists( $this, $method ) ) {
                $this->$method($value);
            }
        }
    }


    /**
     * Add damage to a character and return it state
     *
     * @return int return state of charaters
     */
    public function ajoutDegats()
    {
        $this->_degats += self::QTE_DEGATS;
        // If we have 100 damage or more, we say that the character has been killed.
        if ($this->_degats >= 100)
        {
            return self::PERSONNAGE_TUE;
        }

        // Otherwise, we just say that the character has been hit.
        return self::PERSONNAGE_FRAPPE;
    }


    /**
     * Adds experience to a character and levels it up
     */
    public function ajoutExperience()
    {
        $this->_experience += self::STEP_EXP;
        if( $this->_experience >= $this->getLimitExp() ) {
            $this->_niveau++;
            $this->_experience = 0;
            $this->setLimitExp();
        }
    }


    // GETTERS
    public function getId()
    {
        return $this->_id;
    }
    public function getNom()
    {
        return $this->_nom;
    }
    public function getDegats()
    {
        return $this->_degats;
    }
    public function getExperience()
    {
        return $this->_experience;
    }
    public function getNiveau()
    {
        return $this->_niveau;
    }
    public function getLimitExp()
    {
        return $this->_limitExp;
    }


    // SETTERS
    public function setId( $id )
    {
        $this->_id = $id;
    }
    public function setNom( $nom )
    {
        $this->_nom = $nom;
    }
    public function setDegats( $degats )
    {
        $this->_degats = $degats;
    }
    public function setExperience( $experience )
    {
        $this->_experience = $experience;
    }
    public function setNiveau( $niveau )
    {
        $this->_niveau = $niveau;
    }
    public function setLimitExp()
    {
        $this->_limitExp = self::BASE_EXPERIENCE * ($this->getNiveau() + 1);
    }
}