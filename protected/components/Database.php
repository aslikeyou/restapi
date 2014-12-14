<?php

class Database implements IComponent {
    public $connection_string;
    public $user;
    public $pass;

    private $_dbh;

    public function init() {
        $this->_dbh = new PDO($this->connection_string, $this->user, $this->pass);
    }

    /**
     * @return PDO
     */
    public function dbh() {
        return $this->_dbh;
    }
}