<?php
require_once 'includes/config.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (!$email || !$password) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=gestion_approvisionnement;charset=utf8mb4','root','root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ? AND statut = ?');
            $stmt->execute([$email, 'actif']);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user'] = ['id'=>$user['id_utilisateur'],'nom'=>$user['nom'],'role'=>$user['role'],'email'=>$user['email']];
                $pdo->prepare('UPDATE utilisateurs SET derniere_connexion=NOW() WHERE id_utilisateur=?')->execute([$user['id_utilisateur']]);
                header('Location: dashboard.php'); exit;
            } else { $erreur = 'Email ou mot de passe incorrect.'; }
        } catch (Exception $e) { $erreur = 'Erreur de connexion à la base de données.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GestionApprov — Connexion</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

body{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:16px;
  position:relative;
  overflow:hidden;
}

/* Image nette — sans scale ni blur */
.bg-img{
  position:fixed;inset:0;z-index:0;
  background-image:url('https://images.unsplash.com/photo-1553413077-190dd305871c?w=1920&q=95&auto=format&fit=crop');
  background-size:cover;
  background-position:center;
}

/* Overlay sombre */
.bg-overlay{
  position:fixed;inset:0;z-index:1;
  background:linear-gradient(135deg,rgba(4,10,22,0.75) 0%,rgba(8,18,40,0.70) 60%,rgba(4,10,22,0.80) 100%);
}

/* CARTE compacte */
.card{
  position:relative;z-index:10;
  width:100%;max-width:360px;
  background:rgba(6,14,30,0.72);
  backdrop-filter:blur(6px);
  -webkit-backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,0.1);
  border-radius:18px;
  padding:30px 28px;
  box-shadow:0 24px 60px rgba(0,0,0,0.5);
}

/* Ligne dorée top */
.card::before{
  content:'';
  position:absolute;top:0;left:18%;right:18%;
  height:2px;
  background:linear-gradient(90deg,transparent,rgba(226,168,75,0.65),transparent);
  border-radius:100px;
}

/* LOGO */
.logo{
  display:flex;flex-direction:column;
  align-items:center;text-align:center;
  margin-bottom:18px;
}
.logo-icon{
  width:48px;height:48px;border-radius:13px;
  background:linear-gradient(135deg,#E2A84B,#C47C20);
  display:flex;align-items:center;justify-content:center;
  font-size:1.3rem;color:#fff;
  margin-bottom:9px;
  box-shadow:0 8px 22px rgba(226,168,75,0.28);
}
.logo-name{
  font-size:1.15rem;font-weight:800;
  color:#fff;letter-spacing:-0.2px;margin-bottom:2px;
}
.logo-name span{color:#E2A84B;}
.logo-sub{
  font-size:0.6rem;color:rgba(255,255,255,0.28);
  text-transform:uppercase;letter-spacing:1.8px;font-weight:500;
}

/* SÉPARATEUR */
.sep{
  height:1px;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,0.08),transparent);
  margin:16px 0;
}

/* BADGE */
.badge{
  display:inline-flex;align-items:center;gap:6px;
  background:rgba(74,222,128,0.08);
  border:1px solid rgba(74,222,128,0.16);
  border-radius:100px;padding:3px 10px;margin-bottom:10px;
}
.bdot{
  width:5px;height:5px;border-radius:50%;background:#4ADE80;
  box-shadow:0 0 5px rgba(74,222,128,0.6);
  animation:bp 2s ease-in-out infinite;
}
@keyframes bp{0%,100%{opacity:1;}50%{opacity:0.3;}}
.btxt{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(74,222,128,0.8);}

