<?php


return [
    '/projeto' => ['Home', 'index'],
    '/projeto/home' => ['Home', 'index'],
    '/projeto/home/index' => ['Home', 'index'],
    '/projeto/home/precos' => ['Home', 'precos'],
    '/projeto/home/vender' => ['Home', 'vender'],
    '/projeto/home/anuncios' => ['Home', 'anuncios'],
    '/projeto/home/contato' => ['Home', 'contato'],
    '/projeto/cidades' => ['Cidades', 'index'],
    '/projeto/cidades/new' => ['Cidades', 'new'],
    '/projeto/cidades/index' => ['Cidades', 'index'],
    '/projeto/cidades/edit/{id}' => ['Cidades', 'edit'],
    '/projeto/cidades/delete/{id}' => ['Cidades', 'delete'],
    '/projeto/cidades/save' => ['Cidades', 'save'],
    '/projeto/cidades/edit_save' => ['Cidades', 'edit_save'],
    '/projeto/cidades/search' => ['Cidades', 'search'],
    '/sobre' => ['HomeController', 'sobre'],
];

