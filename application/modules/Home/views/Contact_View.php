<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800&family=Yantramanav:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{--primary:#116E63;--accent:#dcbb07;--text:#19232B;--muted:#757F95;--border:#e3ece8;--bg:#f4f8f6;--white:#fff;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Roboto',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}

/* HERO */
.ct-hero{position:relative;background-size:cover!important;background-position:center!important;padding:140px 0 80px;display:flex;align-items:center;justify-content:center;text-align:center;z-index:1;}
.ct-hero::before{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(11,28,57,.75);z-index:-1;}
.ct-hero h1{font-family:'Yantramanav',sans-serif;font-size:52px;font-weight:800;color:#fff!important;margin-bottom:10px;text-shadow:0 2px 10px rgba(0,0,0,.3);}
.ct-hero p{color:rgba(255,255,255,.85);font-size:18px;margin-bottom:25px;}

/* SECTION */
.ct-section{padding:80px 0;}
.ct-container{max-width:1100px;margin:0 auto;padding:0 20px;}
.ct-heading{text-align:center;margin-bottom:50px;}
.ct-heading h2{font-family:'Yantramanav',sans-serif;font-size:42px;font-weight:800;color:var(--text);margin-bottom:8px;}
.ct-heading h2 span{color:var(--accent);}
.ct-heading p{color:var(--muted);font-size:16px;max-width:600px;margin:0 auto;}

/* GRID */
.ct-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;}
@media(max-width:768px){.ct-grid{grid-template-columns:1fr;gap:36px;}}

/* INFO GRID */
.ct-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;align-items:stretch;}
.ct-info-grid .ct-card-wide{grid-column:1 / -1;}

/* INFO CARDS */
.ct-info-card{background:var(--white);border:1px solid var(--border);border-radius:28px 28px 28px 0;padding:22px 24px;margin:0;display:flex;align-items:flex-start;gap:14px;transition:all .3s ease;height:100%;}
.ct-info-card:hover{transform:translateY(-3px);box-shadow:0 10px 30px rgba(17,110,99,.08);}
.ct-info-icon{width:44px;height:44px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0;}
.ct-info-card h4{font-family:'Roboto',sans-serif;font-size:11.5px;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;}
.ct-info-card p{color:var(--text);font-size:15px;font-weight:500;line-height:1.55;margin:0;overflow-wrap:anywhere;}
.ct-info-card a{color:var(--primary);font-weight:600;text-decoration:none;}
.ct-info-card a:hover{color:var(--accent);}
@media(max-width:991px){.ct-info-grid{grid-template-columns:1fr;}.ct-info-grid .ct-card-wide{grid-column:auto;}}

