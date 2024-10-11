<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel CMS Avançado 2.0</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="">Painel CMS</a>
    </div>
    <nav>
      <ul>
        <li><a href="">Cadastrar Página</a></li>
        <li><a href="">Listar Páginas</a></li>
      </ul>
    </nav>
  </header>

  <div class="main">
    <?php
      if(isset($_POST['acao'])) {
        $nome_arquivo = $_POST['nome_arquivo'];
        $nome_pagina = $_POST['nome_pagina'];
        $conteudo_pagina = "";
        foreach ($_POST as $key => $value) {
          if($key != 'acao' && $key != 'nome_arquivo' && $key != 'nome_pagina') {
            $conteudo_pagina.=$value;
            $conteudo_pagina.="--!--";
          }
        }

        $mysql = MySql::connect()->prepare("INSERT INTO `paginas` VALUES (null,?,?,?)");
        echo $conteudo_pagina;
        $mysql->execute(array($nome_pagina, $nome_arquivo, $conteudo_pagina));
        echo '<script>alert("Salvo com sucesso")</script>';
      }
    ?>
    <?php
      if (!isset($_POST['proxima_etapa'])) {
    ?>
      <form action="" method="post">
        <select name="arquivo" id="">
          <?php

            $files = glob('templates/*.html');
            foreach ($files as $key => $value) {
              $file = explode('/', $value);
              $file_name = $file[count($files) - 1];
              echo '<option value="'.$file_name.'">'.$file_name.'</option>';
            }

          ?>
        </select>

        <input type="text" name="nome_pagina" placeholder="Nome da Página">

        <input type="submit" name="proxima_etapa" value="Proxima Etapa">
      </form>
    <?php
      } else {
        $nome_arquivo = $_POST['arquivo'];
        $nome_pagina = $_POST['nome_pagina'];

        // Pegamos os dados do arquivo e calculamos quantos campos tem para serem substituídos

        $get_content = file_get_contents('templates/'.$nome_arquivo);

        $fields = TemplateLeitor::pegaCampos($get_content, '\{\{!(.*?)\}\}');

    ?>
      <h2>Editando Página: <?php echo $nome_pagina; ?> | Arquivo Base: <?php echo $nome_arquivo; ?></h2>

      <form action="" method="post">
        <?php 
          for ($i=0; $i < count($fields['chave']); $i++) { 
            echo '<input type="text" name="'.$fields['campo'][$i].'" placeholder="'.$fields['campo'][$i].'" />';
            echo '<hr/>';
          }
        ?>
        <input type="hidden" name="nome_pagina" value="<?php echo $nome_pagina; ?>">
        <input type="hidden" name="nome_arquivo" value="<?php echo $nome_arquivo; ?>">
        <input type="submit" name="acao" value="Salvar">
      </form>
    <?php } ?>
  </div>
</body>
</html>