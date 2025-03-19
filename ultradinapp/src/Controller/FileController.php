<?php
namespace App\Controller;

use App\Repository\AdminFileRepository;
use App\Service\AzureBlobStorageService;
use App\Services\AzureBlobService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\AdminFile;

#[Route('/file', name: 'app_file_')]
class FileController extends AbstractController
{
    #[Route('/download/admin/{id}', name: 'download')]
    public function download(
        int $id,
        AzureBlobService $azureService,
        AuthorizationCheckerInterface $authCheck,
        AdminFileRepository $adminFileRepository,
        AdminFile $file
    ): StreamedResponse {
        // Check if user is authorized
        // if (!$authCheck->isGranted('ROLE_ADMIN')) {
        //     return new StreamedResponse(function () {
        //         echo json_encode(['error' => 'Unauthorized']);
        //     }, 401, [
        //         'Content-Type' => 'application/json'
        //     ]);
        // }
    
        // Retrieve the file record from the database
        $file = $adminFileRepository->find($id);
        
        if (!$file) {
            return new StreamedResponse(function () {
                echo json_encode(['error' => 'File not found']);
            }, 404, [
                'Content-Type' => 'application/json'
            ]);
        }
    
        // Get the filename and extension
        $filename = $file->getFileUrl();
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
    
        // Download the file from Azure Blob Storage
        $fileStream = $azureService->streamBlob('pdfgestion', $filename);
    
        // If no file stream is returned, throw an error
        if (!$fileStream) {
            throw $this->createNotFoundException('File not found on Azure');
        }
    
        // Determine MIME type based on file extension
        $mimeType = 'application/octet-stream'; // Default MIME type
        if ($fileExtension === 'pdf') {
            $mimeType = 'application/pdf';
        } elseif ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
            $mimeType = 'image/jpeg';
        } elseif ($fileExtension === 'png') {
            $mimeType = 'image/png';
        }
    
        // Return a StreamedResponse to send the file to the browser
        return new StreamedResponse(function () use ($fileStream) {
            fpassthru($fileStream); // Output the file stream to the response
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }


}
