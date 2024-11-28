<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class TestController extends AbstractController
{
    #[Route('/test-pdf', name: 'app_test')]
    public function tryPDF(): Response
    {
        try{
            $data = [
                'products' => [
                    ['name' => 'Product 1', 'price' => 100],
                    ['name' => 'Product 2', 'price' => 200],
                    ['name' => 'Product 3', 'price' => 300],
                ],
                'user' => ['email' => 'email@email.com'],
                'total' => 600,
            ];
            $filepath = CommonController::generateInvoicePdf($data);
            return new Response('PDF generated successfully at: '.$filepath);

        } catch (\Exception $e) {
            return new Response('Error generating PDF: '.$e->getMessage());
        }
    }
}
