<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Invoice;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class TestController extends AbstractController
{
    #[Route('/test-pdf', name: 'app_test')]
    public function tryPDF(UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        try{
            $data = [
                'products' => [
                    ['name' => 'Product 1', 'price' => 100],
                    ['name' => 'Product 2', 'price' => 200],
                    ['name' => 'Product 3', 'price' => 300],
                ],
                'user' => ['email' => 'user@email.com'],
                'total' => 600,
            ];
            $filepath = CommonController::generateInvoicePdf($data);
            $invoice = new Invoice();
            $invoice->setFileUrl($filepath);
            $invoice->setCreatedAt(new \DateTimeImmutable());
            $user = $userRepo->findOneBy(['email' => $data['user']['email']]);
            $invoice->setUser($user);
            $em->persist($invoice);
            $em->flush();

            return new Response('PDF generated successfully at: '.$filepath);

        } catch (\Exception $e) {
            return new Response('Error generating PDF: '.$e->getMessage());
        }
    }
}
