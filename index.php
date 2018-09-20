<?php
    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Headers: Origin,Content-Type,Accept,X-Requested-With');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: content-type');

    require_once("php/helper.php");
    require_once("php/data.php");
    require_once("php/CSVC.php");
    require_once ("php/token.php");


    $token = new Token($_POST['login'], $_POST['user'], $_POST['hash']);
    Helper::authorization($token);

    $data = $_POST["ids"];
    $d = new Data();
    $resp = $d->get_data($data, $token);

    $csvc = new CSVC();
    $csvc->generate($resp);