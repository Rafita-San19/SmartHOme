<?php
require_once 'conexion.php';
require_once 'jwt.php';

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["user"]) && isset($_GET["pass"])){
        $c = conexion();
        $s = $c->prepare("SELECT * FROM users WHERE user = :user AND pass = :pass");
        $s->bindValue(":user", $_GET["user"]);
        $s->bindValue(":pass", sha1($_GET["pass"]));
        $s->execute();
        $s->setFetchMode(PDO::FETCH_ASSOC);
        $r = $s->fetch();
        if($r){
            $t = JWT::create(["user" => $_GET["user"], "rol" => $r['rol']], Config::SECRET);
            $result = ["login" => "y", "token" => $t];
        }else{
            $result = array("login" => "n", "token" => "Error");
        }
        header("http/1.1 200 ok");
        echo json_encode($result);
    }
}