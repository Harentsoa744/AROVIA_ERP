<?php

namespace App\Controllers;

use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ValeurStock extends BaseController
{
    public function index()
    {
        return view('valeur_stock/index', $this->buildData());
    }

    public function export()
    {
        $data = $this->buildData();
        $lines = [];
        $lines[] = ['Article', 'Quantité', 'CUMP unitaire', 'Valeur totale'];

        foreach ($data['stockPF'] ?? [] as $bocal) {
            $lines[] = [
                $bocal['nom'] ?? 'Bocal',
                ($bocal['quantite_disponible'] ?? 0) . ' unités',
                number_format($bocal['cout_unitaire'] ?? 0, 0, ',', ' ') . ' Ar',
                number_format($bocal['valeur_comptable'] ?? 0, 0, ',', ' ') . ' Ar',
            ];
        }

        $csv = '';
        foreach ($lines as $line) {
            $csv .= implode(';', array_map(fn($value) => str_replace(["\r", "\n"], ' ', (string) $value), $line)) . PHP_EOL;
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="valeur-stock.csv"')
            ->setBody($csv);
    }

    public function exportPdf()
    {
        $data = $this->buildData();
        $html = view('valeur_stock/pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="valeur-stock.pdf"')
            ->setBody($dompdf->output());
    }

    private function buildData(): array
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();

        $stockMP = $stockMPModel->getEtatStock();
        $stockPF = $stockPFModel->getStockAvecTypes();

        $valeurComptablePF = 0;
        $valeurVentePF     = 0;

        foreach ($stockPF as &$bocal) {
            $coutUnitaire = (($stockMP['cump_actuel'] ?? 0) * ($bocal['volume_litres'] ?? 0));
            $bocal['cout_unitaire']    = $coutUnitaire;
            $bocal['valeur_comptable'] = $coutUnitaire * ($bocal['quantite_disponible'] ?? 0);
            $bocal['valeur_vente']     = ($bocal['prix_vente'] ?? 0) * ($bocal['quantite_disponible'] ?? 0);

            $valeurComptablePF += $bocal['valeur_comptable'];
            $valeurVentePF     += $bocal['valeur_vente'];
        }
        unset($bocal);

        return [
            'stockMP'               => $stockMP,
            'stockPF'               => $stockPF,
            'valeurComptablePF'     => $valeurComptablePF,
            'valeurVentePF'         => $valeurVentePF,
            'valeurTotaleComptable' => ($stockMP['valeur_stock'] ?? 0) + $valeurComptablePF,
        ];
    }
}