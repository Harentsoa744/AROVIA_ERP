<header class="topbar">
  <a href="/home" class="topbar-brand">
    <div class="brand-icon">🐝</div>
    <span class="brand-text">Miel <span>Arovia</span></span>
  </a>
  

  <div class="topbar-actions">
    <a href="/logout" class="topbar-btn text-decoration-none" title="Mon Profil">
      <i class="fa fa-user"></i> <span class="badge-notif">3</span>
    </a>
    
    <a href="/profil" class="topbar-user">
      <div class="avatar">
        <?= strtoupper(substr(session()->get('user_prenom') ?? 'A', 0, 1)) ?>
      </div>
      <div class="user-info d-none d-lg-block">
        <div class="user-name">
          <?= esc(session()->get('user_prenom')) . ' ' . esc(session()->get('user_nom')) ?>
        </div>
        <div class="user-role">
          <?= esc(session()->get('user_role')) ?>
        </div>
      </div>
    </a>
  </div>
</header>