.ttl{font-size:1.2rem;font-weight:800;color:#fff;letter-spacing:-0.2px;margin-bottom:3px;}
.sub{font-size:0.74rem;color:rgba(255,255,255,0.32);line-height:1.5;margin-bottom:16px;}

/* ERREUR */
.err{
  display:flex;align-items:center;gap:8px;
  background:rgba(255,80,80,0.1);
  border:1px solid rgba(255,80,80,0.2);
  border-left:3px solid #FF5050;
  border-radius:8px;padding:9px 12px;
  font-size:0.75rem;color:rgba(255,160,160,0.9);
  margin-bottom:14px;
}
.err i{color:#FF5050;flex-shrink:0;font-size:0.8rem;}

/* CHAMPS */
.fg{margin-bottom:11px;}
.fl{
  display:block;font-size:0.6rem;font-weight:700;
  text-transform:uppercase;letter-spacing:1.1px;
  color:rgba(255,255,255,0.38);margin-bottom:5px;
}
.fw{position:relative;}
.fi-ico{
  position:absolute;left:11px;top:50%;transform:translateY(-50%);
  font-size:0.7rem;color:rgba(255,255,255,0.2);
  pointer-events:none;transition:color 0.2s;
}
.fi{
  width:100%;
  padding:10px 11px 10px 34px;
  background:rgba(255,255,255,0.06);
  border:1.5px solid rgba(255,255,255,0.1);
  border-radius:9px;
  font-family:inherit;font-size:0.83rem;color:#fff;
  outline:none;transition:all 0.2s;-webkit-appearance:none;
}
.fi::placeholder{color:rgba(255,255,255,0.17);}
.fi:focus{
  background:rgba(255,255,255,0.09);
  border-color:rgba(226,168,75,0.5);
  box-shadow:0 0 0 3px rgba(226,168,75,0.08);
}
.fw:focus-within .fi-ico{color:#E2A84B;}
.fi-pass{padding-right:36px;}
.fi-eye{
  position:absolute;right:10px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;
  color:rgba(255,255,255,0.2);font-size:0.72rem;
  padding:4px;line-height:1;transition:color 0.2s;
}
.fi-eye:hover{color:rgba(255,255,255,0.55);}

/* BOUTON */
.btn{
  width:100%;margin-top:14px;
  padding:11px 16px;
  background:linear-gradient(135deg,#E2A84B,#C47C20);
  border:none;border-radius:9px;cursor:pointer;
  display:flex;align-items:center;justify-content:space-between;
  transition:all 0.25s;
  box-shadow:0 5px 18px rgba(226,168,75,0.25);
}
.btn:hover{transform:translateY(-1px);box-shadow:0 10px 28px rgba(226,168,75,0.35);}
.btn:active{transform:translateY(0);}
.btn-lbl{font-family:inherit;font-weight:700;font-size:0.82rem;color:#fff;}
.btn-ico{
  width:24px;height:24px;border-radius:6px;
  background:rgba(0,0,0,0.15);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:0.7rem;transition:transform 0.25s;
}
.btn:hover .btn-ico{transform:translateX(3px);}

/* FOOTER */
.foot{
  margin-top:16px;padding-top:13px;
  border-top:1px solid rgba(255,255,255,0.06);
  display:flex;align-items:center;justify-content:space-between;
}
.ssl{display:flex;align-items:center;gap:5px;font-size:0.62rem;color:rgba(255,255,255,0.2);}
.ssl-dot{width:5px;height:5px;border-radius:50%;background:#4ADE80;box-shadow:0 0 5px rgba(74,222,128,0.5);}
.ver{font-size:0.58rem;font-weight:700;color:rgba(226,168,75,0.38);border:1px solid rgba(226,168,75,0.12);padding:2px 7px;border-radius:20px;letter-spacing:0.8px;}

@media(max-width:400px){
  .card{padding:24px 18px;}
  .logo-icon{width:42px;height:42px;font-size:1.1rem;}
}
</style>
</head>
<body>

<div class="bg-img"></div>
<div class="bg-overlay"></div>

<div class="card">

  <div class="logo">
    <div class="logo-icon"><i class="fas fa-warehouse"></i></div>
    <div class="logo-name">Gestion<span>Approv</span></div>
    <div class="logo-sub">Gestion des approvisionnements</div>
  </div>

  <div class="sep"></div>

  <div class="badge"><div class="bdot"></div><span class="btxt">Accès sécurisé</span></div>
  <h2 class="ttl">Connexion</h2>
  <p class="sub">Entrez vos identifiants pour accéder à votre espace.</p>

  <?php if ($erreur): ?>
  <div class="err"><i class="fas fa-circle-exclamation"></i><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>

  <form method="POST" id="lf">
    <div class="fg">
      <label class="fl" for="em">Adresse email</label>
      <div class="fw">
        <i class="fas fa-at fi-ico"></i>
        <input type="email" id="em" name="email" class="fi"
          placeholder="votre@email.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          autocomplete="email" required autofocus>
      </div>
    </div>
    <div class="fg">
      <label class="fl" for="pw">Mot de passe</label>
      <div class="fw">
        <i class="fas fa-lock fi-ico"></i>
        <input type="password" id="pw" name="password"
          class="fi fi-pass" placeholder="••••••••••"
          autocomplete="current-password" required>
        <button type="button" class="fi-eye" onclick="tp()">
          <i class="fas fa-eye" id="ei"></i>
        </button>
      </div>
    </div>
    <button type="submit" class="btn" id="sb">
      <span class="btn-lbl" id="bl">Se connecter</span>
      <div class="btn-ico"><i class="fas fa-arrow-right" id="bi"></i></div>
    </button>
  </form>

  <div class="foot">
    <div class="ssl"><div class="ssl-dot"></div>Connexion chiffrée SSL</div>
    <div class="ver">v1.0</div>
  </div>

</div>

<script>
function tp(){
  var i=document.getElementById('pw'),e=document.getElementById('ei');
  i.type=i.type==='password'?'text':'password';
  e.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
document.getElementById('lf').addEventListener('submit',function(){
  var sb=document.getElementById('sb'),bl=document.getElementById('bl'),bi=document.getElementById('bi');
  bl.textContent='Connexion...';bi.className='fas fa-spinner fa-spin';
  sb.style.opacity='0.75';sb.style.pointerEvents='none';
});
</script>
</body>
</html>