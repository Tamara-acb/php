<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosconsultar.php
# Descricao...: Programa recursivo com dois blocos principais de execução, referenciando um programa de funções (toolskit.php)
# Autor.......: JMHypólito - Copie mas diga quem fez
# Objetivo....: Servir de exemplo para estudo do desenvolvimento de PA.
# Criacao.....: 2021-04-15
# Atualizacao.: 2021-04-15 - Estruturação geral. Escrita da funcionalidade de consulta.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Referenciando o arquivo de funcoes.
require_once("../../fncs/toolskit.php");
require_once("./oficinasfuncoes.php");

iniciapagina(TRUE,"Oficinas","Consultar");

$_REQUEST['salto'] = $_REQUEST['salto']+1;
montamenu("Oficinas","Consultar","$_REQUEST[salto]");
# esta estrutura pode ser 'trocada' por um operador ternário na forma:
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
switch (TRUE)
{
  case ( $bloco==1 ):
  { # Primeiro bloco
    picklist("C");
    break;

    /*
    printf("  <form action='./oficinasconsultar.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    # Lendo os dados de medicos para montar uma picklist de escolha do medico a consultar
    $cmdsql="SELECT cpoficina, txnomeoficina FROM oficinas ORDER BY txnomeoficina";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='cpoficina'>");
    

    while ( $registro=mysqli_fetch_array($execcmd) )
    { # laço para 'montar' as linhas de option da picklist
      printf("<option value='$registro[cpoficina]'>$registro[txnomeoficina]-($registro[cpoficina])</option>");
    }
    printf("</select>\n");
    botoes("Consultar",TRUE,1,2,0);
    printf("  </form>\n");
    break;

*/
  }
  case ( $bloco==2 ):
  { # Segundo bloco do programa.
    # Para o LST e CON é o trecho de leitura do BD e exibição dos dados
    #$cmdsql="SELECT * FROM oficinas WHERE cpoficina = '$_REQUEST[cpoficina]'";

    mostraregistro("$_REQUEST[cpoficina]",'',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    break;
  }
}
terminapagina("oficinas","Consultar","oficinasconsultar.php");
 ?>
