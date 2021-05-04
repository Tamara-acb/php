<?php
#################################################################################################################################
# Programa...: medicosincluir (medicosincluir.php)
# Descrição..: Inclui a execução de arquivo externo (require_once()), identifica valor de variável de controle de recursividade
#              e apresenta dois blocos lógicos de programação: Um blobo para montagem de um formulário para dados de médicos e
#              um bloco para controlar o tratamento de uma trransação.
# Ojetivo....: Gerenciar a funcionalidade "incluir" dados na tabela medicos.
# Autor......: JMH
# Criação....: 2021-04-12
# Atualização: 2021-04-12 - Primeira escrita e montagem da estrutura geral. Refinamos o formulário de entrada de dados e 
#                           estruturamos o tratamento de transação.
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
require_once("./medicosfuncoes.php");
# Usando o operador ternário
$bloco=( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1;
# monstrando o valor de $bloco em cada execução
# printf("$bloco<br>\n");
iniciapagina(TRUE,"Médicos","Incluir");
$_REQUEST['salto']=$_REQUEST['salto']+1;
montamenu("M&eacute;dicos","Incluir","$_REQUEST[salto]");
# Determinando qual bloco será executado no PA
switch (TRUE)
{
  case ($bloco==1):
  { # montando o form
    printf("  <form action='medicosincluir.php' method='POST'>\n");
    printf("  <input type='hidden' name='bloco' value='2'>\n");
    printf("  <input type='hidden' name='salto' value='$_REQUEST[salto]'>\n");
    printf("  <table>\n");
    printf("   <tr><td>Código:</td><td>O código será gerado pelo Sistema</td></tr>\n");
    printf("   <tr><td>Nome:</td><td><input type='text' name='txnomemedico' placeholder='' size=40 maxlength=200></td></tr>\n");
    printf("   <tr><td>NuCRM:</td><td><input type='text' name='nucrm' placeholder='' size=8 maxlength=8>-Somente números</td></tr>\n");
    printf("   <tr><td>Situação:</td><td><input type='radio' name='aosituacao' value='A' checked>-Ativado | <input type='radio' name='aosituacao' value='D'>-Desativado</td></tr>\n");
    # Montando a Picklist para a Especialidade Médica (tabela: especmedicas)
    printf("<tr><td>Especialidade:</td><td>");
    mostrafklist('cpespecialidade','txnomeespecialidade','especmedicas','ceespecialidade','');
    printf("</td></tr>\n");
    # Montando a Picklist para a Escola de Formação do médico (tabela: escolas)
    printf("<tr><td>Escola:</td><td>");
    mostrafklist('cpescola','txnomeescola','escolas','ceinstituicao','');
    printf("</td></tr>\n");
    # Montando a Picklist para a Logradouro da Clínica do Médico (tabela: logradouros+logradourostipos na visão: logradourocompleto)
    printf("<tr><td>Clínica:</td><td>");
    mostrafklist('cplogradouro','txnomelogrcompleto','logradourocompleto','celogradouroclinica','');
    printf("</td></tr>\n");
    printf("<tr><td></td><td>Complemento Clínica<br><input type='text' name='txcomplementoclinica' size='50' maxlength='50'></td></tr>\n");
    printf("<tr><td></td><td>CEP<input type='text' name='nucepclinica' size='8' maxlength='8'></td></tr>\n");
    # Montando a Picklist para a Logradouro da Moradia do Médico (tabela: logradouros+logradourostipos na visão: logradourocompleto)
    printf("<tr><td>Moradia:</td><td>");
    mostrafklist('cplogradouro','txnomelogrcompleto','logradourocompleto','celogradouromoradia','');
    printf("</td></tr>\n");
    printf("   <tr><td></td><td>Complemento moradia<br><input type='text' name='txcomplementomoradia' size='50' maxlength='50'></td></tr>\n");
    printf("   <tr><td></td><td>CEP<input type='text' name='nucepmoradia' size='8' maxlength='8'></td></tr>\n");
    printf("   <tr><td>Telefone:</td><td><input type='text' name='nutelefone' size='8' maxlength='8'></td></tr>\n");
    printf("   <tr><td>Cadastrado em:</td><td><input type='date' name='dtcadmedico'></td></tr>\n");
    printf("   <tr><td></td><td>");
    botoes('Incluir',TRUE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    printf("</td></tr>\n");
    printf("  </table>\n");
    printf("  </form>\n");
    break;
  }
  case ($bloco==2):
  { # Tratamento da transação.
    # lendo os valores digitados nos campos do form
    # a transação se inicia com o comando: START TRANSACTION
    # a transação deve ser executada 'dentro' de um laço de repetição.
    $mostrar=FALSE;
    $tenta=TRUE;
    while ( $tenta )
    { # laço de controle de exec da trans.
      mysqli_query($link,"START TRANSACTION");
      # construção do comando de atualização.
      # recuperação do último valor gravado na PK da tabela
      $ultimacp=mysqli_fetch_array(mysqli_query($link,"SELECT MAX(cpmedico) AS CpMAX FROM medicos"));
      $CP=$ultimacp['CpMAX']+1;
      # Construção do comando de atualização.
      $cmdsql="INSERT INTO medicos (cpmedico,txnomemedico,nucrm,ceespecialidade,ceinstituicao,
                                    celogradouromoradia,txcomplementomoradia,nucepmoradia,
                                    celogradouroclinica,txcomplementoclinica,nucepclinica,nutelefone,aosituacao,dtcadmedico)
                      VALUES ('$CP',
                              '$_REQUEST[txnomemedico]',
                              '$_REQUEST[nucrm]',
                              '$_REQUEST[ceespecialidade]',
                              '$_REQUEST[ceinstituicao]',
                              '$_REQUEST[celogradouromoradia]',
                              '$_REQUEST[txcomplementomoradia]',
                              '$_REQUEST[nucepmoradia]',
                              '$_REQUEST[celogradouroclinica]',
                              '$_REQUEST[txcomplementoclinica]',
                              '$_REQUEST[nucepclinica]','$_REQUEST[nutelefone]',
                              '$_REQUEST[aosituacao]',
                              '$_REQUEST[dtcadmedico]')";
      # printf("$cmdsql<br>\n");
      # execução do cmd.
      mysqli_query($link,$cmdsql);
      # tratamento dos erros de exec do cmd.
      if ( mysqli_errno($link)==0 )
      { # trans pode ser concluída e não reiniciar
        mysqli_query($link,"COMMIT");
        $tenta=FALSE;
        $mostrar=TRUE;
        $mens="Registro incluído! Valor do Código: $CP";
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
    if ( $mostrar )
    {
      mostraregistro("$CP",'',FALSE,$_REQUEST['salto']-2,$_REQUEST['salto']-1,"$_REQUEST[salto]");
    }
    break;
  }
}
terminapagina("médicos","Incluir","medicosincluir.php");
?>
