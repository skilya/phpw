<?php
    class CSVC
    {
        public function generate($arr)
        {
            $result = "";

            foreach ($arr as $value) {

                foreach ($value as $cell){
                    $cell_data = "\"".$cell."\"";
                    $result .= $cell_data.";";
                }
                $result .= "\n";
            }

            file_put_contents("data.csv", $result);
        }
    }