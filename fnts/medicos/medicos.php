<?php
#################################################################################################################################
# Programa...: medicos (medicos.php)
# Descrição..: Inclui a execução de arquivo externo (require_once()), identifica valor de variável de controle de recursividade
#              e apresenta dois blocos lógicos de programação: Um blobo para montagem de um formulário para dados de médicos e
#              um bloco para controlar o tratamento de uma trransação.
# Ojetivo....: Adminitra acesso às funcionalidades de gerenciamento de dados de medicos, monta um menu e
#              explica ao usuário como o sistema pode ser usado.
# Autor......: JMH
# Criação....: 2021-04-12
# Atualização: 2021-04-12 - Primeira escrita e montagem da estrutura geral. Refinamos o formulário de entrada de dados e 
#                           estruturamos o tratamento de transação.
#              2021-04-08 - Uso de acesso à arq. ext., uso de funções no toolskit.php
#################################################################################################################################
# Referenciando o arquivo toolskit.php
require_once("../../fncs/toolskit.php");
# Referenciando o arquivo medicosfuncoes.php
require_once("./medicosfuncoes.php");
# Determinando o valor de $salto (variável usada para contar os "cliques" de avanço na aplicação).
$salto=( ISSET($_REQUEST['salto']) ) ? $_REQUEST['salto'] : 1;
# monstrando o valor de $bloco em cada execução
# printf("$bloco<br>\n");
iniciapagina(TRUE,"Médicos","Abertura");
montamenu("M&eacute;dicos","Abertura",$salto);
printf("Este sistema faz o Gerenciamento de dados da Tabela medicos.<br>
O menu apresentado acima indica as funcionalidades do sistema.<br>\n");
terminapagina("médicos","Médicos","medicos.php");
?>
