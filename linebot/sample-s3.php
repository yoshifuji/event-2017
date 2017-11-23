<?php
require './lib/aws-autoloader.php';

use Aws\Credentials\CredentialProvider;
$ini = './credentials.ini';
$iniProvider = CredentialProvider::ini('fuyufes-s3', $ini);
$iniProvider = CredentialProvider::memoize($iniProvider);

$client = new Aws\S3\S3Client([
    'region'   => 'ap-northeast-1',
    'version'  => '2006-03-01',
    'credentials' => $iniProvider
]);

//画像ファイルをバケットに入れる。
$bucketName = 'fuyufes2017';
$keyName = 'sample.jpg';
#$srcFile = '/path/to/5a0c1a06738f0.jpg';
$srcFile = '5a0c1a06738f0.jpg';

$client->putObject([
    'Bucket' => $bucketName,
    'Key' => $keyName,
    'SourceFile' => $srcFile,
    'ContentType'=> mime_content_type($srcFile)
]);
