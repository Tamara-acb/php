<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosconsultar.php
# Descricao...: Programa recursivo com dois blocos principais de execução, referenciando um programa de funções (toolskit.php)
# Autor.......: JMHypólito - Copie mas diga quem fez
# Objetivo....: Servir de exemplo para estudo do desenvolvimento de PA.
# Criacao.....: 2021-04-15
# Atualizacao.: 2021-04-15 - Estruturação geral. Escrita da funcionalidade de consulta.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Referenciando o arquivo toolskit.php
require_once("../../fncs/toolskit.php");
# Referenciando o arquivo medicosfuncoes.php
require_once("./medicosfuncoes.php");
iniciapagina(TRUE,"M&eacute;dicos","Consultar");
$_REQUEST['salto']=$_REQUEST['salto']+1;
montamenu("M&eacute;dicos","Consultar","$_REQUEST[salto]");
# esta estrutura pode ser 'trocada' por um operador ternário na forma:
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
switch (TRUE)
{
  case ($bloco==1):
  { # montando a picklist para escolha do registro para consultar
    picklist("C");
    break;
  }
  case ( $bloco==2 ):
  { # Executando a função que mostrao registro escolhido na tela.
    mostraregistro("$_REQUEST[cpmedico]",'',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    break;
  }
}
terminapagina("M&eacute;dicos","Consultar","medicosconsultar.php");
 ?>
