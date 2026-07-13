# Fix KPI in livraisons/index.php to use correct variables
with open(r'c:\Users\Harena\Documents\MmeBaovola\AROVIA_upgrade\app\Views\livraisons\index.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Fix KPI to use correct variables
old_kpi = '''          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap blue flex-column justify-content-center "><i class="fa fa-spinner"></i></div><span class="kpi-label d-flex justify-content-center"> En cours </span><span class="kpi-value d-flex justify-content-center blue"><?= count($livraisons ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap green flex-column justify-content-center "><i class="fa fa-check-double"></i></div><span class="kpi-label d-flex justify-content-center"> Livrées (Mois) </span><span class="kpi-value d-flex justify-content-center green"><?= count($livraisons_faites ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap orange flex-column justify-content-center "><i class="fa fa-triangle-exclamation"></i></div><span class="kpi-label d-flex justify-content-center"> Incidents </span><span class="kpi-value d-flex justify-content-center orange"><?= (int) ($stats['annulees'] ?? 0) ?></span></div>'''

new_kpi = '''          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap blue flex-column justify-content-center "><i class="fa fa-spinner"></i></div><span class="kpi-label d-flex justify-content-center"> En cours </span><span class="kpi-value d-flex justify-content-center blue"><?= count($livraisons_en_cours ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap green flex-column justify-content-center "><i class="fa fa-check-double"></i></div><span class="kpi-label d-flex justify-content-center"> Livrées (Mois) </span><span class="kpi-value d-flex justify-content-center green"><?= count($livraisons_faites ?? []) ?></span></div>
          <div class="row-1 d-flex align-items-center"><div class="kpi-icon-wrap orange flex-column justify-content-center "><i class="fa fa-triangle-exclamation"></i></div><span class="kpi-label d-flex justify-content-center"> Incidents </span><span class="kpi-value d-flex justify-content-center orange"><?= (int) ($stats['annulees'] ?? 0) ?></span></div>'''

content = content.replace(old_kpi, new_kpi)

with open(r'c:\Users\Harena\Documents\MmeBaovola\AROVIA_upgrade\app\Views\livraisons\index.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('livraisons/index.php KPI fixed')
