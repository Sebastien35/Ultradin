<?php

namespace App\Cron\Sales;
use App\Entity\Order;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;


function generateSalesReport(){
    $manager = new ManagerRegistry();
    $orderRepository = new OrderRepository($manager);


    $date = new DateTime();
    $oneWeekAgo = $date->sub(new DateInterval('P7D'));

    $orders = $orderRepository->getOrdersToGenerateSalesReport($oneWeekAgo, new DateTime());
    
    
    foreach($orders as $order){ 
        ?><pre><?php print_r($order)?></pre><?php
    }
    
    

}