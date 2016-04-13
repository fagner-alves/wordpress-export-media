<?php 
/*
|--------------------------------------------------------------------------
| WORDPRESS EXPORT MEDIA
|--------------------------------------------------------------------------
|
| Script para exportar arquivos do wordpress, utilizando o xml.
| @autor:   Fagner Alves
| @autor Email : fagner.sa12@htomail.com
|
*/

ini_set('max_execution_time',  0);

//Informe o local do xml exportado do wordpress que contem as urls dos arquivos.
$file    = "wordpress.2016-04-13.xml";

//Informe a parte da url que deseja  buscar para subistituir pelo $replace.
$search = "http://seubblog.files.wordpress.com/";

//Informe o novo caminho a ser susbistituido na url dos arquivos.
$replace = "storage/"

//Carrega o arquivo XML.
$xml = simplexml_load_file($file)->channel;

//Contador, para contabilizar a quantidade de arquivos copiados.
$total = 0;

//Pecorre todos os registros, monta a nova url, verifica se existe os caminhos criados, senão cria os caminhos e faz a copia dos arquivos.
foreach ($xml->item as $key => $value)
{
	$url = explode('/', str_replace($search, $replace, (string) $value->guid));
	array_pop($url);

	if(!file_exists(implode('/', $url))) {
		mkdir(implode('/', $url), 0777, true);
	}

	$source  = fopen((string) $value->guid, "r");
	$destiny = fopen(str_replace($search, $replace, (string) $value->guid), "w");

	while (!feof($source)) {
		$row = fread ($source, 1024);
		fwrite($destiny, $row);
	}

	fclose($source);
	fclose($destiny);

	$total = ($total + 1);
}

echo "Foram copiado {$total} arquivo(s)";
exit;

