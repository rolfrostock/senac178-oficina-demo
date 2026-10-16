<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit('Execute pelo terminal.');}
$source=dirname(__DIR__);$target='C:/wamp64/www/aula-senac178';
$newDb='senac178_oficina_relacional';$newUser='senac178_rel';
if(!is_file($target.'/config.local.php'))throw new RuntimeException('A instalação anterior não foi localizada.');
$old=require $target.'/config.local.php';
if($old['database']!=='senac178_oficina_demo')throw new RuntimeException('Configuração anterior inesperada; nenhuma substituição foi feita.');
$pdo=new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$query=$pdo->prepare('SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME=?');$query->execute([$newDb]);
if($query->fetchColumn())throw new RuntimeException('O novo banco já existe; preparação interrompida.');
$query=$pdo->prepare('SELECT COUNT(*) FROM mysql.user WHERE User=?');$query->execute([$newUser]);
if((int)$query->fetchColumn())throw new RuntimeException('O novo usuário já existe; preparação interrompida.');
$backup=$target.'/backups/antes-relacional-'.date('Ymd-His');
mkdir($backup,0775,true);file_put_contents($target.'/backups/.htaccess',"Require all denied\n");
$zip=new ZipArchive();$zip->open($backup.'/codigo-anterior.zip',ZipArchive::CREATE|ZipArchive::EXCL);
$iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target,FilesystemIterator::SKIP_DOTS));
foreach($iterator as $file){$rel=str_replace('\\','/',substr($file->getPathname(),strlen($target)+1));if(!$file->isFile()||str_starts_with($rel,'.git/')||str_starts_with($rel,'backups/')||$rel==='config.local.php')continue;$zip->addFile($file->getPathname(),$rel);}
$zip->close();copy($target.'/config.local.php',$backup.'/config.local.php');
$pdo->exec('CREATE DATABASE `'.$newDb.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$pdo->exec('USE `'.$newDb.'`');$pdo->exec(file_get_contents(__DIR__.'/schema.sql'));
$password=bin2hex(random_bytes(24));$account=$pdo->quote($newUser)."@'127.0.0.1'";
$pdo->exec('CREATE USER '.$account.' IDENTIFIED BY '.$pdo->quote($password));
$pdo->exec('GRANT SELECT,INSERT,UPDATE,DELETE ON `'.$newDb.'`.* TO '.$account);
$iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source,FilesystemIterator::SKIP_DOTS));
foreach($iterator as $file){$rel=str_replace('\\','/',substr($file->getPathname(),strlen($source)+1));if(!$file->isFile()||str_starts_with($rel,'.git/')||str_starts_with($rel,'backups/')||$rel==='config.local.php')continue;$dest=$target.'/'.$rel;if(!is_dir(dirname($dest)))mkdir(dirname($dest),0775,true);copy($file->getPathname(),$dest);}
$config=['host'=>'127.0.0.1','port'=>3306,'database'=>$newDb,'username'=>$newUser,'password'=>$password];
file_put_contents($target.'/config.local.php',"<?php\nreturn ".var_export($config,true).";\n");
echo "Preparação relacional concluída. O banco anterior permanece intacto.\n";
echo "Novo banco: ".$newDb."\nURL: http://localhost/aula-senac178/\nBackup local protegido: ".$backup."\n";
