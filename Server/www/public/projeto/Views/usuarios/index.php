<?php


?>

<div class="container pt-4 pb-5 bg-light">
    <h2 class="border-bottom border-2 border-primary">
         <?= $data['pagina'] ?>
    </h2>

    <?php


    // Mensangem de retorno
     if($data['msg']){
        echo msg($data['msg']['texto'], $data['msg']['color']);
     } 

    ?>

    
    <div class="container-fluid p-3">
        <form class="d-flex" action="<?php echo base_url('usuarios/search'); ?>" role="search" method="POST">
            <input class="form-control me-2" name="pesquisar" type="search" placeholder="Pesquisar" aria-label="Search">
            <button type="submit" class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i> buscar</button>
        </form>
    </div>

    

    <table class="table">
        <!-- Cabeçalho da tabela -->
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th scope="col">
                    <a class="btn btn-primary" href="<?php echo base_url('usuarios/new'); ?>">
                    <i class="bi bi-plus-circle"></i> Novo
                    </a>
                </th>
            </tr>
        </thead>
        <!-- Corpo da tabela -->
        <tbody class="table-group-divider">
            <?php 
            foreach($data['usuarios'] as $usuarios ){?>
            <tr>
                <td><?= $usuarios->usuarios_id; ?></td>
                <td><?= $usuarios->usuarios_nome.' '.$usuarios->usuarios_sobrenome; ?></td>
                <td><?= $usuarios->usuarios_email; ?></td>
                <td>
                    <a class="btn btn-secondary" href="<?php echo base_url('usuarios/edit/'.$usuarios->usuarios_id); ?>">
                        <i class="bi bi-pencil-square"></i> Editar 
                    </a>
                    <a class="btn btn-danger" href="<?php echo base_url('usuarios/delete/'.$usuarios->usuarios_id); ?>">
                    <i class="bi bi-x-circle"></i> Excluir
                    </a>
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>

</div>
