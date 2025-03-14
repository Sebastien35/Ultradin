<?php
declare(strict_types=1);

namespace App\Services;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Symfony\Component\Dotenv\Dotenv;

class AzureBlobService
{
    private BlobRestProxy $blobClient;


    public function __construct($connectionString)
    {   
        $this->blobClient = BlobRestProxy::createBlobService($connectionString);
    }

    public function uploadBlob(string $containerName, $filepath, $fileName, string $prefix = ''): string
    {
        $contents = file_get_contents($filepath);
        $blobName = $fileName;
        if ('' !== $prefix) {
            $blobName = sprintf(
                '%s/%s',
                rtrim($prefix, '/'),
                $blobName
            );
        }
        $this->blobClient->createBlockBlob(strtolower($containerName), $blobName, $contents);
        $this->blobClient->setBlobProperties(
            strtolower($containerName),
            $blobName,
        );
        return $blobName;
    }

    public function streamBlob(string $containerName, string $blobName): string
    {
        $blob = $this->blobClient->getBlob(strtolower($containerName), $blobName);
        $stream = $blob->getContentStream();
        $contents = stream_get_contents($stream);
        fclose($stream);
        return $contents;
    }

}