/* FORM */
.ct-form-card{background:var(--white);border:1px solid var(--border);border-radius:50px 50px 50px 0;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,.05);}
.ct-form-card h3{font-family:'Yantramanav',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:24px;}
.ct-field{margin-bottom:18px;}
.ct-field label{display:block;font-weight:600;color:var(--text);font-size:14px;margin-bottom:6px;}
.ct-field label .req{color:#dc2626;}
.ct-field input,.ct-field textarea,.ct-field select{width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:50px 50px 50px 0;font-family:'Roboto',sans-serif;font-size:15px;color:var(--text);background:var(--white);transition:border-color .3s,box-shadow .3s;outline:none;}
.ct-field input:focus,.ct-field textarea:focus,.ct-field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(17,110,99,.1);}
.ct-field textarea{resize:vertical;min-height:120px;border-radius:18px;}
.ct-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:var(--accent);color:#fff;border:none;border-radius:50px 50px 50px 0;font-family:'Roboto',sans-serif;font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:1px;cursor:pointer;position:relative;overflow:hidden;transition:all .35s cubic-bezier(.25,.46,.45,.94);}
.ct-btn::before{content:"";height:300px;width:300px;background:var(--primary);border-radius:50%;position:absolute;top:50%;left:50%;transform:translateY(-50%) translateX(-50%) scale(0);transition:.5s cubic-bezier(.25,.46,.45,.94);z-index:-1;}
.ct-btn:hover{color:#fff!important;transform:translateY(-2px);box-shadow:0 8px 25px rgba(220,187,7,.4);}
.ct-btn:hover::before{transform:translateY(-50%) translateX(-50%) scale(1);}
.ct-btn:disabled{opacity:.6;cursor:not-allowed;}

/* MAP */
.ct-map{margin-top:40px;border-radius:50px 50px 50px 0;overflow:hidden;border:1px solid var(--border);box-shadow:0 10px 30px rgba(0,0,0,.08);}
.ct-map iframe{width:100%;height:350px;border:0;}

/* BANK - memo 3.2 Corporate Bank Account */
.ct-bank{margin-top:32px;background:var(--white);border:1px solid var(--border);border-radius:50px 50px 50px 0;padding:34px 32px;box-shadow:0 10px 30px rgba(0,0,0,.05);}
.ct-bank h3{font-family:'Yantramanav',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:4px;}
.ct-bank .ct-bank-sub{font-size:13px;color:var(--muted);margin-bottom:20px;overflow-wrap:anywhere;}
.ct-bank-row{display:flex;gap:16px;padding:12px 0;border-bottom:1px dashed var(--border);}
.ct-bank-row:last-child{border-bottom:0;padding-bottom:0;}
.ct-bank-row dt{flex:0 0 44%;font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);align-self:center;}
.ct-bank-row dd{flex:1;margin:0;font-size:15px;font-weight:500;color:var(--text);overflow-wrap:anywhere;}
.ct-bank-row dd a{color:var(--primary);font-weight:600;text-decoration:none;}
.ct-bank-row dd a:hover{color:var(--accent);}
@media(max-width:575px){.ct-bank{padding:26px 20px;}.ct-bank-row{flex-direction:column;gap:3px;}}

/* SOCIAL */
.ct-social{display:flex;gap:12px;margin-top:20px;}
.ct-social a{width:46px;height:46px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;text-decoration:none;transition:all .3s ease;}
.ct-social a:hover{background:var(--accent);transform:translateY(-3px);}

/* TOAST */
.ct-toast{position:fixed;top:20px;right:20px;padding:16px 24px;border-radius:12px;font-weight:600;font-size:14px;z-index:9999;display:none;align-items:center;gap:10px;box-shadow:0 10px 30px rgba(0,0,0,.15);}
.ct-toast.show{display:flex;animation:fadeInUp .4s ease;}
.ct-toast.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.ct-toast.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
@media(max-width:575px){.ct-hero h1{font-size:32px;}.ct-heading h2{font-size:30px;}.ct-form-card{padding:24px;}.ct-section{padding:50px 0;}}
</style><style>
:root{--primary:#116E63;--accent:#dcbb07;--text:#19232B;--muted:#757F95;--border:#e3ece8;--bg:#f4f8f6;--white:#fff;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Roboto',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}

/* HERO */
.ct-hero{position:relative;background-size:cover!important;background-position:center!important;padding:140px 0 80px;display:flex;align-items:center;justify-content:center;text-align:center;z-index:1;}
.ct-hero::before{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(11,28,57,.75);z-index:-1;}
.ct-hero h1{font-family:'Yantramanav',sans-serif;font-size:52px;font-weight:800;color:#fff!important;margin-bottom:10px;text-shadow:0 2px 10px rgba(0,0,0,.3);}
.ct-hero p{color:rgba(255,255,255,.85);font-size:18px;margin-bottom:25px;}

/* SECTION */
.ct-section{padding:80px 0;}
.ct-container{max-width:1100px;margin:0 auto;padding:0 20px;}
.ct-heading{text-align:center;margin-bottom:50px;}
.ct-heading h2{font-family:'Yantramanav',sans-serif;font-size:42px;font-weight:800;color:var(--text);margin-bottom:8px;}
.ct-heading h2 span{color:var(--accent);}
.ct-heading p{color:var(--muted);font-size:16px;max-width:600px;margin:0 auto;}

/* GRID */
.ct-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;}
@media(max-width:768px){.ct-grid{grid-template-columns:1fr;gap:36px;}}

/* INFO GRID */
.ct-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;align-items:stretch;}
.ct-info-grid .ct-card-wide{grid-column:1 / -1;}

