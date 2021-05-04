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
require_once("./medicosfuncoes.php");
iniciapagina(TRUE,"Médicos","Consultar");
# esta estrutura pode ser 'trocada' por um operador ternário na forma:
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
switch (TRUE)
{
  case ( $bloco==1 ):
  { # Primeiro bloco
    printf("  <form action='./medicosconsultar.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    # Lendo os dados de medicos para montar uma picklist de escolha do medico a consultar
    $cmdsql="SELECT cpmedico, txnomemedico FROM medicos ORDER BY txnomemedico";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='cpmedico'>");
    while ( $registro=mysqli_fetch_array($execcmd) )
    { # laço para 'montar' as linhas de option da picklist
      printf("<option value='$registro[cpmedico]'>$registro[txnomemedico]-($registro[cpmedico])</option>");
    }
    printf("</select>\n");
    botoes("Consultar",TRUE,1,2,0);
    printf("  </form>\n");
    break;
  }
  case ( $bloco==2 ):
  { # Segundo bloco do programa.
    # Para o LST e CON é o trecho de leitura do BD e exibição dos dados
    mostraregistro('$_REQUEST[cpmedico]');
    break;
  }
}
terminapagina("médicos","Consultar","medicosconsultar.php");
 ?>
