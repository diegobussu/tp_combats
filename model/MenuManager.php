<?php
class MenuManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Build menu from the "menu" table
     *
     * @return array|false
     */
    public function getMenu()
    {
        $sql = "SELECT * FROM menu";
        $response = $this->_db->query( $sql );
        $listMenu = $response->fetchAll();
        return $listMenu;
    }

}