/* INFO CARDS */
.ct-info-card{background:var(--white);border:1px solid var(--border);border-radius:28px 28px 28px 0;padding:22px 24px;margin:0;display:flex;align-items:flex-start;gap:14px;transition:all .3s ease;height:100%;}
.ct-info-card:hover{transform:translateY(-3px);box-shadow:0 10px 30px rgba(17,110,99,.08);}
.ct-info-icon{width:44px;height:44px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0;}
.ct-info-card h4{font-family:'Roboto',sans-serif;font-size:11.5px;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;}
.ct-info-card p{color:var(--text);font-size:15px;font-weight:500;line-height:1.55;margin:0;overflow-wrap:anywhere;}
.ct-info-card a{color:var(--primary);font-weight:600;text-decoration:none;}
.ct-info-card a:hover{color:var(--accent);}
@media(max-width:991px){.ct-info-grid{grid-template-columns:1fr;}.ct-info-grid .ct-card-wide{grid-column:auto;}}

/* FORM */
.ct-form-card{background:var(--white);border:1px solid var(--border);border-radius:50px 50px 50px 0;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,.05);}
.ct-form-card h3{font-family:'Yantramanav',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:24px;}
.ct-field{margin-bottom:18px;}
.ct-field label{display:block;font-weight:600;color:var(--text);font-size:14px;margin-bottom:6px;}
.ct-field label .req{color:#dc2626;}
.ct-field input,.ct-field textarea,.ct-field select{width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:50px 50px 50px 0;font-family:'Roboto',sans-serif;font-size:15px;color:var(--text);background:var(--white);transition:border-color .3s,box-shadow .3s;outline:none;}
.ct-field input:focus,.ct-field textarea:focus,.ct-field select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(17,110,99,.1);}
.ct-field textarea{resize:vertical;min-height:120px;border-radius:18px;}
.ct-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:var(--accent);color:#fff;border:none;border-radius:50px 50px 50px 0;font-family:'Roboto',sans-serif;font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:1px;cursor:pointer;position:relative;overflow:hidden;transition:all .35s cubic-bezier(.25,.46,.45,.94);}
.ct-btn::before{content:"";height:300px;width:300px;background:var(--primary);border-radius:50%;position:absolute;top:50%;left:50%;transform:translateY(-50%) translateX(-50%) scale(0);transition:.5s cubic-bezier(.25,.46,.45,.94);z-index:-1;}
.ct-btn:hover{color:#fff!important;transform:translateY(-2px);box-shadow:0 8px 25px rgba(220,187,7,.4);}
.ct-btn:hover::before{transform:translateY(-50%) translateX(-50%) scale(1);}
.ct-btn:disabled{opacity:.6;cursor:not-allowed;}

/* MAP */
.ct-map{margin-top:40px;border-radius:50px 50px 50px 0;overflow:hidden;border:1px solid var(--border);box-shadow:0 10px 30px rgba(0,0,0,.08);}
.ct-map iframe{width:100%;height:350px;border:0;}

/* BANK - memo 3.2 Corporate Bank Account */
.ct-bank{margin-top:32px;background:var(--white);border:1px solid var(--border);border-radius:50px 50px 50px 0;padding:34px 32px;box-shadow:0 10px 30px rgba(0,0,0,.05);}
.ct-bank h3{font-family:'Yantramanav',sans-serif;font-size:24px;font-weight:700;color:var(--text);margin-bottom:4px;}
.ct-bank .ct-bank-sub{font-size:13px;color:var(--muted);margin-bottom:20px;overflow-wrap:anywhere;}
.ct-bank-row{display:flex;gap:16px;padding:12px 0;border-bottom:1px dashed var(--border);}
.ct-bank-row:last-child{border-bottom:0;padding-bottom:0;}
.ct-bank-row dt{flex:0 0 44%;font-size:11.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);align-self:center;}
.ct-bank-row dd{flex:1;margin:0;font-size:15px;font-weight:500;color:var(--text);overflow-wrap:anywhere;}
.ct-bank-row dd a{color:var(--primary);font-weight:600;text-decoration:none;}
.ct-bank-row dd a:hover{color:var(--accent);}
@media(max-width:575px){.ct-bank{padding:26px 20px;}.ct-bank-row{flex-direction:column;gap:3px;}}

