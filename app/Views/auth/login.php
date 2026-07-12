<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Miel Arovia</title>
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/login-style.css">
    <style>
        /* Ajustements mineurs pour centrer parfaitement la carte */
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.59), rgba(0, 0, 0, 0.4)), url('/assets/images/bg-arovia.png');
            background-size: cover;
            background-position: center;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .line {
            display: block;
            height: 1px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.15);
        }
        .place-entree:focus {
            outline: none;
            border-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }
        .deco{
            background-image: url('/assets/images/Arovia-login.png');
            background-size: cover;
            background-position: center;
            color: white;
        }
    </style>
</head>
<body>
    <section class="container p-2 col-10 col-md-10 col-lg-9 mx-auto text-black">
        <div class="row card-container rounded-5 col-12 col-md-10 border border-5 border-white p-1 mx-auto shadow" style="aspect-ratio: 16 / 9; background-color: #f2f2f2; filter:brightness(0.9)">
            
            <div class="log-section col-6 px-4 py-4 d-flex flex-column justify-content-between" style="height: 100%;">
                
                <section class="login-header" style="line-height: 22px; height: 20%;">
      <img src="<?= base_url('assets/images/Pattern simple - 1.png') ?>" alt="Pattern" class="header-pattern-img" />
      <h1 class="fw-medium mb-1" style="font-size: 1.75rem;">Login</h1>
                    <div class="page-title-wrap">
      <h1 class="fw-bold m-0" style="font-size: 2.5rem; letter-spacing: 1px;">AROVIA</h1>
      <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
    </div>
                    <span class="d-block mt-1" style="font-size: 0.75rem; color: rgb(255, 255, 255); font-weight: 500;">Bienvenue sur Arovia ERP</span>
                    <span class="d-block" style="font-size: 0.7rem; color: rgb(245, 245, 245);">Application dédiée à l'entreprise AROVIA</span>
                </section>
            </div>

        </div>
    </section>
</body>
</html>
