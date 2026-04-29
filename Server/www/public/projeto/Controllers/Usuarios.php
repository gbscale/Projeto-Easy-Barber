<?php

namespace Controllers;

require_once("Models/Database.php");
require_once("Config/Helpers.php");

use Models\Database as Conexao;
use \PDO;

class Usuarios{

    private $usuarios;

    
    function __construct(){
        $this->usuarios = new Conexao('usuarios');
    }

    protected function redirect($path, $message = null) {
        if ($message) {
            $_SESSION['msg'] = $message;
        }
        header("Location: {$path}");
        exit;
    }

    private function flash(string $texto, string $color = 'success'): array{
        return [
            'texto' => $texto,
            'color' => $color
        ];
    }
    // Chama o formulário de cadastro
    function new(){
        $data = [];
        $data['usuarios'] = (object) [
            'usuarios_id' => '',
            'usuarios_nome' => '',
            'usuarios_sobrenome' => '',
            'usuarios_cpf' => '',
            'usuarios_email' => '',
            'usuarios_senha' => '',
            'usuarios_fone' => '',
        ];
        $data['pagina'] = 'Cadastrar usuarios';
        $data['method'] = 'save';
        return view('usuarios/form',$data);
    }

    // C - Função Cadastrar
    function save(){
        $data = [];
        $requests = $_POST;

        $values = [
            'usuarios_nome'=> $requests['usuarios_nome'],
            'usuarios_sobrenome'=> $requests['usuarios_sobrenome'],
            'usuarios_cpf'=> $requests['usuarios_cpf'],
            'usuarios_email'=> $requests['usuarios_email'],
            'usuarios_senha'=> md5($requests['usuarios_senha']),
            'usuarios_fone'=> $requests['usuarios_fone'],
            'usuarios_nivel'=> 1
            ];

        if($this->usuarios->insert($values)){
            $data['msg'] = $this->flash('Cadastrado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Cadastrado com Sucesso!','danger');
        }

        $data['usuarios'] = $this->usuarios->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar usuarios';
        return view('usuarios/index',$data);

    }


    //R - Função Listar todas os registros de uma tabela do BD
    function index(){
        $data = [];
        $data['usuarios'] = $this->usuarios->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar usuarios';
        $data['msg'] = '';
        return view('usuarios/index',$data);
    }

    //R - Função editar  - Lista um registro da tabela filtrado por id
    function edit($id){
        $data = [];
        $id = (int) $id;
        $data['usuarios'] = $this->usuarios->select($join=null,'usuarios_id = '.$id)->fetchObject();
        $data['pagina'] = 'Alterar usuarios';
        $data['method'] = 'edit_save';

        return view('usuarios/form',$data);
    }

    //R - Função Pesquisar por um valor
    function search(){
        $data = [];
        $requests = $_POST;
        $data['msg'] = '';
        if(isset($requests['pesquisar'])){
            $join = null;
            $where = null;
            $order = null;
            $limit = null;
            $where = 'usuarios_nome like "%'.$requests['pesquisar'].'%"'.' or usuarios_cpf like "%'.$requests['pesquisar'].'%"'.' or usuarios_email like "%'.$requests['pesquisar'].'%"';
            $data['usuarios'] = $this->usuarios->select($join,$where,$order,$limit)->fetchAll(PDO::FETCH_CLASS);
            $data['msg'] = $this->flash("Total de registros: ".count($data['usuarios']));

            $data['pagina'] = 'Pesquisar usuarios';
            return view('usuarios/index',$data);

        }else{
            $this->index();
        }
        

    }

    //U - Função Alterar
    function edit_save(){
        $data = [];
        $requests = $_POST;
        $values = [
                    'usuarios_nome' => $requests['usuarios_nome'],
                    'usuarios_sobrenome'=> $requests['usuarios_sobrenome'],
                    'usuarios_cpf'=> $requests['usuarios_cpf'],
                    'usuarios_email'=> $requests['usuarios_email'],
                    'usuarios_fone'=> $requests['usuarios_fone'],
                ];

        if($this->usuarios->update('usuarios_id = '.$requests['usuarios_id'],$values)){
            $data['msg'] = $this->flash('Alterado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Não foi alterado!','danger');
        }
        $data['usuarios'] = $this->usuarios->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar usuarios';
        return view('usuarios/index',$data);

    }

    //D - Função Deletar
    function delete($id){
        $id = (int) $id;
        $data = [];
        if($this->usuarios->delete('usuarios_id = '.$id)){
            $data['msg'] = $this->flash("Exluido com Sucesso!");
        }else{
            $data['msg'] = $this->flash("Não foi Excluido!","danger");
        }
        $data['usuarios'] = $this->usuarios->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar usuarios';
        return view('usuarios/index',$data);

    }
    
    
}