/* SOCIAL */
.ct-social{display:flex;gap:12px;margin-top:20px;}
.ct-social a{width:46px;height:46px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;text-decoration:none;transition:all .3s ease;}
.ct-social a:hover{background:var(--accent);transform:translateY(-3px);}

/* TOAST */
.ct-toast{position:fixed;top:20px;right:20px;padding:16px 24px;border-radius:12px;font-weight:600;font-size:14px;z-index:9999;display:none;align-items:center;gap:10px;box-shadow:0 10px 30px rgba(0,0,0,.15);}
.ct-toast.show{display:flex;animation:fadeInUp .4s ease;}
.ct-toast.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.ct-toast.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
@media(max-width:575px){.ct-hero h1{font-size:32px;}.ct-heading h2{font-size:30px;}.ct-form-card{padding:24px;}.ct-section{padding:50px 0;}}
</style><script>
document.getElementById('ctContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('ctSubmitBtn');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Envoi...';

    const formData = new FormData(this);

    fetch('<?= base_url('Home/Contact/send') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showToast(data.message || 'Message sent successfully!', 'success');
            document.getElementById('ctContactForm').reset();
        } else {
            showToast(data.message || 'An error occurred while sending.', 'error');
        }
        btn.disabled = false;
        btn.innerHTML = orig;
    })
    .catch(() => {
        showToast('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = orig;
    });
});

function showToast(msg, type) {
    const t = document.getElementById('ctToast');
    t.className = 'ct-toast ' + type;
    t.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill') + '"></i> ' + msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 5000);
}
</script>

<!-- HERO -->
<div class="ct-hero" style="background:url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
    <div class="ct-container">
        <h1>Contactez-<span>nous</span></h1>

        <p>Nous sommes l&agrave; pour vous. N'h&eacute;sitez pas &agrave; nous contacter.</p>

    </div>
</div>

