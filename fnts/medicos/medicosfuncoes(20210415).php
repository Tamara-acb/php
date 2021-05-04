<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosfuncoes.php
# Descricao...: Programa recursivo com dois blocos principais de execução, referenciando um programa de funções (toolskit.php)
# Autor.......: JMHypólito - Copie mas diga quem fez
# Objetivo....: Servir de exemplo para estudo do desenvolvimento de PA.
# Criacao.....: 2021-04-15
# Atualizacao.: 2021-04-15 - Estruturação geral. Escrita da funcionalidade de consulta.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function mostraregistro($CP)
{ # função que recebe o código do médico e exibe o registro.
    global $link;
    $cmdsql="SELECT M.*, 
                    E.txnomeespecialidade, 
                    I.txnomeescola,
		                L1.txnomelogrcompleto AS txnomelogrmoradia,
		                L2.txnomelogrcompleto AS txnomelogrclinica
                    FROM medicos AS M INNER JOIN especmedicas AS E ON M.ceespecialidade=E.cpespecialidade
                                      INNER JOIN escolas AS I ON M.ceinstituicao=I.cpescola
                                      INNER JOIN logradourocompleto AS L1 ON M.celogradouromoradia=L1.cplogradouro
                                      INNER JOIN logradourocompleto AS L2 ON M.celogradouromoradia=L2.cplogradouro
                    WHERE cpmedico='$_REQUEST[cpmedico]'";
    $execcmd=mysqli_query($link,$cmdsql);
    $registro=mysqli_fetch_array($execcmd);
    printf("<table>\n");
    printf("<tr><td colspan=2>Valores informados no form:</td></tr>\n");
    printf("<tr><td>Nome:</td>       <td>$registro[txnomemedico]</td></tr>\n");
    printf("<tr><td>NuCRM:</td>      <td>$registro[nucrm]</td></tr>\n");
    printf("<tr><td>Situação:</td>   <td>$registro[aosituacao]</td></tr>\n");
    printf("<tr><td>EspecMédica:</td><td>$registro[txnomeespecialidade]-($registro[ceespecialidade])</td></tr>\n");
    printf("<tr><td>Escola:</td>     <td>$registro[txnomeescola]-($registro[ceinstituicao])</td></tr>\n");
    printf("<tr><td>Logr.Mora:</td>  <td>$registro[txnomelogrmoradia]-($registro[celogradouromoradia])</td></tr>\n");
    printf("<tr><td>Compl.Mora:</td> <td>$registro[txcomplementomoradia]</td></tr>\n");
    printf("<tr><td>CEP Mora:</td>   <td>$registro[nucepmoradia]</td></tr>\n");
    printf("<tr><td>Logr. Clin.:</td><td>$registro[txnomelogrclinica]-($registro[celogradouroclinica])</td></tr>\n");
    printf("<tr><td>Compl.Clin.:</td><td>$registro[txcomplementoclinica]</td></tr>\n");
    printf("<tr><td>CEP Clin.:</td>  <td>$registro[nucepclinica]</td></tr>\n");
    printf("<tr><td>Telefone:</td>   <td>$registro[nutelefone]</td></tr>\n");
    printf("<tr><td>Cadastro:</td>   <td>$registro[dtcadmedico]</td></tr>\n");
    printf("<tr><td></td>            <td>");botoes('',FALSE,0,2,0);printf("</td></tr>\n");
    printf("</table>\n");
}
?>
