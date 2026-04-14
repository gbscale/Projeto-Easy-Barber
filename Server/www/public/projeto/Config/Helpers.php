<?php

function accessNavigate($nivel=Null){
    if($nivel == 1){
        include('Views/templates/nav_user.php');
    }else if($nivel == 2){
        include('Views/templates/nav_admin.php');
    }else{
        include('Views/templates/nav.php');
    }
}

function view($viewName, $data = [])
{
    $viewPath = "Views/{$viewName}.php";

    if (file_exists($viewPath)) {
        // Extrai as variáveis do array $data para dentro da view
        extract($data);
        include $viewPath;
    } else {
        echo "Página não encontrada!";
    }
}

function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $url = rtrim($protocol . "://" . $host . $scriptName, '/');

    return $url . '/' . ltrim($path, '/');
}

function msg($texto, $tipo = 'success'){
    $alertType = "alert-{$tipo}";
    if($tipo == 'danger'){
        $icone = '<i class="bi bi-exclamation-triangle-fill"></i>';
    }
    else{
        $icone = '<i class="bi bi-check-circle-fill"></i>';
    }
    
    return '
        <div class="alert '.$alertType.'" role="alert">
        '.$icone.' '.$texto.' 
        </div>
        ';
}


?>