<!-- SECTION CONTACT -->
<div class="ct-section">
    <div class="ct-container">
        <div class="ct-heading">
            <h2>Contact <span>Information</span></h2>

            <p>&Eacute;tablissement r&eacute;gis&eacute; en Zambie. Toutes les informations proviennent des param&egrave;tres de la plateforme.</p>

        </div>

        <div class="ct-grid">
            <!-- COLONNE GAUCHE : Infos -->
            <div>
                <div class="ct-info-grid">
                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-building-fill"></i></div>
                    <div>
                        <h4>Company</h4>
                        <p><?= htmlspecialchars($company) ?></p>
                    </div>
                </div>

                <div class="ct-info-card ct-card-wide">
                    <div class="ct-info-icon"><i class="bi bi-buildings"></i></div>
                    <div>
                        <h4>Facility / Project</h4>
                        <p><?= htmlspecialchars($facility) ?></p>
                    </div>
                </div>

                <div class="ct-info-card ct-card-wide">
                    <div class="ct-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h4>Physical Address</h4>
                        <p><?= htmlspecialchars($site_address) ?></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <div>
                        <h4>Chief Executive Officer</h4>
                        <p><?= htmlspecialchars($ceo) ?></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <h4>Official Email</h4>
                        <p><a href="mailto:<?= htmlspecialchars($site_email) ?>"><?= htmlspecialchars($site_email) ?></a></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-phone-fill"></i></div>
                    <div>
                        <h4>Official Mobile</h4>
                        <p><a href="tel:<?= htmlspecialchars($site_phone) ?>"><?= htmlspecialchars($site_phone) ?></a></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-phone-vibrate-fill"></i></div>
                    <div>
                        <h4>Alternative Mobile</h4>
                        <p><a href="tel:<?= htmlspecialchars($site_phone_alt) ?>"><?= htmlspecialchars($site_phone_alt) ?></a></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div>
                        <h4>ZDA Investment Licence</h4>
                        <p><?= htmlspecialchars($licence_investissement) ?></p>
                    </div>
                </div>

                <div class="ct-info-card">
                    <div class="ct-info-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h4>Heures d'ouverture</h4>
                        <p><?php if (!empty($horaires)) { echo htmlspecialchars($horaires); } else { echo 'Lundi - Vendredi : 8h00 - 17h00<br>Samedi : 9h00 - 13h00'; } ?></p>
                    </div>
                </div>
                </div>

                <div class="ct-social">
                    <a href="https://facebook.com" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com" target="_blank" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://linkedin.com" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="https://youtube.com" target="_blank" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', htmlspecialchars($site_phone)) ?>" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <!-- COLONNE DROITE : Formulaire -->
            <div class="ct-form-card">
                <h3><i class="bi bi-chat-dots" style="color:var(--primary);"></i> Envoyez-nous un message</h3>

                <form id="ctContactForm">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="ct-field">
                        <label>Nom complet <span class="req">*</span></label>
                        <input type="text" name="name" placeholder="Votre nom" required minlength="3">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="ct-field">
                            <label>Email <span class="req">*</span></label>
                            <input type="email" name="email" placeholder="vous@email.com" required>
                        </div>
                        <div class="ct-field">
                            <label>T&eacute;l&eacute;phone</label>
                            <input type="tel" name="phone" placeholder="+260 ...">
                        </div>
                    </div>
                    <div class="ct-field">
                        <label>Sujet <span class="req">*</span></label>
                        <select name="subject" required>
                            <option value="">Choisir un sujet...</option>
                            <option value="Consultation m&eacute;dicale">Consultation m&eacute;dicale</option>
                            <option value="Produits phytosanitaires">Produits phytosanitaires</option>
                            <option value="Partenariat / Investissement">Partenariat / Investissement</option>
                            <option value="Service client">Service client</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="ct-field">
                        <label>Message <span class="req">*</span></label>
                        <textarea name="message" placeholder="D&eacute;crivez votre demande..." required minlength="10"></textarea>
                    </div>
                    <button type="submit" class="ct-btn" id="ctSubmitBtn">
                        <i class="bi bi-send-fill"></i> Envoyer le message
                    </button>
                </form>
            </div>
        </div>

        <!-- BANQUE - memo 3.2 -->
        <?php if (!empty($banque)): ?>
        <div class="ct-bank">
            <h3>Corporate Bank Account</h3>
            <dl>
            <?php foreach ($banque as $label => $value):
                  $is_mail = (strpos($value, '@') !== false && strpos($value, ' ') === false);
                  $is_tel  = (bool) preg_match('/^\+?[0-9][0-9 ().\-]{6,}$/', $value);
            ?>
                <div class="ct-bank-row">
                    <dt><?= htmlspecialchars($label) ?></dt>
                    <dd><?php if ($is_mail): ?><a href="mailto:<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></a><?php elseif ($is_tel): ?><a href="tel:<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></a><?php else: ?><?= htmlspecialchars($value) ?><?php endif; ?></dd>
                </div>
            <?php endforeach; ?>
            </dl>
        </div>
        <?php endif; ?>

        <!-- CARTE -->
        <div class="ct-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15284.47934!2d28.28!3d-15.38!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19408d0bbd3e7e5d%3A0x1!2sLusaka%2C+Zambia!5e0!3m2!1sfr!2s!4v1" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<!-- TOAST -->
<div class="ct-toast" id="ctToast"></div>

<script>
document.getElementById('ctContactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('ctSubmitBtn');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Envoi...';

    const formData = new FormData(this);

    fetch('<?= base_url('Home/Contact/send') ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            showToast(data.message || 'Message sent successfully!', 'success');
            document.getElementById('ctContactForm').reset();
        } else {
            showToast(data.message || 'An error occurred while sending.', 'error');
        }
        btn.disabled = false;
        btn.innerHTML = orig;
    })
    .catch(() => {
        showToast('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = orig;
    });
});

function showToast(msg, type) {
    const t = document.getElementById('ctToast');
    t.className = 'ct-toast ' + type;
    t.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill') + '"></i> ' + msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 5000);
}
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>