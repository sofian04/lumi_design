<?php

namespace App\Service;

use App\Entity\Order;
use Dompdf\Dompdf;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class InvoiceService
{

    public function __construct(private Environment $twig, private ParameterBagInterface $parameterBag) {}

    public function generateInvoice(Order $order)
    {
        $invoice = $this->twig->render('front/invoice/template.html.twig', [
            'order' => $order
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($invoice);
        $dompdf->render();

        return $dompdf->output();
    }

    public function saveInvoice(Order $order, $pdf)
    {
        $fileName = $order->getOrderNumber() . '.pdf';
        $fileDir =  $this->parameterBag->get('kernel.project_dir') . '/public/invoices/';
        $filePath = $fileDir . $fileName;

        file_put_contents($filePath, $pdf);
    }
}
