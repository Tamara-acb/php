<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcoes (medicosfuncoes.php)
# Descricao...: Armazena as funções de sistema específicas para a tabela medicos.
# Autor.......: JMHypólito - Copie mas diga quem fez
# Objetivo....: Servir de exemplo para estudo do desenvolvimento de PA.
# Criacao.....: 2021-04-15
# Atualizacao.: 2021-04-15 - Estruturação geral. Escrita da funcionalidade de consulta.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function mostraregistro($CP,$acao,$limpa,$volta1,$volta2,$sair)
{ # função que recebe o código do médico e parâmetros da funções botoes (disponível no ToolsKit.php),
  # a função pesquisa os dados relacionados com o registro do médico e retorna detalhes do registro (de médicos e relacinados)..
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
                    WHERE cpmedico='$CP'";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);
    $registro=mysqli_fetch_array($execcmd);
    printf("<table>\n");
    printf("<tr><td>Código:</td>     <td>$registro[cpmedico]</td></tr>\n");
    printf("<tr><td>Nome:</td>       <td>$registro[txnomemedico]</td></tr>\n");
    printf("<tr><td>NuCRM:</td>      <td>$registro[nucrm]</td></tr>\n");
    printf("<tr><td>Situação:</td>   <td>%s</td></tr>\n",$registro['aosituacao']=="A" ? "Ativado" : "Desativado");
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
    printf("<tr><td></td>            <td>");
    # Os parâmetros de botoes são ($acao,$limpar,$voltar1,$voltar2,$sair)
    botoes($acao,$limpa,$volta1,$volta2,$sair);
    printf("</td></tr>\n");
    printf("</table>\n");
}
function picklist($acao)
{ # 
    Global $link;
    $prg=($acao=="C") ? "medicosconsultar.php" : (($acao=="A") ? "medicosalterar.php" : "medicosexcluir.php" ) ;
    printf("  <form action='./$prg' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    printf("  <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    # Lendo os dados de medicos para montar uma picklist de escolha do medico a consultar
    $cmdsql="SELECT cpmedico, txnomemedico, ceespecialidade, txnomeespecialidade FROM medicos AS m inner join especmedicas AS e on m.ceespecialidade=e.cpespecialidade ORDER BY e.txnomeespecialidade, m.txnomemedico";
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($link,$cmdsql);
    printf("<select name='cpmedico'>\n");
    $ceespec="";
    while ( $registro=mysqli_fetch_array($execcmd) )
    { # laço para 'montar' as linhas de option da picklist
      if ( $ceespec!=$registro['ceespecialidade'] )
      {
        if ( $ceespec!="" )
        {
            printf("</optgroup>\n");
        }
        printf("<optgroup label='$registro[txnomeespecialidade]'>\n");
        $ceespec="$registro[ceespecialidade]";
          
      }
      printf("<option value='$registro[cpmedico]'>$registro[txnomemedico]-$ceespec-($registro[cpmedico])</option>\n");
    }
    printf("</optgroup>\n");
    printf("</select>\n");
    $bt=($acao=="C") ? "Consultar" : (($acao=="A") ? "Alterar" : "Excluir") ;
    botoes("$bt",TRUE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("  </form>\n");
}
function montamenu($tabela,$acao,$salto)
{
  printf("<div>");
  printf(" <div class='menu'>");
  printf(" <form action='' method='POST'>\n");
  printf("  <input type='hidden' name='salto' value='$salto'>\n");
  printf("  <negrito>$tabela:</negrito>");
  printf("  <button type='submit' formaction='./medicosincluir.php'>Incluir</button>");
  printf("  <button type='submit' formaction='./medicosconsultar.php'>Consultar</button>");
  printf("  <button type='submit' formaction='./medicosalterar.php'>Alterar</button>");
  printf("  <button type='submit' formaction='./medicosexcluir.php'>Excluir</button>");
  printf("  <button type='submit' formaction='./medicoslistar.php'>Listar</button>");
  printf(" </form>\n");
  printf("</div>\n");
  printf(" <titulo>$acao</titulo>");
  printf("</div><br><br><hr>");
}
?>
