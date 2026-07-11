<aside class="sidebar" id="sidebar">
  <nav class="sidebar-nav">
    <a href="/" class="nav-item-main active"><i class="fa fa-home nav-icon"></i> Accueil</a>
    
    <?php 
$userRole = session()->get('user_role');
// Debug: uncomment to see the role
// error_log('User role in session: ' . ($userRole ?? 'null'));
?>
    
    <?php if (strtolower($userRole ?? '') === 'admin' || strtolower($userRole ?? '') === 'responsable'): ?>
    <div class="nav-group">
      <div class="nav-group-header" onclick="toggleSubmenu(this)">
        <div class="nav-header-left"><i class="fa fa-boxes-stacked nav-icon"></i><span>Gestion de stock</span></div>
        <i class="fa fa-chevron-down chevron"></i>
      </div>
      <div class="nav-submenu">
        <a href="/fournisseurs" class="nav-subitem">Fournisseurs</a>
        <a href="/supermarches" class="nav-subitem">Supermarchés</a>
        <a href="/entrees-matiere-premiere" class="nav-subitem">Entrées matière première</a>
        <a href="/transformations" class="nav-subitem">Transformations (mise en bocal)</a>
        <a href="/sorties" class="nav-subitem">Sorties (ventes)</a>
        <a href="/valeur-stock" class="nav-subitem">Valeur du stock</a>
      </div>
    </div>
    <a href="/contrat" class="nav-item-main"><i class="fa fa-file-contract nav-icon"></i> Gestion de contrat</a>
    <a href="/employes" class="nav-item-main"><i class="fa fa-users nav-icon"></i> Gestion Employés</a>
    <a href="/finances" class="nav-item-main"><i class="fa fa-circle-dollar-to-slot nav-icon"></i> Finance</a>
    <a href="/statistiques" class="nav-item-main"><i class="fa fa-chart-line nav-icon"></i> Statistiques vente</a>
    <a href="/livraisons" class="nav-item-main"><i class="fa fa-truck nav-icon"></i> Distribution</a>
    <a href="/emploi-temps" class="nav-item-main"><i class="fa fa-calendar-days nav-icon"></i> Emploi du temps</a>
    <?php elseif (strtolower($userRole ?? '') === 'livreur'): ?>
    <a href="/livraisons" class="nav-item-main"><i class="fa fa-truck nav-icon"></i> Distribution</a>
    <a href="/emploi-temps" class="nav-item-main"><i class="fa fa-calendar-days nav-icon"></i> Emploi du temps</a>
    <?php elseif (strtolower($userRole ?? '') === 'magasinier' || strtolower($userRole ?? '') === 'comptable'): ?>
    <div class="nav-group">
      <div class="nav-group-header" onclick="toggleSubmenu(this)">
        <div class="nav-header-left"><i class="fa fa-boxes-stacked nav-icon"></i><span>Gestion de stock</span></div>
        <i class="fa fa-chevron-down chevron"></i>
      </div>
      <div class="nav-submenu">
        <a href="/sorties" class="nav-subitem">Sorties (ventes)</a>
        <a href="/valeur-stock" class="nav-subitem">Valeur du stock</a>
      </div>
    </div>
    <a href="/statistiques" class="nav-item-main"><i class="fa fa-chart-line nav-icon"></i> Statistiques vente</a>
    <?php else: ?>
    <div class="nav-group">
      <div class="nav-group-header" onclick="toggleSubmenu(this)">
        <div class="nav-header-left"><i class="fa fa-boxes-stacked nav-icon"></i><span>Gestion de stock</span></div>
        <i class="fa fa-chevron-down chevron"></i>
      </div>
      <div class="nav-submenu">
        <a href="/sorties" class="nav-subitem">Sorties (ventes)</a>
      </div>
    </div>
    <?php endif; ?>
  </nav>
  <!-- <div class="sidebar-image"><img src="assets/images/honey-sidebar.png" alt="Miel"/></div> -->
</aside>
<script>
  document.addEventListener("DOMContentLoaded", () => {
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll("#sidebar a");

  navLinks.forEach(link => {
    const linkPage = link.getAttribute("href");
    link.classList.remove("active");
    if (("/"+currentPage) === linkPage || (currentPage === "" && linkPage === "index.php")) {
      link.classList.add("active");
      const submenu = link.closest(".nav-submenu");
      if (submenu) {
        submenu.style.display = "block";
        const groupHeader = submenu.previousElementSibling;
        if (groupHeader && groupHeader.classList.contains("nav-group-header")) {
          groupHeader.classList.add("active");
        }
      }
    }
  });
});
</script>