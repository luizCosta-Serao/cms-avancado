<?php
  include('./class/TemplateLeitor.php');
  include('./class/MySql.php');
  $url = @$_GET['url'];

  if($url == '') {
    include('./painel.php');
  } else {
    $mysql = MySql::connect()->prepare("SELECT * FROM `paginas` WHERE slug = ?");
    $mysql->execute(array($url));
    if ($mysql->rowCount() >= 1) {
      $conteudo = $mysql->fetch();
      $content_pagina = file_get_contents('templates/'.$conteudo['template']);
      $fields = TemplateLeitor::pegaCampos($content_pagina, '\{\{!(.*?)\}\}');

      $conteudo_final = explode('--!--', $conteudo['valores']);
      $content_pagina = str_replace($fields['chave'], $conteudo_final, $content_pagina);

      echo $content_pagina;
    } else {
      include('painel.php');
    }
  }
?>