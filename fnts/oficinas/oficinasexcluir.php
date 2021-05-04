<?php
#################################################################################################################################
# Programa...: medicosexcluir (medicosexcluir.php)
# Descrição..: Inclui a execução de arquivo externo (require_once()), identifica valor de variável de controle de recursividade
#              e apresenta dois blocos lógicos de programação: Um blobo para montagem de um formulário para dados de médicos e
#              um bloco para exibir od dados digitados. 
# Ojetivo....: Apresentar o conceito de programação recursiva com uso de funções e com acesso a arquivos externos.
# Autor......: JMH
# Criação....: 2021-04-01
# Atualização: 2021-04-01 - Primeira escrita e montagem da estrutura geral
#              2021-04-08 - Uso de acesso à arq. ext., uso de funções no toolskit.php
#################################################################################################################################
# Este é um exemplo de um programa recursivo
# Ele irá 'chamar' a ele mesmo... ou 'carregará' a ele mesmo...
# como um programa sabe que foi executado pela primeira vez em um ciclo recursivo?
# A PHP usa um conjunto de variáveis de ambiente (globais) que estão 'vazias'
# sempre que se inicia a execução do PA (Programa Aplicativo).
# então não existe valor em nenhuma posição de $_REQUEST[]
# Na PHP existem um conjunto de funções de ambiente
# Entre elas a função ISSET() recebe o nome de uma variável e
# retorna TRUE ou FALSE.
#################################################################################################################################
# Acessando arquivos externos.
# A PHP tem dois modos básicos que permitem incluir na execução de um PA outros arquivos (com códigos PHP).
# A ideia é interromper um programa e alternar o controle de execução para outro arquivo.
# Em PHP existem os comandos que "abrem" um arquivo externo
# Na PHP existem 2 modos de referenciar arq. externos.
# INCLUDE - muda o controle de execução do PA-Principal para um PA-Secundário. MAS se o PA-Secundário tiver um erro, o PA-Principal NÃO é interrompido.
# REQUIRE - muda o controle de execução do PA-Principal para um PA-Secundário. MAS se o PA-Secundário tiver um erro, o PA-Principal SERÁ interrompido.
# Ambos os comandos tem a possibilidade (em PA-Recursivo) de fazer a leitura uma só vez. Neste caso use o complemento _ONCE
###############
# INCLUDE("local + nome do arquivo"); -> NÃO interrompe o programa principal, se o secundário tem erro.
# REQUIRE("local + nome do arquivo"); -> INTERROMPE o programa principal, se o secundário tem erro.
###############
# Para estes dois comandos existe uma variação que possibilita a abertura e leitura do arquivo secundário somente uma vez.
# include_once("local + nome do arquivo");
# require_once("local + nome do arquivo");

# Os arquivos externos podem conter segmentos de códigos de Funções.
# Funções são segmentos de códigos que podem ser necessárias em "outros" PA-PHP.
# portanto foram "Transferidas" para um Arq. EXTERNO.
# Sendo assim, dentro "deste" PA.... teremos que 'referenciar' aquele arq. externo que contém o segmento de código de funções.
# Como se faz referencia para arq. EXTERNOS.
# _ONCE
# Referenciando o arquivo toolskit.php
require_once("../../fncs/toolskit.php");
# Referenciando o arquivo medicosfuncoes.php
require_once("./oficinasfuncoes.php");
# Usando o operador ternário
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1;
# monstrando o valor de $bloco em cada execução
# printf("$bloco<br>\n");
iniciapagina(TRUE,"Oficinas","Excluir");
$_REQUEST['salto']=$_REQUEST['salto']+1;
montamenu("Oficinas","Excluir","$_REQUEST[salto]");
# Determinando qual bloco será executado no PA
switch (TRUE)
{
  case ($bloco==1):
  { # montando a picklist para escolha do registro para alterar
    picklist("E");
    break;
  }
  case ($bloco==2):
  { # mostra o registro que será excluído e pede confirmação do usuário.
    printf("<form action='oficinasexcluir.php' method='POST'>\n");
    printf(" <input type='hidden' name='bloco' value='3'>\n");
    printf(" <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    printf(" <input type='hidden' name='cpoficina' value='$_REQUEST[cpoficina]'>\n");
    mostraregistro("$_REQUEST[cpoficina]",'Confirmar Excluir',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("</form>");
    break;
  }
  case ($bloco==3):
  { # tratamento da transação.
    # construção do comando de atualização.
    $cmdsql="DELETE FROM Oficinas O WHERE O.cpoficina='$_REQUEST[cpoficina]'";
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
        $mens="Registro com código $_REQUEST[cpoficina] excluído!";
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
          $mens=mysqli_errno($linkmy)."-".mysqli_error($linkmy);
        }
        mysqli_query($link,"ROLLBACK");
        $mostrar=FALSE;
      }
    }
    printf("$mens<br>\n");
    # ESTA É UMA CHAMADA DA FUNÇÃO BOTOES() QUE NÃO TEM SENTIDO O BOTÃO 'VOLTAR', POR ISSO O PARÂMETRO ESTÁ COM '0'.
    botoes('',FALSE,0,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    break;
  }
}
terminapagina("Oficinas","Excluir","oficinasexcluir.php");
?>
