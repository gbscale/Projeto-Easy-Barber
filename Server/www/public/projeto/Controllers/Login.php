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

    //R - Função Listar todas os registros de uma tabela do BD
    function index(){
        $data = [];
        $data['pagina'] = 'Login';
        $data['msg'] = '';
        return view('login/index',$data);
    }

    //R - Função editar  - Lista um registro da tabela filtrado por id

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
    function search(){
        $data = [];
        $requests = $_POST;
        $data['msg'] = '';
        if(isset($requests['pesquisar'])){
            $join = null;
            $where = null;
            $order = null;
            $limit = null;
            $where = 'login_nome like "%'.$requests['pesquisar'].'%"'.' or login_cpf like "%'.$requests['pesquisar'].'%"'.' or login_email like "%'.$requests['pesquisar'].'%"';
            $data['login'] = $this->login->select($join,$where,$order,$limit)->fetchAll(PDO::FETCH_CLASS);
            $data['msg'] = $this->flash("Total de registros: ".count($data['login']));

            $data['pagina'] = 'Pesquisar login';
            return view('login/index',$data);

        }else{
            $this->index();
        }
        

    }

    //U - Função Alterar
    function edit_save(){
        $data = [];
        $requests = $_POST;
        $values = [
                    'login_nome' => $requests['login_nome'],
                    'login_sobrenome'=> $requests['login_sobrenome'],
                    'login_cpf'=> $requests['login_cpf'],
                    'login_email'=> $requests['login_email'],
                    'login_fone'=> $requests['login_fone'],
                ];

        if($this->login->update('login_id = '.$requests['login_id'],$values)){
            $data['msg'] = $this->flash('Alterado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Não foi alterado!','danger');
        }
        $data['login'] = $this->login->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar login';
        return view('login/index',$data);

    }

    //D - Função Deletar
    function delete($id){
        $id = (int) $id;
        $data = [];
        if($this->login->delete('login_id = '.$id)){
            $data['msg'] = $this->flash("Exluido com Sucesso!");
        }else{
            $data['msg'] = $this->flash("Não foi Excluido!","danger");
        }
        $data['login'] = $this->login->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar login';
        return view('login/index',$data);

    }
    
    
}