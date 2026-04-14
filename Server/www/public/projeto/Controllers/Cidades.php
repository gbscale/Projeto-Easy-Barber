<?php

namespace Controllers;

require_once("Models/Database.php");
require_once("Config/Helpers.php");

use Models\Database as Conexao;
use \PDO;

class Cidades{

    private $cidades;


    
    function __construct(){
        $this->cidades = new Conexao('cidades');
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
        $data['cidades'] = (object) [
            'cidades_id' => '',
            'cidades_nome' => '',
            'cidades_uf' => '',
        ];
        $data['pagina'] = 'Cadastrar cidades';
        $data['method'] = 'save';
        return view('cidades/form',$data);
    }

    // C - Função Cadastrar
    function save(){
        $data = [];
        $requests = $_POST;

        $values = [
            'cidades_nome'=> $requests['cidades_nome'],
            'cidades_uf'  => $requests['cidades_uf']
            ];

        if($this->cidades->insert($values)){
            $data['msg'] = $this->flash('Cadastrado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Cadastrado com Sucesso!','danger');
        }

        $data['cidades'] = $this->cidades->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar cidades';
        return view('cidades/index',$data);

    }


    //R - Função Listar todas os registros de uma tabela do BD
    function index(){
        $data = [];
        $data['cidades'] = $this->cidades->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar cidades';
        $data['msg'] = '';
        return view('cidades/index',$data);
    }

    //R - Função editar  - Lista um registro da tabela filtrado por id
    function edit($id){
        $data = [];
        $id = (int) $id;
        $data['cidades'] = $this->cidades->select($join=null,'cidades_id = '.$id)->fetchObject();
        $data['pagina'] = 'Alterar cidades';
        $data['method'] = 'edit_save';

        return view('cidades/form',$data);
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
            $where = 'cidades_nome like "%'.$requests['pesquisar'].'%"'; 
            $data['cidades'] = $this->cidades->select($join,$where,$order,$limit)->fetchAll(PDO::FETCH_CLASS);
            $data['msg'] = $this->flash("Total de registros: ".count($data['cidades']));

            $data['pagina'] = 'Pesquisar cidades';
            return view('cidades/index',$data);

        }else{
            $this->index();
        }
        

    }

    //U - Função Alterar
    function edit_save(){
        $data = [];
        $requests = $_POST;
        $values = [
                    'cidades_nome' => $requests['cidades_nome'],
                    'cidades_uf'   => $requests['cidades_uf']
                ];

        if($this->cidades->update('cidades_id = '.$requests['cidades_id'],$values)){
            $data['msg'] = $this->flash('Alterado com Sucesso!');
        }else{
            $data['msg'] = $this->flash('Não foi alterado!','danger');
        }
        $data['cidades'] = $this->cidades->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar cidades';
        return view('cidades/index',$data);

    }

    //D - Função Deletar
    function delete($id){
        $id = (int) $id;
        $data = [];
        if($this->cidades->delete('cidades_id = '.$id)){
            $data['msg'] = $this->flash("Exluido com Sucesso!");
        }else{
            $data['msg'] = $this->flash("Não foi Excluido!","danger");
        }
        $data['cidades'] = $this->cidades->select($join=null, $where=null,$order=null,$limit=null)->fetchAll(PDO::FETCH_CLASS);
        $data['pagina'] = 'Listar cidades';
        return view('cidades/index',$data);

    }
    
    
}



