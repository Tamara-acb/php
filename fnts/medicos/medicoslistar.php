<?php
#--------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicoslistar (arquivo medicoslistar.php)
# Descrição...: Este PA tem 2 'cases' com tres valores de $bloco. No primeiro case monta um form para escolha da ordenacao dos
#               dados de medicos que serao exibidos na listagem, nos cases 2 e 3 monta uma tabewla com os dados de uma juncao
#               completa entre medicos e todas as tabelas relacionadas. Note o comando SQL que faz a juncao e deste modo faz o
#               SBD trabalhar para o PA.
# Objetivo....: Montar a Listagem de dados da tabela medicos.
# Autor.......: JMH - Copie, mas diga que fez!
# Criação.....: 2019-11-08
# Atualização.: 2019-11-08 - Escrita da estrutura fundamental do programa
#                            (tela de escolha da ordem dos dados e processamento da listagem).
#               2019-11-10 - Coloquei o uso da funcao botoes.
#               2019-11-10 - Testes de conformidade.
#--------------------------------------------------------------------------------------------------------------------------------
require_once("../../fncs/toolskit.php");
require_once("./medicosfuncoes.php");
$bloco=( ISSET($_POST['bloco']) ) ? $_POST['bloco'] : 1;
$cordefundo=($bloco<3) ? TRUE : FALSE;
iniciapagina($cordefundo,"M&eacute;dicos","Listar");
$_REQUEST['salto']=$_REQUEST['salto']+1;
# Separador de Blocos Lógicos do programa
switch (TRUE)
{
  case ( $bloco==1 ):
  { # este bloco monta o form e passa o bloco para o valor 2 em modo oculto
    montamenu("M&eacute;dicos","Listar","$_REQUEST[salto]");
    printf(" <form action='./medicoslistar.php' method='post'>\n");
    printf("  <input type='hidden' name='bloco' value=2>\n");
    printf("  <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    printf("  <table>\n");
    printf("   <tr><td colspan=2>Escolha a <negrito>ordem</negrito> como os dados serão exibidos no relatório:</td></tr>\n");
    printf("   <tr><td>Código do Médico.:</td><td>(<input type='radio' name='ordem' value='M.cpmedico'>)</td></tr>\n");
    printf("   <tr><td>Nome do Médico...:</td><td>(<input type='radio' name='ordem' value='M.txnomemedico' checked>)</td></tr>\n");
    printf("   <tr><td colspan=2>Escolha valores para selação de <negrito>dados</negrito> do relatório:</td></tr>\n");
    printf("   <tr><td>Escolha uma especialidade:</td><td>");
    $cmdsql="SELECT cpespecialidade,txnomeespecialidade FROM especmedicas ORDER BY txnomeespecialidade";
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='ceespecialidade'>");
    printf("<option value='TODAS'>Todas</option>");
    while ( $reg=mysqli_fetch_array($execcmd) )
    {
      printf("<option value='$reg[cpespecialidade]'>$reg[txnomeespecialidade]-($reg[cpespecialidade])</option>");
    }
    printf("<select>\n");
    printf("</td></tr>\n");
    $dtini="1901-01-01";
    $dtfim=date("Y-m-d");
    printf("<tr><td>Intervalo de datas de cadastro:</td><td><input type='date' name='dtcadini' value='$dtini'> até <input type='date' name='dtcadfim' value='$dtfim'></td></tr>");
    printf("   <tr><td></td><td>");
    botoes('Listar',TRUE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("</td></tr>\n");
    printf("  </table>\n");
    printf(" </form>\n");
    break;
  }
  case ( $bloco==2 || $bloco==3 ):
  { # Este bloco vai processar a junção de medicos com instituicaoensino, logradourocompleto (moradia e clinica) e especiaidadesmedicas.
    # Depois monta a tabela com os dados e a seguir um form permitindo que a listagem seja exibida para impressão em uma nova aba.
    $selecao=" WHERE (M.dtcadmedico between '$_REQUEST[dtcadini]' and '$_REQUEST[dtcadfim]')";
    $selecao=( $_REQUEST['ceespecialidade']!='TODAS' ) ? $selecao." AND M.ceespecialidade='$_REQUEST[ceespecialidade]'" : $selecao ;
    $cmdsql="SELECT M.*,
                    E.txnomeespecialidade,
		                I.txnomeescola,
                    L1.txnomelogrcompleto AS txnomelogrmoradia,
                    L2.txnomelogrcompleto AS txnomelogrclinica
                    FROM medicos AS M INNER JOIN especmedicas AS E ON M.ceespecialidade=E.cpespecialidade
                                      LEFT JOIN escolas AS I ON M.ceinstituicao=I.cpescola
                                      INNER JOIN logradourocompleto AS L1 ON M.celogradouromoradia=L1.cplogradouro
                                      INNER JOIN logradourocompleto AS L2 ON M.celogradouroclinica=L2.cplogradouro".$selecao." ORDER BY $_REQUEST[ordem]";
    # printf("$cmdsql<br>\n");
    $execsql=mysqli_query($link,$cmdsql);
    ($bloco==2) ? montamenu("M&eacute;dicos","Listar","$_REQUEST[salto]"):"";
    printf("<table border=1 style=' border-collapse: collapse; '>
            <tr><td valign=top>Cod.</td>\n
                <td valign=top>Nome:</td>\n
                <td valign=top>CRM</td>\n
                <td valign=top>Especialidade</td>\n
                <td valign=top>Inst.de Form.</td>\n
                <td valign=top>Logr.Moradia</td>\n
                <td valign=top>Complemento</td>\n
                <td valign=top>CEP Moradia</td>\n
                <td valign=top>Logr.Clinica</td>\n
                <td valign=top>Compl.</td>\n
                <td valign=top>CEP Clin.</td>\n
                <td valign=top>Tel.</td>\n
                <td valign=top>Situação</td>\n
                <td valign=top>Dt.Cad.</td></tr>\n");
    while ( $le=mysqli_fetch_array($execsql) )
    {
      printf("<tr><td>$le[cpmedico]</td>\n
                  <td valign=top>$le[txnomemedico]</td>\n
                  <td valign=top>$le[nucrm]</td>\n
                  <td valign=top>$le[txnomeespecialidade]-($le[ceespecialidade])</td>\n
                  <td valign=top>$le[txnomeescola]-($le[ceinstituicao])</td>\n
                  <td valign=top>$le[txnomelogrmoradia]-($le[celogradouromoradia])</td>\n
                  <td valign=top>$le[txcomplementomoradia]</td>\n
                  <td valign=top>$le[nucepmoradia]</td>\n
                  <td valign=top>$le[txnomelogrclinica]-($le[celogradouroclinica])</td>\n
                  <td valign=top>$le[txcomplementoclinica]</td>\n
                  <td valign=top>$le[nucepclinica]</td>\n
                  <td valign=top>$le[nutelefone]</td>\n
                  <td valign=top>%s</td>\n
                  <td valign=top>$le[dtcadmedico]</td></tr>\n",($le['aosituacao']=="A")?"Ativado":"Desativado");
    }
    printf("</table>\n");
    if ( $bloco==2 )
    {
      printf("<form action='./medicoslistar.php' method='POST' target='_NEW'>\n");
      printf(" <input type='hidden' name='bloco' value=3>\n");
      printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
      printf("<input type='hidden' name='ceespecialidade' value=$_REQUEST[ceespecialidade]>\n");
      printf("<input type='hidden' name='dtcadini' value=$_REQUEST[dtcadini]>\n");
      printf("<input type='hidden' name='dtcadfim' value=$_REQUEST[dtcadfim]>\n");
      printf("<input type='hidden' name='ordem' value=$_REQUEST[ordem]>\n");
      # <button type='submit'>Impressão</button>
      botoes("Gerar cópia para Impressão",FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
      printf("</form>\n");
    }
    else
    {
      printf("<button type='submit' onclick='window.print();'>Imprimir</button> - Corte a folha abaixo da linha no final da página<br>\n<hr>\n");
    }
    break;
  }
}
terminapagina("médicos","Listar","medicoslistar.php");
?>
