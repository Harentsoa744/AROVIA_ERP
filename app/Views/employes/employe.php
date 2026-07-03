<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des employés</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/liste.css">
</head>

<body>

<div class="page-wrapper">

    <header class="page-header">
        <div class="header-left">
            <h1>Équipe</h1>
            <span class="employee-count" id="count">— employés</span>
        </div>
        <a href="/employes/create" class="btn-add">
            <span class="btn-icon">+</span>
            Nouvel employé
        </a>
    </header>

    <div class="toolbar">
        <div class="search-wrap">
            <svg class="search-icon" viewBox="0 0 20 20" fill="none">
                <circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="1.6"/>
                <path d="M13 13l3.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
            <input type="text" id="search" placeholder="Rechercher un employé...">
        </div>
        <select id="statut">
            <option value="">Tous les statuts</option>
            <option value="ACTIF">Actif</option>
            <option value="INACTIF">Inactif</option>
        </select>
    </div>

    <div class="cards-grid" id="cards-container">
        <?php foreach ($employes as $emp): ?>
            <div class="employee-card" data-statut="<?= strtolower($emp['statut']) ?>">

                <div class="card-top">
                    <div class="avatar-wrap">
                        <?php if (!empty($emp['photo'])): ?>
                            <img src="<?= $emp['photo'] ?>" alt="Photo de <?= $emp['prenom'] ?>" class="avatar-img">
                        <?php else: ?>
                            <div class="avatar-initials" data-initials="<?= strtoupper(substr($emp['prenom'],0,1).substr($emp['nom'],0,1)) ?>">
                                <?= strtoupper(substr($emp['prenom'],0,1).substr($emp['nom'],0,1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="status-badge <?= strtolower($emp['statut']) ?>">
                        <?= $emp['statut'] ?>
                    </span>
                </div>

                <div class="card-body">
                    <p class="emp-matricule"><?= $emp['matricule'] ?></p>
                    <h2 class="emp-name"><?= $emp['prenom'] ?> <?= $emp['nom'] ?></h2>
                    <p class="emp-poste"><?= $emp['poste'] ?></p>
                </div>

                <div class="card-info">
                    <div class="info-row">
                        <svg viewBox="0 0 20 20" fill="none">
                            <path d="M6.5 3.5C6.5 2.7 7.2 2 8 2h4c.8 0 1.5.7 1.5 1.5v1h2C16.9 4.5 18 5.6 18 7v9c0 1.4-1.1 2.5-2.5 2.5h-11C3.1 18.5 2 17.4 2 16V7c0-1.4 1.1-2.5 2.5-2.5h2v-1zm1 0v1h5v-1H7.5z" stroke="currentColor" stroke-width="1.4" fill="none"/>
                        </svg>
                        <span><?= $emp['telephone'] ?></span>
                    </div>
                    <div class="info-row">
                        <svg viewBox="0 0 20 20" fill="none">
                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                        <span class="salary"><?= number_format($emp['salaire_base'], 0, ',', ' ') ?> Ar</span>
                    </div>
                </div>

                <div class="card-actions">
                    <a href="/employes/show/<?= $emp['id'] ?>" class="btn-action btn-view">Voir</a>
                    <a href="/employes/edit/<?= $emp['id'] ?>" class="btn-action btn-edit">Modifier</a>
                    <a href="/employes/delete/<?= $emp['id'] ?>"
                       class="btn-action btn-delete"
                       onclick="return confirm('Supprimer cet employé ?')">Supprimer</a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div class="empty-state" id="empty-state" style="display:none;">
        <div class="empty-icon">🔍</div>
        <p>Aucun employé ne correspond à votre recherche.</p>
    </div>

</div>

<script>
function buildCard(emp) {
    const initials = (emp.prenom.charAt(0) + emp.nom.charAt(0)).toUpperCase();
    const avatarHtml = emp.photo
        ? `<img src="${emp.photo}" alt="Photo de ${emp.prenom}" class="avatar-img">`
        : `<div class="avatar-initials" data-initials="${initials}">${initials}</div>`;

    const salary = Number(emp.salaire_base).toLocaleString('fr-FR');

    return `
        <div class="employee-card" data-statut="${emp.statut.toLowerCase()}">
            <div class="card-top">
                <div class="avatar-wrap">${avatarHtml}</div>
                <span class="status-badge ${emp.statut.toLowerCase()}">${emp.statut}</span>
            </div>
            <div class="card-body">
                <p class="emp-matricule">${emp.matricule}</p>
                <h2 class="emp-name">${emp.prenom} ${emp.nom}</h2>
                <p class="emp-poste">${emp.poste}</p>
            </div>
            <div class="card-info">
                <div class="info-row">
                    <svg viewBox="0 0 20 20" fill="none">
                        <path d="M14.5 13.5l-2.5 2.5a11 11 0 01-8-8l2.5-2.5-1.5-3.5H2a1 1 0 00-1 1A15 15 0 0018 18a1 1 0 001-1v-3l-3.5-1.5z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    </svg>
                    <span>${emp.telephone}</span>
                </div>
                <div class="info-row">
                    <svg viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/>
                        <path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                    <span class="salary">${salary} Ar</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="/employes/show/${emp.id}" class="btn-action btn-view">Voir</a>
                <a href="/employes/edit/${emp.id}" class="btn-action btn-edit">Modifier</a>
                <a href="/employes/delete/${emp.id}"
                   class="btn-action btn-delete"
                   onclick="return confirm('Supprimer cet employé ?')">Supprimer</a>
            </div>
        </div>
    `;
}

function loadEmployes() {
    const search = document.getElementById('search').value;
    const statut = document.getElementById('statut').value;
    const container = document.getElementById('cards-container');
    const emptyState = document.getElementById('empty-state');
    const countEl = document.getElementById('count');

    fetch(`/employes/ajax?search=${encodeURIComponent(search)}&statut=${encodeURIComponent(statut)}`)
        .then(res => res.json())
        .then(data => {
            container.innerHTML = '';
            countEl.textContent = `${data.length} employé${data.length > 1 ? 's' : ''}`;

            if (data.length === 0) {
                emptyState.style.display = 'flex';
            } else {
                emptyState.style.display = 'none';
                data.forEach(emp => {
                    container.innerHTML += buildCard(emp);
                });
            }
        });
}

document.getElementById('search').addEventListener('keyup', loadEmployes);
document.getElementById('statut').addEventListener('change', loadEmployes);
loadEmployes();
</script>
</body>
</html>
