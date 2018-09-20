<?php
    class Token
    {
        public $_login;
        public $_user;
        public $_link;
        public $_user_hash;

        public function __construct($login, $user, $hash)
        {
            $this->_login = $login;
            $this->_user = $user;
            $this->_link = 'https://'.$this->_user.'.amocrm.ru/api/v2/';
            $this->_hash = $hash;
        }
    }