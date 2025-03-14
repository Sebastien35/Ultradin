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
use App\Cron\Sales\GenerateSalesReport;
use App\Entity\User;
use App\Repository\AdminFileRepository;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateInterval;

use App\Services\AzureBlobService;

class TestController extends AbstractController
{
    

    #[Route('/test-sales-report', name: 'app_test_sales_report')]
    public function testSalesReport(OrderRepository $orderRepo, ProductRepository $pr, UserRepository $userRepo, AzureBlobService $azureBlob, AdminFileRepository $adminFileRepo): Response{
        try{

            $date = new DateTime();
            $oneWeekAgo = $date->sub(new DateInterval('P7D'));
            $orders = $orderRepo->getOrdersToGenerateSalesReport($oneWeekAgo, new DateTime());
            $topsellers = $pr->getTopSales();
            $arrayData = ['orders' => $orders, 'topSellers' => $topsellers, 'startDate' => $oneWeekAgo, 'endDate' => new DateTime()];
        
            $fileUrl = CommonController::generateSalesReportPdf($arrayData, true, $userRepo, $azureBlob, $adminFileRepo);

           return new Response('Sales report generated successfully');
        } catch (\Exception $e) {
            return new Response('Error generating sales report: '.$e->getMessage());
        }
    }

    

    
    
}
