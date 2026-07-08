<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      color: #222222;
      font-size: 13px;
      line-height: 1.5;
    }
    h1 {
      color: #c8860a;
      border-bottom: 2px solid #c8860a;
      padding-bottom: 8px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th {
      background-color: #f9f9f9;
      color: #c8860a;
      font-weight: bold;
      text-align: left;
      border-bottom: 2px solid #dddddd;
      padding: 8px;
    }
    td {
      padding: 8px;
      border-bottom: 1px solid #eeeeee;
      vertical-align: middle;
    }
    .kpi-container {
      margin-top: 10px;
      margin-bottom: 25px;
    }
    .kpi-box {
      display: inline-block;
      width: 45%;
      background: #fdfaf4;
      border: 1px solid #f2e6cf;
      padding: 10px;
      margin-right: 15px;
      margin-bottom: 10px;
      border-radius: 4px;
    }
    .kpi-label {
      font-size: 11px;
      color: #777777;
    }
    .kpi-value {
      font-size: 16px;
      font-weight: bold;
      color: #c8860a;
      margin-top: 4px;
    }
    .total-bar {
      margin-top: 25px;
      background-color: #fdfaf4;
      border-top: 2px solid #c8860a;
      padding: 12px;
      font-size: 15px;
      font-weight: bold;
      text-align: right;
    }
  </style>
</head>
<body>

  <h1>Valorisation du Stock de Miel — Arovia</h1>

  <div class="kpi-container">
    <div class="kpi-box">
      <div class="kpi-label">Stock matière (L)</div>
      <div class="kpi-value"><?= number_format($stockMP['quantite_litres'] ?? 0, 2) ?> L</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-label">CUMP (Ar/L)</div>
      <div class="kpi-value"><?= number_format($stockMP['cump_actuel'] ?? 0, 0, ',', ' ') ?> Ar</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-label">Bocaux en stock</div>
      <div class="kpi-value"><?= count($stockPF ?? []) ?> types</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-label">Valeur totale comptable</div>
      <div class="kpi-value"><?= number_format($valeurTotaleComptable ?? 0, 0, ',', ' ') ?> Ar</div>
    </div>
  </div>

  <h2>Détail de valorisation des produits finis</h2>
  <table>
    <thead>
      <tr>
        <th>Article (Type de bocal)</th>
        <th>Quantité en stock</th>
        <th>Coût unitaire</th>
        <th>Valeur comptable</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($stockPF)): ?>
        <?php foreach ($stockPF as $bocal): ?>
          <tr>
            <td><strong><?= esc($bocal['nom'] ?? 'Bocal') ?></strong></td>
            <td><?= (int) ($bocal['quantite_disponible'] ?? 0) ?> unités</td>
            <td><?= number_format($bocal['cout_unitaire'] ?? 0, 0, ',', ' ') ?> Ar</td>
            <td style="color: #c8860a; font-weight: bold;"><?= number_format($bocal['valeur_comptable'] ?? 0, 0, ',', ' ') ?> Ar</td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" style="text-align: center; color: #777;">Aucun bocal en stock.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="total-bar">
    Valeur totale du stock : <?= number_format($valeurTotaleComptable ?? 0, 0, ',', ' ') ?> Ar
  </div>

</body>
</html>
