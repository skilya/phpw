<?php
	class Data
	{
		private $_token;

		public function get_data($_id_list, $token) {
            $this->_token = $token;
		    $resp = $this->generate_leads_arr($_id_list);
			return $resp;
		}

		private function get_contacts($arr) {
            $query = "";
            $resp_arr = "";
            if(count($arr) > 0){
                foreach ($arr as $key => $value) {
                    $query .= "id%5B%5D=".$value."&";
                }
            } else {
                return ".";
            }

            $query = substr($query, 0, -1);
            $response = Helper::ask("contacts", $query, $this->_token);
            $i = 0;
            foreach($response['_embedded']['items'] as $val) {
                $resp_arr .= $response['_embedded']['items'][$i]['name']." : ";
                $i++;
            }

            return $resp_arr;
        }
        private function get_companies($id)
        {
            $query = "id%5B%5D=".$id;
            $response = Helper::ask("companies", $query, $this->_token);
            $result = $response['_embedded']['items'][0]['name'];
            if(empty($result)){return ".";}
            return $result;
        }
		private function get_leads($_id_list) {
			$query = "";
			foreach ($_id_list as $key => $value) {
				$query .= "id%5B%5D=".$value."&";
			}
			$query = substr($query, 0, -1);
            $response = Helper::ask("leads", $query, $this->_token);
			return $response;
		}

		private function generate_leads_arr($_id_list) {
            $response = $this->get_leads($_id_list);
            $cnt = count($response['_embedded']['items']);
            $cf_title = [];

            $result = array();
            $result[0]['name']          = "Сделки";
            $result[0]['created_by']    = "Дата создания";
            $result[0]['tags']          = "Тэг";
            $result[0]['contacts']      = "Контакты";
            $result[0]['company']       = "Компания";

            for($i = 0; $i < $cnt; $i++) {
                $l = $i + 1;
                $result[$l]['name'] = $response['_embedded']['items'][$i]['name'];
                $result[$l]['created_at'] = date("Y-m-d H:i:s",
                    $response['_embedded']['items'][$i]['created_at']);

                $j = 0;
                foreach($response['_embedded']['items'][$i]['tags'] as $val) {
                    $result[$l]['tags'] .= $response['_embedded']['items'][$i]['tags'][$j]['name']." : ";
                    $j++;
                }
                if(empty($result[$l]['tags'])){
                    $result[$l]['tags'] = ".";
                } else {
                    $result[$l]['tags'] = substr($result[$l]['tags'], 0, -3);
                }

                $con = $this->get_contacts($response['_embedded']['items'][$i]['contacts']['id']);
                $result[$l]['contacts'] = $con;
                if(empty($result[$l]['contacts'])){
                    $result[$l]['contacts'] = ".";
                } else {
                    $result[$l]['contacts'] = substr($result[$l]['contacts'], 0, -3);
                }


                $com = $this->get_companies($response['_embedded']['items'][$i]['company']['id']);
                $result[$l]['company'] = $com;
                if(empty($result[$l]['company'])){
                    $result[$l]['company'] = ".";
                }


                foreach($response['_embedded']['items'][$i]['custom_fields'] as $val) {
                    $cf_title[$val['id']] = $val['name']." (".$val['id'].")";
                }
                $cf_title = array_unique($cf_title);


                foreach ($cf_title as $title){
                    $result[0][$title] = $title;
                }


                foreach ($cf_title as $key => $title){
                    $vall_is_empty = true;
                    foreach($response['_embedded']['items'][$i]['custom_fields'] as $val) {
                        if($title == $val['name']." (".$val['id'].")"){
                            $vall_is_empty = false;
                            foreach ($val['values'] as $fv){
                                if(is_array($fv['value'])){
                                    foreach ($fv['value'] as $arr_k => $urf){
                                        Helper::print_p($urf);
                                        $result[$l][$title] .= $arr_k.": ".$urf."\n";
                                    }
                                } else {
                                    $result[$l][$title] .= $fv['value']." : ";
                                }
                            }

                        }
                    }
                    if($vall_is_empty == false){
                        $result[$l][$title] = substr($result[$l][$title], 0, -3);
                    } else {
                        $result[$l][$title] = ".";
                    }
                }
            }
            Helper::print_p($result);
            return $result;
		}
	}