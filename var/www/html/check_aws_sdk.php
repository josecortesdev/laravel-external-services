<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

try {
    $s3 = new S3Client([
        'version' => 'latest',
        'region'  => 'us-west-2'
    ]);
    echo "AWS SDK for PHP is installed. Version: " . \Aws\Sdk::VERSION . "\n";
    
    // Intentar listar los buckets para verificar la conexión
    $result = $s3->listBuckets();
    echo "Buckets disponibles:\n";
    foreach ($result['Buckets'] as $bucket) {
        echo $bucket['Name'] . "\n";
    }
} catch (AwsException $e) {
    echo "Error al conectar con AWS: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "AWS SDK for PHP no está instalado.\n";
}
?>
