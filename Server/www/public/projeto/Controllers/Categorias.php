<?php

namespace Controllers;

require_once("Models/Database.php");
require_once("Config/Helpers.php");

use Models\Database as Conexao;
use \PDO;

class Categorias{

    private $categorias;

    
    function __construct(){
        $this->categorias = new Conexao('categorias');
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
        $data['categorias'] = (object) [
            'categorias_id' => '',
            'categorias_nome' => '',
        ];
        $data['pagina'] = 'Cadastrar categorias';
        $data['method'] = 'save';
        return view('categorias/form',$data);
    }

    // C - Função Cadastrar
    function save(){
        $data = [];
        $requests = $_POST;

        $values = [
            'categorias_nome'=> $requests['categorias_nome'],
            ];

        if($this->categorias->insert($values)){
            $data['msg'] = $this->flash('Cadastrado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Cadastrado com Sucesso!','danger');
        }

        $data['categorias'] = $this->categorias->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar categorias';
        return view('categorias/index',$data);

    }


    //R - Função Listar todas os registros de uma tabela do BD
    function index(){
        $data = [];
        $data['categorias'] = $this->categorias->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar categorias';
        $data['msg'] = '';
        return view('categorias/index',$data);
    }

    //R - Função editar  - Lista um registro da tabela filtrado por id
    function edit($id){
        $data = [];
        $id = (int) $id;
        $data['categorias'] = $this->categorias->select($join=null,'categorias_id = '.$id)->fetchObject();
        $data['pagina'] = 'Alterar categorias';
        $data['method'] = 'edit_save';

        return view('categorias/form',$data);
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
            $where = 'categorias_nome like "%'.$requests['pesquisar'].'%"'; 
            $data['categorias'] = $this->categorias->select($join,$where,$order,$limit)->fetchAll(PDO::FETCH_CLASS);
            $data['msg'] = $this->flash("Total de registros: ".count($data['categorias']));

            $data['pagina'] = 'Pesquisar categorias';
            return view('categorias/index',$data);

        }else{
            $this->index();
        }
        

    }

    //U - Função Alterar
    function edit_save(){
        $data = [];
        $requests = $_POST;
        $values = [
                    'categorias_nome' => $requests['categorias_nome'],
                ];

        if($this->categorias->update('categorias_id = '.$requests['categorias_id'],$values)){
            $data['msg'] = $this->flash('Alterado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Não foi alterado!','danger');
        }
        $data['categorias'] = $this->categorias->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar categorias';
        return view('categorias/index',$data);

    }

    //D - Função Deletar
    function delete($id){
        $id = (int) $id;
        $data = [];
        if($this->categorias->delete('categorias_id = '.$id)){
            $data['msg'] = $this->flash("Exluido com Sucesso!");
        }else{
            $data['msg'] = $this->flash("Não foi Excluido!","danger");
        }
        $data['categorias'] = $this->categorias->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar categorias';
        return view('categorias/index',$data);

    }
    
    
}



