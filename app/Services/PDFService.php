<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Gera o arquivo PDF a partir de uma view do Blade.
     *
     * @param string $view Caminho da view (ex: 'pdfs.reserva')
     * @param array $data Dados que serão passados para a view
     * @param string $paper Tamanho do papel (ex: 'a4', 'letter')
     * @param string $orientation Orientação (ex: 'portrait', 'landscape')
     * @return \Barryvdh\DomPDF\PDF
     */
    public function gerarDeView(string $view, array $data = [], string $paper = 'a4', string $orientation = 'portrait')
    {
        return Pdf::loadView($view, $data)
            ->setPaper($paper, $orientation);
    }
}
