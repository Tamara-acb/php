<?php

require_once("../../fncs/toolskit.php");
require_once("./oficinasfuncoes.php");

$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1;

iniciapagina(TRUE, "Oficinas", "Incluir");
$_REQUEST['salto'] = $_REQUEST['salto'] +1;
montamenu("Oficinas","Incluir","$_REQUEST[salto]");

switch (TRUE)
{
  case ($bloco==1):
  { 
  	printf(" <form action='oficinasincluir.php' method='POST'>\n");
  	printf(" <input type='hidden' name='bloco' value='2'>\n");
    printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
  	printf(" <table>\n");
  	printf(" <tr><td>Nome:</td><td><input type='text' name='txnomeoficina' size=50 maxlength=250></td></tr>\n");
  	printf(" <tr><td>Apelido:</td><td><input type='text' name='txapelido' size=50 maxlength=60></td></tr>\n");
  	printf(" <tr><td>Logradouro:</td><td>");
  	mostrafklist('cplogradouro','txnomelogradouro','logradouros','celogradouro','');
  	printf(" </td></tr>\n");
  	printf(" <tr><td></td><td>Complemento:<br><input type='text' name='txcomplemento' size=50 maxlength=80></td></tr>\n");
    printf(" <tr><td></td><td>CEP: <input type='text' name='nucep' size='10' maxlength='8'></td></tr>\n");
  	printf(" <tr><td>Data de Cadastro:</td><td><input type='date' name='dtcadoficina'></td></tr>\n");
    printf(" <tr><td></td><td>");
    botoes(' Incluir', TRUE,$_REQUEST['salto']-2, $_REQUEST['salto']-1, "$_REQUEST[salto]");
    printf(" </td></tr>\n");
  	printf(" </table>\n");
  	printf(" </form>");
	break;
  }
  case ($bloco==2):
  {
  	$mostrar=FALSE;
  	$tenta=TRUE;
  	while($tenta)
  	{#laço de controle de exec da trans.
  		mysqli_query($link, "START TRANSACTION");
  		#construção do comando de atualização
  		#recuperação do ultimo valor gravado na PK da tabela
  		$ultimacp=mysqli_fetch_array(mysqli_query($link,"SELECT MAX(cpoficina) AS CpMAX FROM oficinas"));
  		$CP=$ultimacp['CpMAX']+1;
  		$cmdsql="INSERT INTO oficinas (cpoficina,
  									                 txnomeoficina,
  									                 txapelido,
  									                 celogradouro,
  									                 txcomplemento,
                                     nucep,
                                     dtcadoficina)
                      VALUES ('$CP',
                              '$_REQUEST[txnomeoficina]',
                              '$_REQUEST[txapelido]',
                              '$_REQUEST[celogradouro]',
                              '$_REQUEST[txcomplemento]',
                              '$_REQUEST[nucep]',
                              '$_REQUEST[dtcadoficina]')";
        #printf("$cmdsql<br>\n");
  		#execução do cmd.
  		mysqli_query($link,$cmdsql);
  		#tratamento dos erros de exec do cmd.
  		if( mysqli_errno($link)==0)
  		{#trans pode ser concluida  e não reiciniar
  			mysqli_query($link,"COMMIT");
  			$tenta=FALSE;
  			$mostrar=TRUE;
        $mens="Registro incluído! Valor do Código: $CP";
  		}
  		else
  		{
  			if(mysqli_errno($link)==1213)
  			{#abortar a trans e reiniciar
  				$tenta=TRUE;
  			}
  			else
  			{#abortar a trans e NÂO reiniciar
  				$tenta=FALSE;
          $mens=mysqli_errno($link)."-".mysqli_error($link);
  			}
  			mysqli_query($link,"ROLLBACK");
  			$mostrar=FALSE;
  		}
  	}
    printf("$mens<br>\n");
  	if($mostrar)
  	{
      mostraregistro("$CP",'',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");

	  	/*printf("<table>\n");
	  	printf("<tr><td colspan=2>Valores informados no form:</td></tr>");
	  	printf("<tr><td>Nome:</td>				<td>$_REQUEST[txnomeoficina]</td></tr>");
	  	printf("<tr><td>Apelido:</td>			<td>$_REQUEST[txapelido]</td></tr>");
	  	printf("<tr><td>Logradouro:</td>	<td>$_REQUEST[celogradouro]</td></tr>");
	  	printf("<tr><td>Complemento:</td>	<td>$_REQUEST[txcomplemento]</td></tr>");
	  	printf("<tr><td>CEP:</td>				  <td>$_REQUEST[nucep]</td></tr>");
	  	printf("<tr><td>Data de cadastro: </td>	<td>$_REQUEST[dtcadoficina]</td></tr>");
      printf("<tr><td></td>             <td>");botoes('',FALSE,0,2,0);printf("</td></tr>\n");
	  	printf("</table>\n");*/
  	}
	break;
  }
}
terminapagina("Oficinas", "Incluir", "oficinasincluir.php");
?>