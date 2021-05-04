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
require_once("./oficinasfuncoes.php");
$bloco=( ISSET($_POST['bloco']) ) ? $_POST['bloco'] : 1;
$cordefundo=($bloco<3) ? TRUE : FALSE;
iniciapagina($cordefundo,"Oficinas","Listar");
$_REQUEST['salto']=$_REQUEST['salto']+1;
# Separador de Blocos Lógicos do programa
switch (TRUE)
{
  case ( $bloco==1 ):
  { # este bloco monta o form e passa o bloco para o valor 2 em modo oculto
    montamenu("Oficinas","Listar","$_REQUEST[salto]");
    printf(" <form action='./oficinaslistar.php' method='post'>\n");
    printf(" <input type='hidden' name='bloco' value=2>\n");
    printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    printf(" <table>\n");
    printf(" <tr><td colspan=2>Escolha a <negrito>ordem</negrito> como os dados serão exibidos no relatório:</td></tr>\n");
    printf(" <tr><td>Código da Oficina.:</td><td>(<input type='radio' name='ordem' value='O.cpoficina'>)</td></tr>\n");
    printf(" <tr><td>Nome da Oficina...:</td><td>(<input type='radio' name='ordem' value='O.txnomeoficina' checked>)</td></tr>\n");
    printf(" <tr><td colspan=2>Escolha valores para selação de <negrito>dados</negrito> do relatório:</td></tr>\n");
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
    $selecao=" WHERE (O.dtcadoficina between '$_REQUEST[dtcadini]' and '$_REQUEST[dtcadfim]')";
    #$selecao=( $_REQUEST['ceespecialidade']!='TODAS' ) ? $selecao." AND M.ceespecialidade='$_REQUEST[ceespecialidade]'" : $selecao ;
    $cmdsql="SELECT O.*, 
                    L.txnomelogradouro      AS nomelogradouro, 
                    LT.txnometipologradouro AS tipologradouro
                    FROM oficinas AS O INNER JOIN logradouros      AS L    ON O.celogradouro     = L.cplogradouro
                                       INNER JOIN logradourostipos AS LT   ON L.cetipologradouro = LT.cptipologradouro".$selecao." ORDER BY $_REQUEST[ordem]";



    # printf("$cmdsql<br>\n");
    $execsql=mysqli_query($link,$cmdsql);
    ($bloco==2) ? montamenu("Oficinas","Listar","$_REQUEST[salto]"):"";
    printf("<table border=1 style=' border-collapse: collapse; '>
            <tr><td valign=top>Cod.</td>\n
                <td valign=top>Nome:</td>\n
                <td valign=top>Apelido</td>\n
                <td valign=top>Logradouro</td>\n
                <td valign=top>Complemento</td>\n
                <td valign=top>CEP</td>\n
                <td valign=top>Dt.Cad.</td></tr>\n");
    while ( $le=mysqli_fetch_array($execsql) )
    {
      printf("<tr><td>$le[cpmedico]</td>\n
              <td valign=top>$le[txnomeoficina]</td>\n
              <td valign=top>$le[txapelido]</td>\n
              <td valign=top>$le[txnomelogradouro]-($le[celogradouro])</td>\n
              <td valign=top>$le[txcomplemento]</td>\n
              <td valign=top>$le[nucep]</td>\n
              <td valign=top>%s</td>\n
              <td valign=top>$le[dtcadoficina]</td></tr>\n");
    }
    printf("</table>\n");
    if ( $bloco==2 )
    {
      printf("<form action='./medicoslistar.php' method='POST' target='_NEW'>\n");
      printf(" <input type='hidden' name='bloco' value=3>\n");
      printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
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
terminapagina("Oficinas","Listar","oficinaslistar.php");
?>
