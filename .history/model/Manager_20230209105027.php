<?php

namespace combats\model;

use PDO;

class Manager
{
    private $_dsn = 'mysql:host=localhost:3306;dbname=';
    private $_login;
    private $_password;

    protected $_db;

    public function __construct()
    {
        if( strstr( $_SERVER['HTTP_HOST'], '51.178.86.117' ) ) {
            $this->_dsn .= 'etudiant1';
            $this->_login = 'etudiant1';
            $this->_password = 'etudiant1';
        } else {
            $this->_dsn .= 'combats';
            $this->_login = 'root';
            $this->_password = '';
        }
        try
        {
            $this->_db = new \PDO( $this->_dsn . ';charset=utf8',
                $this->_login,
                $this->_password  );
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        }
        catch( \Exception $e )
        {
            die( 'Erreur : ' . $e->getMessage());
        }

    }

}