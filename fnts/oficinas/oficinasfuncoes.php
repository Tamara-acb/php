<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosfuncoes.php
# Descricao...: Programa recursivo com dois blocos principais de execução, referenciando um programa de funções (toolskit.php)
# Autor.......: JMHypólito - Copie mas diga quem fez
# Objetivo....: Servir de exemplo para estudo do desenvolvimento de PA.
# Criacao.....: 2021-04-15
# Atualizacao.: 2021-04-15 - Estruturação geral. Escrita da funcionalidade de consulta.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function mostraregistro($CP,$acao,$limpa,$volta1,$volta2,$sair)
{ # função que recebe o código do médico e exibe o registro.
    global $link;
    $cmdsql="SELECT O.*, 
                    L.txnomelogradouro      AS nomelogradouro, 
                    LT.txnometipologradouro AS tipologradouro
                    FROM oficinas AS O INNER JOIN logradouros      AS L    ON O.celogradouro     = L.cplogradouro
                                       INNER JOIN logradourostipos AS LT   ON L.cetipologradouro = LT.cptipologradouro
                    WHERE cpoficina = '$CP'";
    $execcmd=mysqli_query($link,$cmdsql);
    $registro=mysqli_fetch_array($execcmd);

    printf("<table>\n");
    printf("<tr><td colspan=2>Valores informados no form:</td></tr>");
    printf("<tr><td>Nome:</td>        <td>$registro[txnomeoficina]</td></tr>");
    printf("<tr><td>Apelido:</td>     <td>$registro[txapelido]</td></tr>");
    printf("<tr><td>Logradouro:</td>  <td>$registro[tipologradouro] $registro[nomelogradouro]-($registro[celogradouro])</td></tr>");
    printf("<tr><td>Complemento:</td> <td>$registro[txcomplemento]</td></tr>");
    printf("<tr><td>CEP:</td>         <td>$registro[nucep]</td></tr>");
    printf("<tr><td>Data de cadastro: </td> <td>$registro[dtcadoficina]</td></tr>");
    printf("<tr><td></td>             <td>");
    botoes($acao,$limpa,$volta1,$volta2,$sair);
    printf("</td></tr>\n");
    printf("</table>\n");
}

function picklist($acao)
{ # 
    Global $link;
    $prg=($acao=="C") ? "oficinasconsultar.php" : (($acao=="A") ? "oficinasalterar.php" : "oficinasexcluir.php" ) ;
    printf("  <form action='./$prg' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    printf("  <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    # Lendo os dados de medicos para montar uma picklist de escolha do medico a consultar
    $cmdsql="SELECT cpoficina, txtnomeoficina FROM oficinas ORDER BY txtnomeoficina";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);

    printf("<select name='cpoficina'>\n");
    
    while ( $registro=mysqli_fetch_array($execcmd) )
    { # laço para 'montar' as linhas de option da picklist
      printf("<option value='$registro[cpoficina]'>$registro[txnomeoficina]-($registro[cpoficina])</option>");
    }
    printf("</select>\n");
    $bt=($acao=="C") ? "Consultar" : (($acao=="A") ? "Alterar" : "Excluir") ;
    botoes("$bt",TRUE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("  </form>\n");
}
/*
    printf("  <form action='./oficinasconsultar.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    # Lendo os dados de medicos para montar uma picklist de escolha do medico a consultar
    $cmdsql="SELECT cpoficina, txnomeoficina FROM oficinas ORDER BY txnomeoficina";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='cpoficina'>");
    ''
    while ( $registro=mysqli_fetch_array($execcmd) )
    { # laço para 'montar' as linhas de option da picklist
      printf("<option value='$registro[cpoficina]'>$registro[txnomeoficina]-($registro[cpoficina])</option>");
    }
    printf("</select>\n");
    botoes("Consultar",TRUE,1,2,0);
    printf("  </form>\n");
*/

function montamenu($tabela,$acao,$salto)
{
  printf("<div>");
  printf(" <div class='menu'>");
  printf(" <form action='' method='POST'>\n");
  printf("  <input type='hidden' name='salto' value='$salto'>\n");
  printf("  <negrito>$tabela:</negrito>");
  printf("  <button type='submit' formaction='./oficinasincluir.php'>Incluir</button>");
  printf("  <button type='submit' formaction='./oficinasconsultar.php'>Consultar</button>");
  printf("  <button type='submit' formaction='./oficinasalterar.php'>Alterar</button>");
  printf("  <button type='submit' formaction='./oficinasexcluir.php'>Excluir</button>");
  printf("  <button type='submit' formaction='./oficinaslistar.php'>Listar</button>");
  printf(" </form>\n");
  printf("</div>\n");
  printf(" <titulo>$acao</titulo>");
  printf("</div><br><br><hr>");
}

?>
