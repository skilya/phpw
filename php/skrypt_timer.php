<?php
    class skrypt_timer
    {
        private $_time_start;
        private $_time_end;

        public function start() {
            $this->_time_start = time();
        }
        public function end() {
            $this->_time_end = time();
        }
        public function print_time() {
            echo "<p>Блок выполнялся ";
            echo $this->_time_end - $this->_time_start;
            echo " секунд.</p>";
        }
    }