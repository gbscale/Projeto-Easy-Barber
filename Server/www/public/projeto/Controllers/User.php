<?php

namespace Controllers;

require_once("Models/Database.php");
require_once("Config/Helpers.php");

use Models\Database as Conexao;
use \PDO;

class Login{

    private $login;

    
    function __construct(){
        $this->login = new Conexao('login');
    }

    protected function redirect($path, $message = null) {
        if ($message) {
            $_SESSION['msg'] = $message;
        }
        header("Location: {$path}");
        exit;
    }

    function index(){
        $data = [];
        $data['pagina'] = 'Login';
        $data['msg'] = '';
        return view('login/index',$data);
    }

    function auth(){
        $requests = $_POST;

        $login = $requests['login'];
        $senha = $requests['senha'];

        $where = "(usuarios_cpf = '{$login}' OR usuarios_email = '{$login}') 
                  AND usuarios_senha = '{$senha}'";

        $usuario = $this->login->select(null, $where)->fetchObject();

        if ($usuario) {

            if ($usuario->usuarios_nivel == 1) {
                $_SESSION['usuario_logado'] = $usuario;
                $msg = ['texto' => "Logado como admin!", 'color' => "success"];
                Login::redirect(base_url('admin'), $msg);

            } else if ($usuario->usuarios_nivel == 2) {
                $_SESSION['usuario_logado'] = $usuario;
                $msg = ['texto' => "Logado com sucesso!", 'color' => "success"];
                Login::redirect(base_url('user'), $msg);

            } else {

                $msg = ['texto' => "Nível de usuário inválido!", 'color' => "danger"];
                Login::redirect(base_url('login'), $msg);
            }

        } else {

            $msg = ['texto' => "Usuário ou senha incorretos!", 'color' => "danger"];
            Login::redirect(base_url('login'), $msg);
        }
    }
    
    function logout(){
        unset($_SESSION['usuario_logado']);
        session_destroy();
        $msg = ['texto' => "Deslogado com sucesso!", 'color' => "success"];
                Login::redirect(base_url('/'), $msg);
    }
}