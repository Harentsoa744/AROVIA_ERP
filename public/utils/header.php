<header class="topbar">
  <a href="/home" class="topbar-brand">
    <img class="brand-logo" src="<?= base_url('assets/images/logo.png') ?>" alt="Arovia">
  </a>
  

  <div class="topbar-actions">
    
    <div class="notification-container">
    <button class="topbar-btn notification-btn" id="notificationBtn">
        <i class="fa fa-bell"></i>
        <span 
            class="badge-notif" 
            id="notificationCount">
            0
        </span>
    </button>
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            Notifications
        </div>
        <div id="notificationList">
            <div class="text-center p-3">
                Chargement...
            </div>
        </div>
    </div>
</div>
    
    <div class="profile-container">
      <button class="topbar-user" id="profileBtn">
        <div class="avatar">
          <?php if (!empty(session()->get('user_photo'))): ?>
            <img src="<?= base_url('uploads/utilisateurs/' . session()->get('user_photo')) ?>" 
                 alt="Photo de profil" 
                 style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
          <?php else: ?>
            <?= strtoupper(substr(session()->get('user_prenom') ?? 'A', 0, 1)) ?>
          <?php endif; ?>
        </div>
        <div class="user-info d-none d-lg-block">
          <div class="user-name">
            <?= esc(session()->get('user_prenom')) . ' ' . esc(session()->get('user_nom')) ?>
          </div>
          <div class="user-role">
            <?= esc(session()->get('user_role')) ?>
          </div>
        </div>
      </button>
      <div class="profile-dropdown" id="profileDropdown">
        <a href="/profil" class="profile-dropdown-item">
          <i class="fa fa-user me-2"></i> Voir mon profil
        </a>
        <a href="/logout" class="profile-dropdown-item">
          <i class="fa fa-sign-out-alt me-2"></i> Se déconnecter
        </a>
      </div>
    </div>
  </div>
</header>
<script>
  document.addEventListener("DOMContentLoaded",()=>{


const btn =
document.querySelector("#notificationBtn");


const dropdown =
document.querySelector("#notificationDropdown");


const list =
document.querySelector("#notificationList");


const badge =
document.querySelector("#notificationCount");





// ouvrir dropdown

btn.addEventListener("click",()=>{


if(dropdown.style.display==="block")
{

dropdown.style.display="none";

}
else
{

dropdown.style.display="block";

chargerNotifications();

}


});







function chargerCount(){


fetch("/notification/count")


.then(r=>r.json())


.then(data=>{


badge.innerHTML=data.count;



if(data.count==0)

badge.style.display="none";

else

badge.style.display="flex";


});


}






function chargerNotifications(){


fetch("/notification/liste")


.then(r=>r.json())


.then(data=>{


list.innerHTML="";



if(data.length===0)
{


list.innerHTML=`

<div class="notification-empty">

Aucune notification

</div>

`;

return;

}




data.forEach(n=>{


list.innerHTML+=`

<div class="notification-item 
${n.lu==false?'non-lue':''}"
data-id="${n.id}">


<div class="notification-title">


${n.lu==false?

'<span class="notification-point"></span>'

:''}



${n.titre}


</div>



<div class="notification-message">

${n.message}

</div>



<div class="notification-date">

${n.date_creation}

</div>



</div>

`;


});



});

}






list.addEventListener("click",(e)=>{


let item =
e.target.closest(".notification-item");


if(!item)
return;



let id=item.dataset.id;



fetch("/notification/lire/"+id,{

method:"POST"

})

.then(()=>{


item.classList.remove("non-lue");


chargerCount();


});



});





// lancement

chargerCount();



// refresh toutes les 15 secondes

setInterval(
chargerCount,
15000
);



});

const profileBtn = document.querySelector("#profileBtn");
const profileDropdown = document.querySelector("#profileDropdown");

// --- GESTION DU CLIC PROFIL ---
profileBtn.addEventListener("click", (e) => {
    // On empêche la propagation pour éviter que le clic sur le bouton 
    // ne ferme immédiatement le dropdown à cause du script de fermeture globale
    e.stopPropagation(); 
    
    if (profileDropdown.style.display === "block") {
        profileDropdown.style.display = "none";
    } else {
        profileDropdown.style.display = "block";
        // Optionnel : fermer le dropdown de notification s'il est ouvert
        dropdown.style.display = "none"; 
    }
});

// --- BONUS : FERMER LES DROPDOWNS QUAND ON CLIQUE AILLEURS ---
document.addEventListener("click", () => {
    profileDropdown.style.display = "none";
    dropdown.style.display = "none";
});
</script>