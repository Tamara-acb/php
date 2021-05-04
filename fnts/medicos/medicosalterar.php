<?php
#################################################################################################################################
# Programa...: medicosalterar (medicosalterar.php)
# Descrição..: Executa arquivo externo (require_once()), identifica valor de variável de controle de recursividade
#              e apresenta dois blocos lógicos de programação: Um blobo para escolha do registro que será excluído, o segundo
#              bloco  realiza o tratamento de transação pra o comando DELETE. 
# Objetivo...: Apresentar o conceito de programação recursiva com uso de funções e com acesso a arquivos externos.
# Autor......: JMH
# Criação....: 2021-04-01
# Atualização: 2021-04-01 - Primeira escrita e montagem da estrutura geral
#              2021-04-08 - Uso de acesso à arq. ext., uso de funções no toolskit.php
#################################################################################################################################
# Referenciando o arquivo toolskit.php
require_once("../../fncs/toolskit.php");
# Referenciando o arquivo medicosfuncoes.php
require_once("./medicosfuncoes.php");
# Usando o operador ternário
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1;
# monstrando o valor de $bloco em cada execução
# printf("$bloco<br>\n");
iniciapagina(TRUE,"Médicos","Alterar");
$_REQUEST['salto']=$_REQUEST['salto']+1;
montamenu("M&eacute;dicos","Alterar","$_REQUEST[salto]");
# Determinando qual bloco será executado no PA
switch (TRUE)
{
  case ($bloco==1):
  { # montando a picklist para escolha do registro para alterar
    picklist("A");
    break;
  }
  case ($bloco==2):
  { # lendo o registro a alterar da tabela medicos
    $reglido=mysqli_fetch_array(mysqli_query($link,"SELECT * FROM medicos WHERE cpmedico='$_REQUEST[cpmedico]'"));
    # montando o form
    printf("<form action='medicosalterar.php' method='POST'>\n");
    printf(" <input type='hidden' name='bloco' value='3'>\n");
    printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    printf(" <input type='hidden' name='cpmedico' value='$_REQUEST[cpmedico]'>\n");
    printf("<table>");
    printf("<tr><td>Nome:</td><td><input type='text' name='txnomemedico' value='$reglido[txnomemedico]' size='50' maxlength='200'></td></tr>\n");
    printf("<tr><td>NuCRM:</td><td><input type='text' name='nucrm' value='$reglido[nucrm]' size='8' maxlength='8'></td></tr>\n");
    printf("<tr><td>Situação:</td><td><input type='radio' name='aosituacao' value='A' checked>-Ativado<input type='radio' name='aosituacao' value='D'>-Desativado</td></tr>\n");
    # Montando a Picklist para a Especialidade Médica (tabela:especmedicas)
    printf("<tr><td>Especialidade:</td><td>");
    mostrafklist('cpespecialidade','txnomeespecialidade','especmedicas','ceespecialidade',"$reglido[ceespecialidade]");
    printf("</td></tr>\n");
    # Montando a Picklist para a Escola de Formação do Médico (tabela:escolas)
    printf("<tr><td>Escola de formação:</td><td>");
    mostrafklist('cpescola','txnomeescola','escolas','ceinstituicao',"$reglido[ceinstituicao]");
    printf("</td></tr>\n");
    printf("<tr><td>Logr.clinica:</td><td>");
    mostrafklist('cplogradouro','txnomelogrcompleto','logradourocompleto','celogradouroclinica',"$reglido[celogradouroclinica]");
    printf("</td></tr>\n");
    printf("<tr><td></td><td>Complemento Clinica<br><input type='text' name='txcomplementoclinica' value='$reglido[txcomplementoclinica]' size='50' maxlength='50'></td></tr>\n");
    printf("<tr><td></td><td>CEP <input type='text' name='nucepclinica' value='$reglido[nucepclinica]' size='8' maxlength='8'></td></tr>\n");
    printf("<tr><td>Logr.Moradia:</td><td>");
    mostrafklist('cplogradouro','txnomelogrcompleto','logradourocompleto','celogradouromoradia',"$reglido[celogradouromoradia]");
    printf("</td></tr>\n");
    printf("<tr><td></td><td>Complemento moradia<br><input type='text' name='txcomplementomoradia' value='$reglido[txcomplementomoradia]' size='50' maxlength='50'></td></tr>\n");
    printf("<tr><td></td><td>CEP<input type='text' name='nucepmoradia' value='$reglido[nucepmoradia]' size='8' maxlength='8'></td></tr>\n");
    printf("<tr><td>Telefone</td><td><input type='text' name='nutelefone' value='$reglido[nutelefone]' size='11' maxlength='11'>-somente números</td></tr>\n");
    printf("<tr><td>Cadastro em:</td><td><input type='date' name='dtcadmedico' value='$reglido[dtcadmedico]'></td></tr>\n");
    printf("   <tr><td></td><td>");
    botoes('Alterar',TRUE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("</td></tr>\n");
    printf("</table>");
    printf("</form>");
    break;
  }
  case ($bloco==3):
  { # tratamento da transação.
    # construção do comando de atualização.
    $cmdsql="UPDATE medicos
                    SET txnomemedico         = '$_REQUEST[txnomemedico]',
                        nucrm                = '$_REQUEST[nucrm]',
                        ceespecialidade      = '$_REQUEST[ceespecialidade]',
                        ceinstituicao        = '$_REQUEST[ceinstituicao]',
                        celogradouromoradia  = '$_REQUEST[celogradouromoradia]',
                        txcomplementomoradia = '$_REQUEST[txcomplementomoradia]',
                        nucepmoradia         = '$_REQUEST[nucepmoradia]',
                        celogradouroclinica  = '$_REQUEST[celogradouroclinica]',
                        txcomplementoclinica = '$_REQUEST[txcomplementoclinica]',
                        nucepclinica         = '$_REQUEST[nucepclinica]',
                        aosituacao           = '$_REQUEST[aosituacao]',
                        nutelefone           = '$_REQUEST[nutelefone]',
                        dtcadmedico          = '$_REQUEST[dtcadmedico]'
                    WHERE
                        cpmedico='$_REQUEST[cpmedico]'";
    # printf("$cmdsql<br>\n");
    $mostrar=FALSE;
    $tenta=TRUE;
    while ( $tenta )
    { # laço de controle de exec da trans.
      mysqli_query($link,"START TRANSACTION");
      # execução do cmd.
      mysqli_query($link,$cmdsql);
      # tratamento dos erros de exec do cmd.
      if ( mysqli_errno($link)==0 )
      { # trans pode ser concluída e não reiniciar
        mysqli_query($link,"COMMIT");
        $tenta=FALSE;
        $mostrar=TRUE;
        $mens="Registro com código $_REQUEST[cpmedico] Alterado!";
      }
      else
      {
        if ( mysqli_errno($link)==1213 )
        { # abortar a trans e reiniciar
          $tenta=TRUE;
        }
        else
        { # abortar a trans e NÃO reiniciar
          $tenta=FALSE;
          $mens=mysqli_errno($link)."-".mysqli_error($link);
        }
        mysqli_query($link,"ROLLBACK");
        $mostrar=FALSE;
      }
    }
    printf("$mens<br>\n");
    if ( $mostrar )
    {
      mostraregistro("$_REQUEST[cpmedico]",'',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    }
    break;
  }
}
terminapagina("médicos","Alterar","medicosalterar.php");
?>
