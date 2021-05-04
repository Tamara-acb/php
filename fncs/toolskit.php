<?php
# Aqui começa o trecho de declaração das funções deste PA-PHP
function iniciapagina($cordefundo,$tabela,$acao)
{ # emite as TAGs de inicio de página HTML
  printf("<html>\n");
  printf(" <head>\n");
  printf("  <title>PrimeiraPHP</title>\n");
  printf("  <link rel='stylesheet' href='../../fncs/estilos.css'>\n");
  printf(" </head>\n");
  printf($cordefundo ? " <body class='cor'>\n" : " <body>\n");
  printf("<vermelho>$tabela - <negrito>$acao</negrito></vermelho><br>\n");
}
function terminapagina($tabela,$acao,$prg)
{
  printf(" <hr>$tabela %s | &copy; ".date('Y')." - JMH+FATEC-4ºADS | $prg",$acao? " - ".$acao : "");
  printf(" </body>\n");
  printf("</html>");
}
function mostrafklist($pk,$tx,$tb,$fk,$fkalt)
{
    global $link;
    $cmdsql="SELECT $pk,$tx FROM $tb ORDER BY $tx";
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='$fk'>");
    while ( $reg=mysqli_fetch_array($execcmd) )
    {
      $selected=($reg[$pk]=='$fkalt') ? " selected" : "";
      printf("<option value='$reg[$pk]'$selected>$reg[$tx]-($reg[$pk])</option>");
    }
    printf("</select>");
}
function botoes($acao,$limpar,$voltar1,$voltar2,$sair)
{ # Função.....: botoes
  # Descricao..: Esta funcao monta a barra de botoes usados nos PAs de Ger. de Medicos
  # Parametros.: Esta Funcao recebe os parametros:
  #              $acao....: Texto que aparece no botao SUBMIT.
  #              $sair....: Quantidade de saltos em telas para sair do PA.
  #              $voltar..: Numero (1|0) indicando salto para a tela anterior.
  #              $limpar..: Numero (1|0 - TRUE|FALSE) indicando se o botao RESET deve ser apresentado ou nao.
  #              $voltar2: Quantidade de saltos para retornar para a tela de escolha de registro
  #                         (Incluir e Listar recebem '0' neste parametro)
  # Autor......: JMH - Use! Mas fale quem fez!
  # Criação....: 2019-11-08
  # Atualização: 2019-11-08 - Desenvolvimento e teste da funcao.
  #              2019-11-27 - Alterei o codigo para usar somente button, assim podemos usar CSS no iniciapagina
  #                           para ter a tela mais limpa e usar botoes de formaularios.
  #--------------------------------------------------------------------------------------------------------------------
  $barra="";
  $barra=($acao<>"")   ? $barra."<button type='submit'>$acao</button>\n" : $barra;
  $barra=($limpar)     ? $barra."<button type='reset'>Limpar</button>\n" : $barra;
  $barra=($voltar1>0)  ? $barra."<button type='button' onclick='history.go(-1)'>Voltar</button>\n" : $barra;
  $barra=($voltar2>0)  ? $barra."<button type='button' onclick='history.go(-$voltar2)'>Menu</button>\n" : $barra;
  $barra=($sair>0)     ? $barra."<button type='button' onclick='history.go(-$sair)'>Sair</button>\n" : $barra;
  printf("$barra<br>\n");
}
function conectamariadb($server,$username,$senha,$dbname)
{
  # conexão com bancos de dados
  # A PHP dispõe de FUNÇÕES de AMBIENTE para tratar aspectos de programa, tais como processamento de dados em BD (externos).
  # Para 'acessar' um BD (cuidado por um SGBD), a PHP usa um conjunto de funções.
  # Para o SGBD MySQL (ou MariaDB) o conjunto de funções são identificados por mysqli_.
  # o retorno da função de ambiente que faz a conexão de um PA com o SGBD é um NÚMERO.
  # o número da conexão pode ser aramazenado em variável PHP.
  # a sintaxe da função de conexão indica que a função recebe 4 parâmetros:
  # Nome do servidor
  # nome do usuário
  # senha de acesso do usuao ao sgbd
  # nome da base de dados.
  # globalizando uma variavel de conexão
  global $link;
  $link=mysqli_connect($server,$username,$senha,$dbname);
}
# Aqui acaba o trecho de declaração das funções.
#--------------------------------------------------------------------------------------------------------------------------------
# Aqui começa o 'Bloco Principal' deste PA-PHP
# executando a função de conexão com o SGBD MariaDB.
conectamariadb("localhost","root","root","ilp54020211");
?>