<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* ================================================================
   AGF — ITN EXACT DESIGN (agf- prefix, no conflicts)
   All CSS matches ITN style.css 1:1
============================================================ */
.agf-main {
  font-family: 'Roboto', sans-serif !important;
  padding-top: 0 !important;
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}
.agf-main h1,.agf-main h2,.agf-main h3,.agf-main h4,.agf-main h5,.agf-main h6 {
  font-family: 'Yantramanav', sans-serif !important;
  color: #19232B !important;
  font-weight: 600;
  line-height: 1;
}
.agf-main p { color: #757F95; line-height: 1.8; margin: 0; }
.agf-main a { color: #19232B; transition: all 0.3s ease-out 0s; text-decoration: none; }
.agf-main a:hover { color: #116E63; }
.agf-main ul { margin: 0; padding: 0; }
.agf-main li { list-style: none; }
.agf-main img { max-width: 100%; height: auto; transition: all 0.3s ease-out 0s; }

/* === HERO — matches ITN exactly === */
.agf-hero-sec { position: relative; }
.agf-hero-single {
  padding-top: 100px;
  padding-bottom: 140px;
  background-position: center !important;
  background-size: cover !important;
  background-repeat: no-repeat !important;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 1;
}
.agf-hero-single::before {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  left: -0.5px;
  top: 0;
  background: rgba(11, 28, 57, .7);
  z-index: -1;
}
.agf-hero-content { height: 100%; }
.agf-main .agf-hero-title {
  color: white !important;
  font-size: 72px !important;
  font-weight: 800 !important;
  margin: 20px 0 !important;
  text-transform: capitalize;
}
.agf-main .agf-hero-title span { color: #dcbb07 !important; }
.agf-hero-content p {
  color: #fff !important;
  line-height: 30px;
  font-size: 18px;
  font-weight: 400;
  margin-bottom: 20px;
}
.agf-hero-btns { gap: 1rem; display: flex; margin-top: 35px; justify-content: start; }

/* === BUTTONS — matches ITN exactly === */
.agf-btn {
  font-size: 14px;
  color: #fff !important;
  padding: 14px 20px;
  transition: all .5s ease-in-out;
  text-transform: uppercase;
  position: relative;
  border-radius: 50px 50px 50px 0;
  font-weight: 600;
  letter-spacing: 1px;
  cursor: pointer;
  text-align: center;
  overflow: hidden;
  border: none;
  background: #dcbb07;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
  z-index: 1;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}
.agf-btn::before {
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
.agf-btn:hover { color: #fff !important; }
.agf-btn:hover::before { transform: translateY(-50%) translateX(-50%) scale(1); }
.agf-btn i { margin-left: 5px; }
.agf-btn2 { background: #fff; color: #19232B !important; }
.agf-btn2::before { background: #116E63; }
.agf-btn2:hover { color: #fff !important; }

/* === FEATURE AREA — matches ITN exactly === */
.agf-feat-area { position: relative; z-index: 1; }
.agf-feat-neg { margin-top: -150px; margin-right: 20px; }
.agf-feat-wrapper { /* ITN has feature-wrapper but no specific CSS for it */ }
.agf-feat-item {
  position: relative;
  padding: 20px 25px;
  margin-right: 10px;
  background: #fff;
  border-radius: 50px 50px 50px 0;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
  z-index: 1;
  display: block;
  text-decoration: none;
  transition: all 0.3s ease-out 0s;
}
.agf-feat-item:hover { transform: translateY(-3px); }
.agf-feat-item .agf-feat-count {
  position: absolute;
  right: 30px;
  top: 0px;
  font-size: 50px;
  font-weight: 800;
  -webkit-text-stroke: 2px #116E63;
  -webkit-text-fill-color: transparent;
}
.agf-feat-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  text-align: center;
  color: #fff;
  font-size: 40px;
  background: #116E63;
  margin-bottom: 25px;
  box-shadow: 5px 5px 0 #F2F3F5;
  position: relative;
  transition: all .5s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
}
.agf-feat-item:hover .agf-feat-icon { transform: rotateY(360deg); }
.agf-feat-icon i { font-family: 'bootstrap-icons'; font-size: 40px; line-height: 1; font-style: normal; }
.agf-feat-content { }
.agf-feat-title {
  font-size: 18px;
  font-weight: 600;
  color: #19232B;
  margin: 0;
}

/* === SITE HEADING === */
.agf-sh { margin-bottom: 50px; position: relative; z-index: 1; }
.agf-sh h2 {
  font-weight: 800;
  text-transform: capitalize;
  font-size: 55px;
  color: #19232B !important;
  margin-top: 10px;
  margin-bottom: 0;
  position: relative;
}
.agf-sh h2 span { color: #dcbb07 !important; }
.agf-sh p { margin-top: 15px; }

/* === ABOUT — matches ITN exactly === */
.agf-about { position: relative; }
.agf-about-left { margin-right: 20px; }
.agf-about-img { display: flex; gap: 30px; position: relative; }
.agf-about-img .agf-img1 { border-radius: 80px 0 80px 80px; }
.agf-about-exp {
  display: flex;
  align-items: center;
  text-align: center;
  background: #dcbb07;
  padding: 15px 20px 15px 15px;
  color: #fff;
  border-radius: 50px 50px 50px 0;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 10%);
}
.agf-about-exp h4 { font-size: 16px; margin: 0; color: #19232B; }
.agf-about-exp b { color: #fff; }
.agf-about-right { position: relative; display: block; }
.agf-about-content {
  margin-top: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}
.agf-about-item {
  position: relative;
  display: flex;
  gap: 12px;
  margin-bottom: 25px;
}
.agf-about-item-icon {
  width: 70px;
  height: 70px;
  text-align: center;
  margin-bottom: 12px;
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
.agf-about-item-icon i { font-size: 32px; line-height: 1; font-style: normal; }
.agf-about-item-content { flex: 1; }
.agf-about-item-content h5 { font-size: 22px; margin-bottom: 5px; }
.agf-about-bottom {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  margin-top: 30px;
}
.agf-phone { display: flex; align-items: center; gap: 12px; }
.agf-phone-icon {
  width: 48px;
  height: 48px;
  line-height: 48px;
  background: #116E63;
  color: #fff;
  border-radius: 50px;
  text-align: center;
  font-size: 22px;
  box-shadow: -5px 5px 0 rgba(17, 110, 99, 0.09);
}
.agf-phone-num { line-height: 1; }
.agf-phone-num span { color: #dcbb07; font-weight: 500; }
.agf-phone-num h6 { font-size: 20px; margin-top: 8px; }
.agf-phone-num h6 a { color: #116E63; }

/* === COUNTER === */
.agf-counter {
  position: relative;
  background: url('<?= base_url("attachments/Parametres/2026030221535169a5eacf8b9bd.jpg") ?>') no-repeat center center;
  background-size: cover;
  background-attachment: fixed;
  z-index: 1;
}
.agf-counter::before {
  content: "";
  position: absolute;
  background: rgba(26, 54, 93, 0.92);
  left: 0; top: 0; width: 100%; height: 100%;
  z-index: -1;
}
.agf-cbox {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  text-align: center;
  gap: 30px;
  position: relative;
  z-index: 1;
  padding: 20px 0;
}
.agf-cbox-icon {
  position: relative;
  text-align: center;
  width: 100px;
  height: 100px;
  color: #fff;
  background: #dcbb07;
  border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.agf-cbox-icon i { font-size: 44px; line-height: 1; font-style: normal; }
.agf-cbox-icon::before {
  content: "";
  position: absolute;
  left: 10px; top: 10px;
  width: 100%; height: 100%;
  border-radius: 30px;
  border: 3px solid #fff;
  transition: all .5s ease-in-out;
  border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
  z-index: -1;
}
.agf-cbox:hover .agf-cbox-icon::before { left: 0; top: 0; }
.agf-cbox-num {
  display: block;
  line-height: 1;
  color: #fff;
  font-size: 50px;
  font-weight: 600;
}
.agf-cbox-title {
  color: #fff;
  margin-top: 20px;
  font-size: 20px;
  font-weight: 600;
  text-transform: capitalize;
}

/* === STRATEGIC UNITS CARD === */
.agf-ccard {
  position: relative;
  background: #fff;
  padding: 20px;
  border-radius: 50px 50px 50px 0;
  margin-bottom: 25px;
  box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
  display: flex;
  flex-direction: column;
  height: 100%;
}
.agf-ccard > div:last-child { flex: 1; display: flex; flex-direction: column; }
.agf-ccard-text {
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.6;
  max-height: calc(1.6em * 3);
  margin-bottom: 0;
}
.agf-ccard-img { position: relative; }
.agf-ccard-img img { border-radius: 40px 40px 40px 0; }
.agf-ccard-tag {
  position: absolute;
  right: -15px;
  top: 15px;
  background: #dcbb07;
  color: #fff;
  border-radius: 40px 40px 40px 0;
  padding: 2px 10px;
  display: inline-block;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.17);
  z-index: 1;
}
.agf-ccard-title { margin-bottom: 10px; }
.agf-ccard-title:hover { color: #dcbb07; }
.agf-ccard-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  margin-top: 15px;
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  flex-shrink: 0;
}
.agf-readmore {
  display: inline-block;
  background: #dcbb07;
  color: #fff;
  padding: 1px 10px;
  border-radius: 50px 50px 50px 0;
  font-weight: 500;
}
.agf-readmore:hover { color: #fff; background: #116E63; }

/* === PRODUCT CARD — E-COMMERCE PROFESSIONAL === */
.agf-course { position: relative; }
.agf-course-bg { background: #F2F3F5; }
.agf-pcard {
  position: relative;
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 15px rgba(0,0,0,.08);
  display: flex;
  flex-direction: column;
  height: 100%;
  transition: all .3s ease;
  border: 1px solid #f0f0f0;
}
.agf-pcard:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 40px rgba(0,0,0,.15);
  border-color: #dcbb07;
}
.agf-pcard-img {
  position: relative;
  overflow: hidden;
  background: #f8f9fa;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 220px;
}
.agf-pcard-img img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 8px;
  transition: transform .5s ease;
}
.agf-pcard:hover .agf-pcard-img img { transform: scale(1.08); }
.agf-pcard-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #116E63;
  color: #fff;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  z-index: 2;
}
.agf-pcard-actions {
  position: absolute;
  top: 12px;
  right: 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  opacity: 0;
  transform: translateX(10px);
  transition: all .3s ease;
  z-index: 2;
}
.agf-pcard:hover .agf-pcard-actions {
  opacity: 1;
  transform: translateX(0);
}
.agf-pcard-action {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: #fff;
  border: none;
  box-shadow: 0 2px 8px rgba(0,0,0,.12);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 14px;
  color: #19232B;
  transition: all .3s ease;
  text-decoration: none;
}
.agf-pcard-action:hover {
  background: #dcbb07;
  color: #fff;
  transform: scale(1.1);
}
.agf-pcard-body {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.agf-pcard-cat {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  color: #116E63;
  letter-spacing: .5px;
  margin-bottom: 6px;
}
.agf-pcard-title {
  font-family: 'Yantramanav', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #19232B;
  margin-bottom: 8px;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.agf-pcard-title:hover { color: #116E63; }
.agf-pcard-desc {
  font-size: 13px;
  color: #757F95;
  line-height: 1.6;
  margin-bottom: 12px;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.agf-pcard-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 12px;
  border-top: 1px solid #f0f0f0;
}
.agf-pcard-price {
  font-size: 16px;
  font-weight: 800;
  color: #116E63;
}
.agf-pcard-price small {
  font-size: 11px;
  font-weight: 500;
  color: #999;
  text-decoration: line-through;
  margin-left: 6px;
}
.agf-pcard-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #dcbb07;
  color: #fff !important;
  border-radius: 25px;
  font-size: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all .3s ease;
  text-transform: uppercase;
  letter-spacing: .5px;
}
.agf-pcard-btn:hover {
  background: #116E63;
  color: #fff !important;
  transform: translateY(-1px);
}
.agf-pcard-btn i { font-size: 11px; }

/* === PARTNERS CAROUSEL === */
.agf-partners { position: relative; overflow: hidden; padding: 60px 0; }
.agf-partners-wrap { position: relative; overflow: hidden; }
.agf-partners-track {
  display: flex;
  gap: 30px;
  animation: agf-scroll 20s linear infinite;
  width: max-content;
}
.agf-partners-track:hover { animation-play-state: paused; }
@keyframes agf-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.agf-partner-card {
  flex-shrink: 0;
  width: 220px;
  background: #fff;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 2px 15px rgba(0,0,0,.06);
  border: 1px solid #f0f0f0;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  transition: all .3s ease;
}
.agf-partner-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(0,0,0,.12);
  border-color: #dcbb07;
}
.agf-partner-logo {
  width: 100px;
  height: 70px;
  object-fit: contain;
  margin-bottom: 12px;
  border-radius: 8px;
}
.agf-partner-name {
  font-family: 'Yantramanav', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: #19232B;
  margin-bottom: 4px;
  line-height: 1.2;
}
.agf-partner-type {
  font-size: 11px;
  color: #116E63;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .5px;
}
.agf-partners-fade-left,
.agf-partners-fade-right {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 80px;
  z-index: 2;
  pointer-events: none;
}
.agf-partners-fade-left {
  left: 0;
  background: linear-gradient(to right, #fff, transparent);
}
.agf-partners-fade-right {
  right: 0;
  background: linear-gradient(to left, #fff, transparent);
}

/* === CHOOSE / INVESTMENT CTA === */
.agf-choose {
  position: relative;
  background: url('<?= base_url("attachments/Parametres/2026030221535169a5eacf8b9bd.jpg") ?>') no-repeat center center;
  background-size: cover;
  background-attachment: fixed;
  z-index: 1;
  padding: 80px 0;
}
.agf-choose::before {
  content: "";
  position: absolute; left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(26, 54, 93, 0.92);
  z-index: -1;
}
.agf-choose-wrap { margin-top: 30px; }
.agf-citem {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255,255,255,0.12);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 16px;
  padding: 18px 20px;
  overflow: hidden;
  transition: all .3s ease;
}
.agf-citem:hover {
  background: rgba(255,255,255,0.2);
  transform: translateY(-3px);
}
.agf-citem-icon {
  width: 60px; height: 60px;
  background: #dcbb07;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.agf-citem-icon i { color: #19232B; font-size: 28px; line-height: 1; font-style: normal; }
.agf-citem-info { flex: 1; }
.agf-citem-info h4 { color: #fff; margin-bottom: 4px; font-size: 18px; font-weight: 700; }
.agf-citem-info p { color: rgba(255,255,255,.7); font-size: 13px; margin: 0; }
.agf-choose-stats {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin-top: 40px;
  padding-top: 30px;
  border-top: 1px solid rgba(255,255,255,0.15);
}
.agf-choose-stat {
  text-align: center;
}
.agf-choose-stat-num {
  font-size: 36px;
  font-weight: 800;
  color: #dcbb07;
  line-height: 1;
}
.agf-choose-stat-label {
  font-size: 13px;
  color: rgba(255,255,255,.7);
  margin-top: 6px;
}

/* === CTA === */
.agf-cta { position: relative; background: #dcbb07; }
.agf-cta-box {
  position: relative;
  padding: 80px 40px;
  margin-top: -40px;
  z-index: 1;
}
.agf-cta-box::before {
  content: "";
  position: absolute; left: 0; top: 0;
  width: 100%; height: 100%;
  background: #dcbb07;
  border-radius: 80px 80px 80px 0;
  z-index: -1;
}
.agf-cta-box::after {
  content: "";
  position: absolute; left: 8px; top: 8px; bottom: 8px; right: 8px;
  border: 8px double #fff;
  border-radius: 70px 70px 70px 0;
  z-index: -1;
}
.agf-cta-box h2 { color: #fff !important; }
.agf-cta-box p { color: #fff !important; margin-top: 15px; margin-bottom: 30px; font-size: 18px; }
.agf-cta .agf-btn { background: #fff; color: #19232B !important; }
.agf-cta .agf-btn:hover { color: #fff !important; }

/* === RESPONSIVE === */
@media (max-width: 1199px) {
  .agf-hero-title { font-size: 37px !important; }
  .agf-feat-neg { margin-top: -50px; margin-left: 20px; }
}
@media (max-width: 991px) {
  .agf-hero-title { font-size: 50px !important; }
  .agf-about-right { margin-top: 30px; }
  .agf-cbox { margin: 40px 0; }
  .agf-feat-neg { margin-top: 0; }
}
@media (max-width: 767px) {
  .agf-hero-title { font-size: 32px !important; }
  .agf-hero-btns { gap: 1rem; }
  .agf-about-item { margin-top: 30px; }
}
</style>

<main class="agf-main">

<!-- ========== HERO — EXACT ITN STRUCTURE ========== -->
<div class="agf-hero-sec">
  <div class="agf-hero-single" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 col-lg-8 mx-auto">
          <div class="agf-hero-content text-center">
            <h1 class="agf-hero-title">African Green <span>Farmers</span></h1>
            <p>Building an integrated agro-industrial platform: regenerative agriculture, industrial manufacturing, scientific laboratories and distribution — for a sustainable, resilient and export-oriented organic food and biological system.</p>
            <div class="agf-hero-btns justify-content-center">
              <a href="<?= base_url('doctor') ?>" class="agf-btn">Get Consulted<i class="fas fa-arrow-right-long"></i></a>
              <a href="<?= base_url('Products') ?>" class="agf-btn agf-btn2">Our Products<i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========== FEATURES — EXACT ITN STRUCTURE ========== -->
<div class="agf-feat-area agf-feat-neg">
  <div class="col-xl-9 ms-auto">
    <div class="agf-feat-wrapper">
      <div class="row g-4">

        <div class="col-lg-3 col-sm-6">
          <a href="<?= base_url('about') ?>">
            <div class="agf-feat-item">
              <span class="agf-feat-count">01</span>
              <div class="agf-feat-icon"><i class="fas fa-seedling"></i></div>
              <div class="agf-feat-content"><h5 class="agf-feat-title">Regenerative Agriculture</h5></div>
            </div>
          </a>
        </div>

        <div class="col-lg-3 col-sm-6">
          <a href="<?= base_url('about') ?>">
            <div class="agf-feat-item">
              <span class="agf-feat-count">02</span>
              <div class="agf-feat-icon"><i class="bi bi-gear-wide-connected"></i></div>
              <div class="agf-feat-content"><h5 class="agf-feat-title">Industrial Manufacturing</h5></div>
            </div>
          </a>
        </div>

        <div class="col-lg-3 col-sm-6">
          <a href="<?= base_url('about') ?>">
            <div class="agf-feat-item">
              <span class="agf-feat-count">03</span>
              <div class="agf-feat-icon"><i class="fas fa-microscope"></i></div>
              <div class="agf-feat-content"><h5 class="agf-feat-title">Research Laboratories</h5></div>
            </div>
          </a>
        </div>
        <div class="col-lg-3 col-sm-6">
          <a href="<?= base_url('about') ?>">
            <div class="agf-feat-item">
              <span class="agf-feat-count">04</span>
              <div class="agf-feat-icon"><i class="bi bi-truck"></i></div>
              <div class="agf-feat-content"><h5 class="agf-feat-title">Quality & Distribution</h5></div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========== ABOUT — EXACT ITN STRUCTURE ========== -->
<div class="agf-about py-120 p-4 mb-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <div class="agf-about-left">
          <div class="agf-about-img">
            <div class="col-md-10">
              <img class="agf-img1" src="<?= base_url('attachments/Parametres/agf-limited-certificate.jpg') ?>" alt="A.G.F Limited Certificate">
              <div class="agf-about-exp mt-4">
                <h4>A.G.F Limited: <b class="text-white"> Zambian Agro-Industrial Company</b></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="agf-about-right">
          <div class="agf-sh mb-3">
            <h2>Hands-On <span>Innovation</span>, Real-World <span>Impact</span>.</h2>
          </div>
          <p>African Green Farmers Limited (A.G.F Limited) is a privately-owned Zambian agro-industrial investment company developing a circular bio-economy enterprise powered by ACIDS (Advanced Computational Intelligence & Decision Sciences), focused on high-value organic food, nutritional and agricultural solutions.</p>
          <div class="agf-about-content">
            <div class="row">
              <div class="col-md-12">
                <div class="agf-about-item">
                  <div class="agf-about-item-icon"><i class="fas fa-seedling"></i></div>
                  <div class="agf-about-item-content">
                    <h5>Regenerative Agriculture</h5>
                    <p>Our platform combines organic agricultural production with industrial manufacturing for sustainable solutions.</p>
                  </div>
                </div>
                <div class="agf-about-item">
                  <div class="agf-about-item-icon"><i class="bi bi-globe-americas"></i></div>
                  <div class="agf-about-item-content">
                    <h5>Global Standards, Local Impact</h5>
                    <p>We connect international standards with local relevance, empowering solutions for Africa and beyond.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="agf-about-bottom">
            <a href="<?= base_url('about') ?>" class="agf-btn">Discover More<i class="fas fa-arrow-right-long"></i></a>
            <div class="agf-phone">
              <div class="agf-phone-icon"><i class="fas fa-headset"></i></div>
              <div class="agf-phone-num">
                <span>Call Now</span>
                <h6><a href="tel:+26768546053"> (+267) 68 54 60 53</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========== COUNTER — EXACT ITN STRUCTURE ========== -->
<div class="agf-counter pt-60 pb-60 p-4  mb-2">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-cash-stack"></i></div>
          <div>
            <span class="agf-cbox-num"><?= htmlspecialchars($stats_investissement) ?></span>
            <h6 class="agf-cbox-title">Investment Sought</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-geo-alt"></i></div>
          <div>
            <span class="agf-cbox-num"><?= htmlspecialchars($stats_superficie) ?></span>
            <h6 class="agf-cbox-title">Integrated Farm Platform</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-box-seam"></i></div>
          <div>
            <span class="agf-cbox-num"><?= htmlspecialchars($stats_produits) ?></span>
            <h6 class="agf-cbox-title">Flagship Products</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-graph-up-arrow"></i></div>
          <div>
            <span class="agf-cbox-num"><?= htmlspecialchars($stats_irr) ?></span>
            <h6 class="agf-cbox-title">IRR</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========== STRATEGIC UNITS ========== -->
<div class="agf-course agf-course-bg pt-80 pb-80 mb-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mx-auto">
        <div class="agf-sh text-center">
          <h2>Our Strategic <span>Units</span></h2>
          <p>Five business units, one integrated ecosystem driving sustainable agro-industrial development.</p>
        </div>
      </div>
    </div>
    <div class="row align-items-stretch">
      <?php foreach ($unites as $unit): ?>
      <div class="col-6 col-lg">
        <div class="agf-ccard">
          <div class="agf-ccard-img">
            <img src="<?= base_url($unit['logo']) ?>" alt="<?= htmlspecialchars($unit['nom']) ?>">
          </div>
          <div>
            <h4 class="agf-ccard-title text-center mt-1"><?= htmlspecialchars($unit['nom']) ?></h4>
            <p class="agf-ccard-text"><?= htmlspecialchars($unit['description']) ?></p>
            <div class="agf-ccard-bottom">
              <a href="<?= base_url('about/'.$unit['slug']) ?>"><span class="agf-readmore">Read more <i class="fas fa-arrow-right-long"></i></span></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ========== PRODUCTS ========== -->
<div class="agf-course pt-80 pb-80 mb-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mx-auto">
        <div class="agf-sh text-center">
          <h2>Our Flagship <span>Products</span></h2>
          <p><?= count($produits) ?> flagship products, from wellness to organic fertilization.</p>
        </div>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach ($produits as $prod): ?>
      <div class="col-md-6 col-lg-4">
        <div class="agf-pcard">
          <div class="agf-pcard-img">
            <span class="agf-pcard-badge"><?= htmlspecialchars($prod['conditionnement'] ?? 'Product') ?></span>
            <div class="agf-pcard-actions">
              <a href="<?= base_url('Products/detail/'.$prod['slug']) ?>" class="agf-pcard-action" title="Voir"><i class="fas fa-eye"></i></a>
            </div>
            <img src="<?= base_url($prod['image']) ?>" alt="<?= htmlspecialchars($prod['nom']) ?>">
          </div>
          <div class="agf-pcard-body">
            <div class="agf-pcard-cat"><?= htmlspecialchars($prod['conditionnement'] ?? 'Produit') ?></div>
            <h4 class="agf-pcard-title"><?= htmlspecialchars($prod['nom']) ?></h4>
            <p class="agf-pcard-desc"><?= htmlspecialchars($prod['description']) ?></p>
            <div class="agf-pcard-footer">
              <?php if (!empty($prod['prix'])): ?>
                <span class="agf-pcard-price"><?= htmlspecialchars($prod['prix']) ?></span>
              <?php else: ?>
                <span class="agf-pcard-price">Sur demande</span>
              <?php endif; ?>
              <a href="<?= base_url('Products/detail/'.$prod['slug']) ?>" class="agf-pcard-btn">Détails <i class="fas fa-arrow-right-long"></i></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="<?= base_url('Products') ?>" class="agf-btn">View All Products<i class="fas fa-arrow-right-long"></i></a>
    </div>
  </div>
</div>



<!-- ========== INVESTMENT CTA ========== -->
<div class="agf-choose">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-12">
        <div class="agf-sh mb-0 mt-0 text-center">
          <h2 class="text-white" style="color:#fff !important;">Join a unique agro-industrial <span>investment opportunity</span>.</h2>
          <p style="color:rgba(255,255,255,.85) !important;">USD 63.2 million sought to scale the AFOOPROC pilot platform into a full-scale manufacturing industry — positive NPV, 15.78% IRR, 5-year grace period.</p>
        </div>
        <div class="agf-choose-wrap">
          <div class="row g-4">
            <div class="col-md-6 col-lg-3">
              <div class="agf-citem">
                <div class="agf-citem-icon"><i class="fas fa-chart-line"></i></div>
                <div class="agf-citem-info">
                  <h4>Positive NPV</h4>
                  <p>Net present value confirmed</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="agf-citem">
                <div class="agf-citem-icon"><i class="fas fa-percentage"></i></div>
                <div class="agf-citem-info">
                  <h4>15.78% IRR</h4>
                  <p>Internal rate of return</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="agf-citem">
                <div class="agf-citem-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="agf-citem-info">
                  <h4>5-Year Grace</h4>
                  <p>Grace period included</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="agf-citem">
                <div class="agf-citem-icon"><i class="fas fa-handshake"></i></div>
                <div class="agf-citem-info">
                  <h4>Become a Broker</h4>
                  <p>Earn commission on referrals</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="agf-choose-stats">
          <div class="agf-choose-stat col-lg-3 col-sm-6">
            <div class="agf-choose-stat-num">$63.2M</div>
            <div class="agf-choose-stat-label">Investment Sought</div>
          </div>
          <div class="agf-choose-stat col-lg-3 col-sm-6">
            <div class="agf-choose-stat-num">2,000+</div>
            <div class="agf-choose-stat-label">Hectares Platform</div>
          </div>
          <div class="agf-choose-stat col-lg-3 col-sm-6">
            <div class="agf-choose-stat-num">11</div>
            <div class="agf-choose-stat-label">Flagship Products</div>
          </div>
          <div class="agf-choose-stat col-lg-3 col-sm-6">
            <div class="agf-choose-stat-num">5</div>
            <div class="agf-choose-stat-label">Strategic Units</div>
          </div>
        </div>
        <div class="agf-hero-btns mt-4 justify-content-center">
          <a href="<?= base_url('investment-projection') ?>" class="agf-btn">Investment Opportunity<i class="fas fa-arrow-right-long"></i></a>
          <a href="<?= base_url('broker') ?>" class="agf-btn agf-btn2">Become a Broker<i class="fas fa-arrow-right-long"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ========== PARTNERS CAROUSEL ========== -->
<div class="agf-partners mb-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="agf-sh text-center">
          <h2>Strategic <span>Partners</span></h2>
          <p>Trusted by leading institutions for sustainable agro-industrial development.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="agf-partners-wrap">
    <div class="agf-partners-fade-left"></div>
    <div class="agf-partners-fade-right"></div>
    <div class="agf-partners-track" id="partnersTrack">
      <?php foreach ($partenaires as $partner): ?>
        <?php if (!empty($partner['logo_url'])): ?>
        <div class="agf-partner-card">
          <img class="agf-partner-logo" src="<?= base_url($partner['logo_url']) ?>" alt="<?= htmlspecialchars($partner['nom']) ?>">
          <div class="agf-partner-name"><?= htmlspecialchars($partner['nom']) ?></div>
          <div class="agf-partner-type"><?= htmlspecialchars($partner['type_partenaire']) ?></div>
        </div>
        <?php else: ?>
        <div class="agf-partner-card">
          <div class="agf-partner-logo" style="display:flex;align-items:center;justify-content:center;background:#116E63;border-radius:8px;color:#fff;font-weight:700;font-size:20px;"><?= strtoupper(substr($partner['nom'], 0, 2)) ?></div>
          <div class="agf-partner-name"><?= htmlspecialchars($partner['nom']) ?></div>
          <div class="agf-partner-type"><?= htmlspecialchars($partner['type_partenaire']) ?></div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php foreach ($partenaires as $partner): ?>
        <?php if (!empty($partner['logo_url'])): ?>
        <div class="agf-partner-card">
          <img class="agf-partner-logo" src="<?= base_url($partner['logo_url']) ?>" alt="<?= htmlspecialchars($partner['nom']) ?>">
          <div class="agf-partner-name"><?= htmlspecialchars($partner['nom']) ?></div>
          <div class="agf-partner-type"><?= htmlspecialchars($partner['type_partenaire']) ?></div>
        </div>
        <?php else: ?>
        <div class="agf-partner-card">
          <div class="agf-partner-logo" style="display:flex;align-items:center;justify-content:center;background:#116E63;border-radius:8px;color:#fff;font-weight:700;font-size:20px;"><?= strtoupper(substr($partner['nom'], 0, 2)) ?></div>
          <div class="agf-partner-name"><?= htmlspecialchars($partner['nom']) ?></div>
          <div class="agf-partner-type"><?= htmlspecialchars($partner['type_partenaire']) ?></div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</div>



</main>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
