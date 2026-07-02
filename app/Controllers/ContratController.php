<?php

namespace App\Controllers;

use App\Models\ContratModel;
use App\Models\EntrepriseModel;
use App\Models\StatutModel;
use Dompdf\Dompdf;
use Dompdf\Options;


class ContratController extends BaseController
{
    protected ContratModel $contratModel;
    protected EntrepriseModel $entrepriseModel;
    protected StatutModel $statutModel;

    public function __construct()
    {
        $this->contratModel    = new ContratModel();
        $this->entrepriseModel = new EntrepriseModel();
        $this->statutModel     = new StatutModel();
    }

    
    private function regles(): array
    {
        return [
            'sujet'         => 'required|max_length[200]',
            'entreprise_id' => 'required|is_natural_no_zero',
            'statut_id'     => 'required|is_natural_no_zero',
        ];
    }

    private function messages(): array
    {
        return [
            'sujet' => [
                'required' => 'Le sujet est obligatoire.',
            ],
            'entreprise_id' => [
                'required'           => "L'entreprise est obligatoire.",
                'is_natural_no_zero' => 'Veuillez sélectionner une entreprise valide.',
            ],
            'statut_id' => [
                'required'           => 'Le statut est obligatoire.',
                'is_natural_no_zero' => 'Veuillez sélectionner un statut valide.',
            ],
        ];
    }

    private function estStatutSigne(?array $statut): bool
    {
        $nom = trim((string) ($statut['nom'] ?? ''));
        $nomAscii = function_exists('iconv')
            ? @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom)
            : $nom;

        if ($nomAscii === false) {
            $nomAscii = $nom;
        }

        $nomNormalise = preg_replace('/[^a-z]/', '', strtolower($nomAscii));

        return strpos($nomNormalise, 'sign') === 0;
    }

    private function dateSignatureAutomatique(?array $contratActuel, int $nouveauStatutId): ?string
    {
        $nouveauStatut = $this->statutModel->find($nouveauStatutId);

        if (! $this->estStatutSigne($nouveauStatut)) {
            return $contratActuel['date_signature'] ?? null;
        }

        return $contratActuel['date_signature'] ?? date('Y-m-d');
    }

    
    public function index()
    {
        return view('contrat/index', [
            'titre'    => 'Contrats',
            'contrats' => $this->contratModel->getListe(),
            'statuts'  => $this->statutModel->findAll(),
        ]);
    }

    public function ajout()
    {
        return view('contrat/ajout', [
            'titre'       => 'Ajouter un contrat',
            'entreprises' => $this->entrepriseModel->orderBy('nom', 'ASC')->findAll(),
            'statuts'     => $this->statutModel->findAll(),
        ]);
    }

    public function save()
    {
        if (! $this->validate($this->regles(), $this->messages())) {
            return view('contrat/ajout', [
                'titre'       => 'Ajouter un contrat',
                'entreprises' => $this->entrepriseModel->orderBy('nom', 'ASC')->findAll(),
                'statuts'     => $this->statutModel->findAll(),
                'validation'  => $this->validator,
                'contrat'     => $this->request->getPost(),
            ]);
        }

        $contratId = $this->contratModel->insert([
            'sujet'           => $this->request->getPost('sujet'),
            'entreprise_id'   => $this->request->getPost('entreprise_id'),
            'description'     => $this->request->getPost('description'),
            'statut_id'       => $this->request->getPost('statut_id'),
            'date_signature'  => $this->dateSignatureAutomatique(null, (int) $this->request->getPost('statut_id')),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
        ], true);

        return redirect()->to('/contrat/detail/' . $contratId)->with('succes', 'Contrat créé avec succès.');
    }

    public function modifier(int $id)
    {
        $contrat = $this->contratModel->find($id);

        if (! $contrat) {
            return redirect()->to('/contrat')->with('erreur', 'Contrat introuvable.');
        }

        return view('contrat/modifier', [
            'titre'       => 'Modifier le contrat',
            'contrat'     => $contrat,
            'entreprises' => $this->entrepriseModel->orderBy('nom', 'ASC')->findAll(),
            'statuts'     => $this->statutModel->findAll(),
        ]);
    }

    
    public function update(int $id)
    {
        if (! $this->validate($this->regles(), $this->messages())) {
            $contrat       = $this->request->getPost();
            $contrat['id'] = $id;

            return view('contrat/modifier', [
                'titre'       => 'Modifier le contrat',
                'validation'  => $this->validator,
                'contrat'     => $contrat,
                'entreprises' => $this->entrepriseModel->orderBy('nom', 'ASC')->findAll(),
                'statuts'     => $this->statutModel->findAll(),
            ]);
        }

        $contratActuel = $this->contratModel->find($id);

        if (! $contratActuel) {
            return redirect()->to('/contrat')->with('erreur', 'Contrat introuvable.');
        }

        $nouveauStatutId = (int) $this->request->getPost('statut_id');

        $this->contratModel->update($id, [
            'sujet'           => $this->request->getPost('sujet'),
            'entreprise_id'   => $this->request->getPost('entreprise_id'),
            'description'     => $this->request->getPost('description'),
            'statut_id'       => $nouveauStatutId,
            'date_signature'  => $this->dateSignatureAutomatique($contratActuel, $nouveauStatutId),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
        ]);

        return redirect()->to('/contrat/detail/' . $id)->with('succes', 'Contrat modifié avec succès.');
    }

    
    public function detail(int $id)
    {
        $contrat = $this->contratModel->getDetail($id);

        if (! $contrat) {
            return redirect()->to('/contrat')->with('erreur', 'Contrat introuvable.');
        }

        return view('contrat/detail', [
            'titre'   => 'Détail du contrat',
            'contrat' => $contrat,
        ]);
    }

    
    public function recherche()
    {
        $recherche = trim((string) $this->request->getGet('q'));
        $statutGet = $this->request->getGet('statut');
        $statutId  = ($statutGet !== null && $statutGet !== '') ? (int) $statutGet : null;

        return view('contrat/tableau', [
            'contrats' => $this->contratModel->getListe($recherche, $statutId),
        ]);
    }

    
    public function pdf(int $id)
    {
        $contrat = $this->contratModel->getDetail($id);

        if (! $contrat) {
            return redirect()->to('/contrat')->with('erreur', 'Contrat introuvable.');
        }

        $html = view('contrat/pdf', ['contrat' => $contrat]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nomFichier = 'contrat_' . $contrat['id'] . '.pdf';

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $nomFichier . '"')
            ->setBody($dompdf->output());
    }
}
