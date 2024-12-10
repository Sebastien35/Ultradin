<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;

require_once dirname(__DIR__, 2) . '/config/Globals.php';

class CommonController
{
    public static function generateInvoicePdf(array $data): string
    {
        if (empty($data['products']) || empty($data['user']) || !isset($data['total'])) {
            throw new \InvalidArgumentException('Invalid invoice data provided.');
        }

        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                h1, h2 { color: #333; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                table, th, td { border: 1px solid #ddd; }
                th, td { padding: 10px; text-align: left; }
                th { background-color: #f4f4f4; }
                .total { font-weight: bold; }
                .company-info, .footer-info { font-size: 12px; margin-top: 20px; color: #666; }
                .header { margin-bottom: 20px; }
                .header img { max-width: 150px; margin-bottom: 10px; }
                .customer-info, .invoice-info { margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . COMPANY_NAME . '</h1>
                <p>' . COMPANY_ADDRESS . '<br>
                Email: ' . COMPANY_EMAIL . '<br>
                Phone: ' . COMPANY_PHONE . '</p>
            </div>
            
            <h2>Invoice</h2>
            <div class="invoice-info">
                <p><strong>Invoice Date:</strong> ' . date('Y-m-d H:i:s') . '</p>
                <p><strong>Customer:</strong> ' . htmlspecialchars($data['user']['email']) . '</p>
            </div>

            <h2>Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (€)</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data['products'] as $product) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($product['name']) . '</td>
                        <td>' . number_format($product['price'], 2) . '</td>
                    </tr>';
        }

        $html .= '</tbody>
            </table>
            <p class="total"><strong>Total:</strong> ' . number_format($data['total'], 2) . ' €</p>

            <div class="footer-info">
                <p><strong>' . COMPANY_LEGAL . '</strong><br>
                SIRET: ' . COMPANY_SIRET . ' | TVA: ' . COMPANY_TVA . '<br>
                Capital: ' . COMPANY_CAPITAL . ' | RCS: ' . COMPANY_RCS . '<br>
                APE Code: ' . COMPANY_APE . '</p>
            </div>
        </body>
        </html>';

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $directory = '../public/invoices/';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $filePath = $directory . 'invoice_' . time() . '.pdf';
        file_put_contents($filePath, $dompdf->output());

        return $filePath;
    }

}
