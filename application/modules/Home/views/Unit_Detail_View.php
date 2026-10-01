<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* ================================================================
   UNIT DETAIL — MÊME DESIGN QUE HOME_VIEW
============================================================ */
.ud-main {
  font-family: 'Roboto', sans-serif !important;
  padding-top: 0 !important;
  margin-top: 0 !important;
}
.ud-main h1,.ud-main h2,.ud-main h3,.ud-main h4,.ud-main h5,.ud-main h6 {
  font-family: 'Yantramanav', sans-serif !important;
  color: #19232B !important;
  font-weight: 600;
  line-height: 1;
}
.ud-main p { color: #757F95; line-height: 1.8; margin: 0; }
.ud-main a { color: #19232B; transition: all 0.3s ease-out 0s; text-decoration: none; }
.ud-main a:hover { color: #116E63; }
.ud-main img { max-width: 100%; height: auto; transition: all 0.3s ease-out 0s; }

/* === HERO UNIT — même style que hero home === */
.ud-hero {
  position: relative;
  padding: 18px 0 15px;
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
}
.ud-hero::before {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  left: -0.5px;
  top: 0;
  background: rgba(11, 28, 57, .75);
  z-index: -1;
}
.ud-hero-content { text-align: center; color: #fff; }
.ud-hero-content * { color: #fff !important; }
.ud-hero .ud-hero-title { color: #fff !important; }
.ud-hero .ud-hero-title span { color: #dcbb07 !important; }
.ud-hero-logo {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
  border: 4px solid #dcbb07;
  box-shadow: 0 8px 40px rgba(0,0,0,.4);
  margin-bottom: 20px;
  transition: transform .5s ease;
}
.ud-hero-logo:hover { transform: scale(1.05) rotate(3deg); }
.ud-hero-title {
  color: white !important;
  font-size: 56px !important;
  font-weight: 800 !important;
  margin: 0 0 16px !important;
  font-family: 'Yantramanav', sans-serif !important;
}
.ud-hero-title span { color: #dcbb07 !important; }
.ud-hero-slogan {
  color: rgba(255,255,255,.85);
  font-size: 18px;
  font-style: italic;
  max-width: 600px;
  margin: 0 auto 30px;
}
.ud-hero-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #fff !important;
  padding: 14px 28px;
  text-transform: uppercase;
  border-radius: 50px 50px 50px 0;
  font-weight: 600;
  letter-spacing: 1px;
  background: #dcbb07;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
  text-decoration: none;
  transition: all .5s ease-in-out;
  position: relative;
  overflow: hidden;
  z-index: 1;
}
.ud-hero-btn::before {
  content: "";
  height: 300px;
  width: 300px;
  background: #116E63;
  border-radius: 50%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%) scale(0);
  transition: 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  z-index: -1;
}
.ud-hero-btn:hover { color: #fff !important; }
.ud-hero-btn:hover::before { transform: translateY(-50%) translateX(-50%) scale(1); }

/* === SECTION HEADING — même style === */
.ud-sh { margin-bottom: 50px; position: relative; z-index: 1; }
.ud-sh h2 {
  font-weight: 800;
  text-transform: capitalize;
  font-size: 48px;
  color: #19232B !important;
  margin-top: 10px;
  margin-bottom: 0;
}
.ud-sh h2 span { color: #dcbb07 !important; }
.ud-sh p { margin-top: 15px; }

/* === ABOUT SECTION — même style que home about === */
.ud-about { position: relative; padding: 80px 0; }
.ud-about-left { margin-right: 20px; }
.ud-about-img { display: flex; gap: 30px; position: relative; }
.ud-about-img .ud-img1 {
  border-radius: 80px 0 80px 80px;
  width: 100%;
  object-fit: cover;
}
.ud-about-exp {
  display: flex;
  align-items: center;
  text-align: center;
  background: #dcbb07;
  padding: 15px 20px 15px 15px;
  color: #fff;
  border-radius: 50px 50px 50px 0;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 10%);
  margin-top: 20px;
}
.ud-about-exp h4 { font-size: 16px; margin: 0; color: #19232B; }
.ud-about-exp b { color: #fff; }
.ud-about-right { position: relative; display: block; }
.ud-about-content { margin-top: 30px; }
.ud-about-item {
  position: relative;
  display: flex;
  gap: 12px;
  margin-bottom: 25px;
}
.ud-about-item-icon {
  width: 70px;
  height: 70px;
  text-align: center;
  background: #dcbb07;
  border-radius: 50px;
  font-size: 32px;
  color: #fff;
  box-shadow: -5px 5px 0 rgba(17, 110, 99, 0.09);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.ud-about-item-icon i { font-size: 32px; line-height: 1; font-style: normal; }
.ud-about-item-content { flex: 1; }
.ud-about-item-content h5 { font-size: 22px; margin-bottom: 5px; }

/* === CTA BAND === */
.ud-cta {
  position: relative;
  background: #116E63;
  padding: 60px 0;
  z-index: 1;
}
.ud-cta::before {
  content: "";
  position: absolute;
  background: #116E63;
  left: 0; top: 0; width: 100%; height: 100%;
  opacity: .7;
  z-index: -1;
}
.ud-cta h2 { color: #fff !important; margin-bottom: 15px; }
.ud-cta p { color: rgba(255,255,255,.85) !important; margin-bottom: 30px; }

/* === BACK BUTTON === */
.ud-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #fff !important;
  padding: 14px 28px;
  text-transform: uppercase;
  border-radius: 50px 50px 50px 0;
  font-weight: 600;
  letter-spacing: 1px;
  background: #dcbb07;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
  text-decoration: none;
  transition: all .5s ease-in-out;
  position: relative;
  overflow: hidden;
  z-index: 1;
}
.ud-back::before {
  content: "";
  height: 300px;
  width: 300px;
  background: #116E63;
  border-radius: 50%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateY(-50%) translateX(-50%) scale(0);
  transition: 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  z-index: -1;
}
.ud-back:hover { color: #fff !important; }
.ud-back:hover::before { transform: translateY(-50%) translateX(-50%) scale(1); }

/* === RESPONSIVE === */
@media (max-width: 991px) {
  .ud-hero-title { font-size: 38px !important; }
  .ud-about-right { margin-top: 30px; }
}
@media (max-width: 767px) {
  .ud-hero-title { font-size: 30px !important; }
  .ud-hero { padding: 50px 0 30px; }
  .ud-hero-logo { width: 70px; height: 70px; }
}
</style>

<main class="ud-main">

<!-- ========== HERO — MÊME STYLE QUE HOME ========== -->
<div class="ud-hero" style="background: url('<?= base_url($unit['logo']) ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <img class="ud-hero-logo" src="<?= base_url($unit['logo']) ?>" alt="<?= htmlspecialchars($unit['nom']) ?>">
      <h1 class="ud-hero-title"><?= htmlspecialchars($unit['nom']) ?></h1>
      <p class="ud-hero-slogan">"<?= htmlspecialchars($unit['slogan']) ?>"</p>
      <a href="<?= base_url('about') ?>" class="ud-hero-btn">
        <i class="fas fa-arrow-left-long"></i> Retour à À propos
      </a>
    </div>
  </div>
</div>

<!-- ========== DESCRIPTION — MÊME STYLE ABOUT HOME ========== -->
<div class="ud-about" style="background: #F2F3F5;">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <div class="ud-about-left">
          <div class="ud-about-img">
            <div class="col-md-10">
              <img class="ud-img1" src="<?= base_url($unit['logo']) ?>" alt="<?= htmlspecialchars($unit['nom']) ?>">
              <div class="ud-about-exp mt-4">
                <h4><?= htmlspecialchars($unit['code']) ?>: <b class="text-white"> <?= htmlspecialchars($unit['nom']) ?></b></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ud-about-right">
          <div class="ud-sh mb-3">
            <h2><?= htmlspecialchars($unit['nom']) ?></h2>
          </div>
          <p><?= nl2br(htmlspecialchars($unit['description'])) ?></p>
          <div class="ud-about-content">
            <div class="ud-about-item">
              <div class="ud-about-item-icon"><i class="fas fa-bullseye"></i></div>
              <div class="ud-about-item-content">
                <h5>Slogan</h5>
                <p><?= htmlspecialchars($unit['slogan']) ?></p>
              </div>
            </div>
            <div class="ud-about-item">
              <div class="ud-about-item-icon"><i class="fas fa-cogs"></i></div>
              <div class="ud-about-item-content">
                <h5>Code</h5>
                <p><?= htmlspecialchars($unit['code']) ?></p>
              </div>
            </div>
          </div>
          <a href="<?= base_url('about') ?>" class="ud-back">
            <i class="fas fa-arrow-left-long"></i> Retour à À propos
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========== CTA — MÊME STYLE COUNTER HOME ========== -->
<div class="ud-cta">
  <div class="container text-center">
    <div class="ud-sh">
      <h2 style="color:#fff !important;">Découvrez les autres <span>unités stratégiques</span>.</h2>
    </div>
    <p style="color:rgba(255,255,255,.85) !important;">Five business units, an integrated ecosystem for sustainable agro-industrial development.</p>
    <a href="<?= base_url('about') ?>" class="ud-hero-btn">
      Toutes les unités <i class="fas fa-arrow-right-long"></i>
    </a>
  </div>
</div>

</main>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
