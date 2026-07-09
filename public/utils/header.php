<header class="topbar">
  <a href="/home" class="topbar-brand">
    <div class="brand-icon">🐝</div>
    <span class="brand-text">Miel <span>Arovia</span></span>
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
</script>