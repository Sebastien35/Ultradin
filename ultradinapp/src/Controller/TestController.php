<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Invoice;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;

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

    #[Route('/test-new-order', name: 'app_test_new_order')]
    public function testOrder(ProductRepository $productRepo, EntityManagerInterface $em): Response{
        try{
        $order = new Order();
        $order->setDateConfirmed(new \DateTimeImmutable());
        $order->setOrderUuid(uniqid());
        $order->setStatus('confirmed');
        $order->setEta(new \DateTimeImmutable());
        $order->setUser($this->getUser());
        $order->addProduct($productRepo->find(1));
        $order->addProduct($productRepo->find(2));
        $order->addProduct($productRepo->find(3));
        $price = 0;
        foreach ($order->getProducts() as $product) {
            $price += $product->getPrice();
        }
        $order->setTotalPrice($price);
        
        $em->persist($order);
        $em->flush();
        } catch (\Exception $e) {
            return new Response('Error creating order: '.$e->getMessage());
        }
        try{
        $data = [
            'products' => array_map(function($product){
                return ['name' => $product->getName(), 'price' => $product->getPrice()];
            }, $order->getProducts()->toArray()),
            'user' => ['email' => $order->getUser()->getEmail()],
            'total' => $price,
        ];
    } catch (\Exception $e) {
        return new Response('Error creating invoice data: '.$e->getMessage());
    }
    try{
        $filepath = CommonController::generateInvoicePdf($data);
        $invoice = new Invoice();
        $invoice->setFileUrl($filepath);
        $invoice->setCreatedAt(new \DateTimeImmutable());
        $invoice->setUser($order->getUser());
        $em->persist($invoice);
        $em->flush();
        return new Response('Order created successfully');  
    } catch (\Exception $e) {
        return new Response('Error generating PDF: '.$e->getMessage());
    }
        return new Response('Order created successfully');
    }
}
