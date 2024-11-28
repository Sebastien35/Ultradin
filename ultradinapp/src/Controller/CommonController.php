<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;

class CommonController
{
    public static function generateInvoicePdf(array $data): string
    {
        // Validate input
        if (empty($data['products']) || empty($data['user']) || !isset($data['total'])) {
            throw new \InvalidArgumentException('Invalid invoice data provided.');
        }

        // Build the HTML for the invoice
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                table, th, td { border: 1px solid #ddd; }
                th, td { padding: 10px; text-align: left; }
                th { background-color: #f4f4f4; }
                .total { font-weight: bold; }
            </style>
        </head>
        <body>
            <h1>Invoice</h1>
            <p><strong>Customer:</strong> '.$data['user']['email'].'</p>
            <p><strong>Invoice Date:</strong> '.date('Y-m-d H:i:s').'</p>
            <h2>Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['products'] as $product) {
            $html .= '<tr>
                        <td>'.$product['name'].'</td>
                        <td>'.$product['price'].'</td>
                      </tr>';
        }

        $html .= '</tbody>
            </table>
            <p class="total"><strong>Total:</strong> '.$data['total'].'</p>
        </body>
        </html>';

        // Configure Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Save the generated PDF to a file
        $directory = '../public/invoices/';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create directory if it doesn't exist
        }
        $filePath = $directory.'invoice_'.time().'.pdf';
        file_put_contents($filePath, $dompdf->output());

        return $filePath; // Return the file path
    }
}
