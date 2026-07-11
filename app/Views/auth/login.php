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
            border-color: #000 !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }
        .deco{
            background-image: url('/assets/images/miel-fond.png');
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
                    <h1 class="fw-medium mb-1" style="font-size: 1.75rem;">Login</h1>
                    <h5 class="fw-light text-muted mb-2" style="font-size: 0.95rem;">Hello Dear !</h5>
                </section>
            
                <form action="/login" method="post" class="Form-place d-flex flex-column justify-content-center flex-grow-1 gap-3">
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2 px-3 text-center mb-0" style="font-size: 0.8rem; border-radius: 8px;">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="email-place col-12">
                        <label for="email" class="fw-medium mb-1" style="font-size: 0.85rem;">Email</label>
                        <input type="email" name="email" class="col-12 place-entree px-2 rounded-2" id="email" value="<?= esc($email ?? '') ?>" placeholder="votre email" style="border: 1px solid rgba(0,0,0,0.25); font-size: 0.9rem;" required autofocus>
                    </div>

                    <div class="password-place col-12">
                        <label for="password" class="fw-medium mb-1" style="font-size: 0.85rem;">Mot de passe</label>
                        <input type="password" name="password" class="col-12 place-entree px-2 rounded-2" id="password" placeholder="votre mot de passe" style="border: 1px solid rgba(0,0,0,0.25); font-size: 0.9rem;" required>
                    </div>

                    <button type="submit" class="text-center bg-black text-white shadow-sm py-2 rounded-2 border-0 fw-medium" style="font-size: 0.9rem; cursor: pointer; transition: opacity 0.2s;">
                        Se connecter
                    </button>
                    
                    <div class="least-row d-flex justify-content-between align-items-center">
                        <div class="check-memo d-flex align-items-center gap-1">
                            <input type="checkbox" name="memorize" id="memorize" style="cursor: pointer;">
                            <label for="memorize" class="tiny-text text-muted mb-0" style="cursor: pointer;">Memorize Password</label>
                        </div>

                        <div class="forget">
                            <a href="/auth/forgot" class="tiny-text text-dark" style="text-decoration: none;">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="deco col-6 h-100 rounded-5 shadow-sm d-flex flex-column justify-content-end text-black text-end" style="background-color: #fff7ae;">
                <section class="content col-12 px-3 pb-3 mb-3 d-flex flex-column ">
                    <h1 class="fw-bold m-0" style="font-size: 2.5rem; letter-spacing: 1px; color: white;">AROVIA</h1>
                    <span class="d-block mt-1" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.6); font-weight: 500;">Bienvenue sur Arovia ERP</span>
                    <span class="d-block" style="font-size: 0.7rem; color: rgba(255, 255, 255, 0.5);">Application dédiée à l'entreprise AROVIA</span>
                </section>
            </div>

        </div>
    </section>
</body>
</html>
