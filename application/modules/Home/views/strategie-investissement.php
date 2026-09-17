<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* UD HERO — Same as profil-societe */
.ud-hero {
  position: relative;
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;
  padding: 140px 0 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  z-index: 1;
}
.ud-hero::before {
  content: "";
  position: absolute;
  left: 0; top: 0; width: 100%; height: 100%;
  background: rgba(11, 28, 57, .75);
  z-index: -1;
}
.ud-hero-content { position: relative; z-index: 1; }
.ud-hero-logo {
  width: 80px; height: 80px;
  border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
  border: 4px solid #dcbb07;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  margin-bottom: 20px;
  object-fit: cover;
}
.ud-hero-title {
  font-family: 'Yantramanav', sans-serif;
  font-size: 56px !important;
  font-weight: 800 !important;
  color: #ffffff !important;
  margin-bottom: 10px;
  line-height: 1.1;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
.ud-hero-title,
.ud-hero-title span,
.ud-hero-content h1,
.ud-hero-content h1 span,
div.ud-hero .ud-hero-title,
div.ud-hero .ud-hero-content h1 {
  color: #ffffff !important;
}
h1.ud-hero-title {
  color: #ffffff !important;
}
.ud-hero-slogan {
  color: rgba(255,255,255,0.85) !important;
  font-size: 18px;
  font-style: italic;
  margin-bottom: 25px;
}
.ud-hero-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #dcbb07;
  color: #fff !important;
  padding: 14px 28px;
  border-radius: 50px 50px 50px 0;
  font-weight: 600;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.4s ease;
  border: none;
  cursor: pointer;
}
.ud-hero-btn:hover {
  background: #116E63;
  color: #fff !important;
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(17,110,99,0.4);
}

.agf-main { font-family: 'Roboto', sans-serif !important; padding-top: 0 !important; margin-top: 0 !important; }
.agf-main h1,.agf-main h2,.agf-main h3,.agf-main h4 { font-family: 'Yantramanav', sans-serif !important; color: #19232B !important; font-weight: 600; line-height: 1.2; }
.agf-main p { color: #757F95; line-height: 1.8; }
.agf-main a { color: #19232B; transition: all 0.3s ease; text-decoration: none; }
.agf-main a:hover { color: #116E63; }
.agf-sh { margin-bottom: 50px; position: relative; z-index: 1; }
.agf-sh h2 { font-weight: 800; text-transform: capitalize; font-size: 48px; color: #19232B !important; margin-bottom: 0; }
.agf-sh h2 span { color: #dcbb07 !important; }
.agf-sh p { margin-top: 15px; }

/* Section Index */
.agf-section-index { background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 40px; }
.agf-section-index h4 { font-size: 14px; color: #116E63 !important; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
.agf-section-index ol { margin: 0; padding-left: 20px; }
.agf-section-index li { margin-bottom: 8px; }
.agf-section-index a { color: #19232B; font-weight: 500; transition: all 0.3s ease; }
.agf-section-index a:hover { color: #116E63; padding-left: 5px; }

/* Fact Grid */
.agf-fact-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 30px; }
.agf-fact-card { background: #f8f9fa; padding: 20px; border-radius: 16px; border-left: 4px solid #116E63; transition: all 0.3s ease; }
.agf-fact-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.agf-fact-label { display: block; font-size: 12px; color: #116E63; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
.agf-fact-value { display: block; font-size: 16px; color: #19232B; font-weight: 700; }

/* VM Grid */
.agf-vm-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; }
.agf-vm-card { background: #fff; padding: 40px; border-radius: 50px 50px 50px 0; box-shadow: 0 0 40px 5px rgba(0,0,0,0.05); border-top: 4px solid #dcbb07; transition: all 0.3s ease; }
.agf-vm-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
.agf-vm-card h3 { font-size: 22px; color: #19232B !important; margin: 15px 0; }

/* Values Grid */
.agf-values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
.agf-value-card { background: #fff; padding: 30px; border-radius: 50px 50px 50px 0; box-shadow: 0 0 40px 5px rgba(0,0,0,0.05); text-align: center; transition: all 0.3s ease; border: 1px solid #f0f0f0; }
.agf-value-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
.agf-value-icon { width: 80px; height: 80px; background: #116E63; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.agf-value-icon i { font-size: 32px; color: #fff; }
.agf-value-card h4 { font-size: 18px; color: #19232B !important; margin-bottom: 10px; }
.agf-value-card p { font-size: 14px; color: #757F95; margin: 0; }

/* Tech Card */
.agf-tech-card { display: flex; gap: 20px; padding: 25px; background: #fff; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 20px; transition: all 0.3s ease; }
.agf-tech-card:hover { transform: translateX(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.agf-tech-icon { width: 60px; height: 60px; background: #116E63; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.agf-tech-icon i { font-size: 24px; color: #fff; }
.agf-tech-content h4 { font-size: 16px; color: #19232B !important; margin-bottom: 5px; }
.agf-tech-content p { font-size: 13px; margin: 0; }

/* Counter */
.agf-counter { position: relative; background: url('<?= base_url("attachments/Parametres/2026030221535169a5eacf8b9bd.jpg") ?>') no-repeat center center; background-size: cover; background-attachment: fixed; padding: 80px 0; }
.agf-counter::before { content: ''; position: absolute; background: rgba(26,54,93,0.92); left: 0; top: 0; width: 100%; height: 100%; z-index: 0; }
.agf-cbox { display: flex; align-items: center; justify-content: center; flex-direction: column; text-align: center; gap: 20px; position: relative; z-index: 1; padding: 20px 0; }
.agf-cbox-icon { width: 80px; height: 80px; background: #dcbb07; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; display: flex; align-items: center; justify-content: center; }
.agf-cbox-icon i { font-size: 32px; color: #fff; }
.agf-cbox-num { display: block; line-height: 1; color: #fff; font-size: 42px; font-weight: 600; }
.agf-cbox-title { color: #fff; font-size: 16px; font-weight: 500; margin: 0; }

/* Table */
.agf-table-wrap { overflow-x: auto; }
.agf-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
.agf-table th { background: #116E63; color: #fff; padding: 15px 20px; text-align: left; font-weight: 600; font-size: 14px; }
.agf-table td { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; font-size: 14px; color: #757F95; }
.agf-table tr:last-child td { border-bottom: none; }
.agf-table tr:hover td { background: #f8f9fa; }

/* Callout */
.agf-callout { background: #116E63; color: #fff; padding: 30px; border-radius: 16px; margin-top: 30px; }
.agf-callout p { color: #fff !important; margin: 0; }

/* Enhanced Section 1 — Executive Summary */
.s1-hero-box {
  background: #19232B;
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  margin-bottom: 40px;
}
.s1-hero-box::before {
  content: '';
  position: absolute;
  top: -50%; right: -20%;
  width: 400px; height: 400px;
  background: rgba(220,187,7,0.06);
  border-radius: 50%;
}
.s1-hero-box::after {
  content: '';
  position: absolute;
  bottom: -30%; left: -10%;
  width: 300px; height: 300px;
  background: rgba(255,255,255,0.02);
  border-radius: 50%;
}
.s1-hero-box h3 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 28px;
  font-weight: 800;
  color: #fff !important;
  margin-bottom: 20px;
  position: relative;
  z-index: 1;
}
.s1-hero-box p {
  color: rgba(255,255,255,0.9) !important;
  line-height: 1.9;
  font-size: 16px;
  position: relative;
  z-index: 1;
}
.s1-hero-box .s1-highlight {
  color: #dcbb07;
  font-weight: 700;
}

/* 4 Product Categories */
.s1-product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin: 40px 0;
}
.s1-product-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px 20px;
  text-align: center;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  border-top: 4px solid #dcbb07;
  transition: all 0.3s ease;
  position: relative;
}
.s1-product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}
.s1-product-card .s1-num {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px; height: 40px;
  background: #116E63;
  color: #fff;
  border-radius: 50%;
  font-weight: 800;
  font-size: 16px;
  margin-bottom: 15px;
}
.s1-product-card h5 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #19232B !important;
  margin-bottom: 10px;
}
.s1-product-card p {
  font-size: 13px;
  color: #757F95 !important;
  line-height: 1.6;
  margin: 0;
}

/* Capital Highlight */
.s1-capital-box {
  background: #fff;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  border-left: 5px solid #dcbb07;
  display: flex;
  align-items: center;
  gap: 30px;
  margin-bottom: 40px;
}
.s1-capital-icon {
  width: 80px; height: 80px;
  background: #116E63;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.s1-capital-icon i { font-size: 32px; color: #fff; }
.s1-capital-amount {
  font-family: 'Yantramanav', sans-serif;
  font-size: 36px;
  font-weight: 800;
  color: #116E63;
  line-height: 1;
}
.s1-capital-label {
  font-size: 14px;
  color: #757F95;
  margin-top: 5px;
}

/* Section Sub-Title */
.s1-sub-title {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 25px;
}
.s1-sub-title i {
  width: 40px; height: 40px;
  background: #116E63;
  color: #fff;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
}
.s1-sub-title h4 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 22px;
  font-weight: 700;
  color: #19232B !important;
  margin: 0;
}

/* Platform List — Cards */
.s1-platform-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin: 25px 0;
}
.s1-platform-item {
  background: #fff;
  border-radius: 14px;
  padding: 18px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 3px 15px rgba(0,0,0,0.05);
  border-left: 3px solid #116E63;
  transition: all 0.3s ease;
}
.s1-platform-item:hover {
  transform: translateX(5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.s1-platform-item i {
  color: #dcbb07;
  font-size: 20px;
  flex-shrink: 0;
}
.s1-platform-item span {
  font-size: 14px;
  color: #19232B;
  font-weight: 500;
  line-height: 1.4;
}

/* Section 2 — Credit Executive Summary */
.s2-hero-box {
  background: #19232B;
  border-radius: 20px;
  padding: 40px;
  color: #fff;
  margin-bottom: 30px;
  position: relative;
  overflow: hidden;
}
.s2-hero-box p {
  color: rgba(255,255,255,0.88) !important;
  line-height: 1.8;
  font-size: 15px;
  margin-bottom: 0;
}
.s2-hero-box .s2-highlight {
  color: #dcbb07;
  font-weight: 700;
}
.s2-flowchart-box {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  margin: 30px 0;
  border-top: 4px solid #dcbb07;
}
.s2-flowchart-box h4 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #19232B !important;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.s2-flowchart-box h4 i {
  width: 36px; height: 36px;
  background: #116E63;
  color: #fff;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
}

/* Section 3 — Company Profile */
.s3-table-box {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  border-top: 4px solid #116E63;
}
.s3-header {
  background: #19232B;
  padding: 25px 30px;
  display: flex;
  align-items: center;
  gap: 15px;
}
.s3-header i {
  width: 44px; height: 44px;
  background: #dcbb07;
  color: #fff;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}
.s3-header h4 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #fff !important;
  margin: 0;
}
.s3-row {
  display: flex;
  align-items: stretch;
  border-bottom: 1px solid #f0f0f0;
  transition: background 0.3s ease;
}
.s3-row:last-child { border-bottom: none; }
.s3-row:hover { background: #f8f9fa; }
.s3-row-label {
  flex: 0 0 300px;
  padding: 18px 30px;
  background: #fafbfc;
  font-weight: 700;
  font-size: 14px;
  color: #19232B;
  display: flex;
  align-items: center;
  gap: 10px;
}
.s3-row-label i {
  color: #116E63;
  font-size: 14px;
  flex-shrink: 0;
}
.s3-row-value {
  flex: 1;
  padding: 18px 30px;
  font-size: 14px;
  color: #757F95;
  display: flex;
  align-items: center;
}

/* Section 4 — Strategic Context */
.s4-hero-box {
  background: #19232B;
  border-radius: 20px;
  padding: 40px;
  color: #fff;
  margin-bottom: 30px;
  position: relative;
  overflow: hidden;
}
.s4-hero-box h3 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 22px;
  font-weight: 800;
  color: #fff !important;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.s4-hero-box h3 i {
  width: 40px; height: 40px;
  background: #dcbb07;
  color: #19232B;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
}
.s4-hero-box p {
  color: rgba(255,255,255,0.88) !important;
  line-height: 1.8;
  font-size: 15px;
  margin-bottom: 0;
}
.s4-advantage-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: 30px;
}
.s4-advantage-card {
  background: #fff;
  border-radius: 16px;
  padding: 30px 20px;
  text-align: center;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  border-bottom: 4px solid #116E63;
  transition: all 0.3s ease;
}
.s4-advantage-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}
.s4-advantage-card .s4-icon {
  width: 60px; height: 60px;
  background: #116E63;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 15px;
}
.s4-advantage-card .s4-icon i {
  font-size: 24px;
  color: #fff;
}
.s4-advantage-card h5 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #19232B !important;
  margin-bottom: 8px;
}
.s4-advantage-card p {
  font-size: 13px;
  color: #757F95 !important;
  margin: 0;
  line-height: 1.5;
}

/* Section 5&6 — Vision & Mission Statement */
.s5-vm-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 30px;
}
.s5-vm-card {
  background: #fff;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 5px 25px rgba(0,0,0,0.06);
  border-top: 4px solid #dcbb07;
  transition: all 0.3s ease;
  position: relative;
}
.s5-vm-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}
.s5-vm-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #116E63;
  color: #fff;
  padding: 10px 22px;
  border-radius: 50px;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.s5-vm-card h3 {
  font-family: 'Yantramanav', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #19232B !important;
  margin-top: 20px;
  line-height: 1.6;
}

@media (max-width: 991px) {
  .agf-sh h2 { font-size: 36px; }
  .agf-vm-grid, .agf-fact-grid { grid-template-columns: 1fr; }
  .s1-product-grid { grid-template-columns: repeat(2, 1fr); }
  .s1-platform-grid { grid-template-columns: 1fr; }
  .s1-capital-box { flex-direction: column; text-align: center; }
  .s4-advantage-grid { grid-template-columns: repeat(2, 1fr); }
  .s5-vm-grid { grid-template-columns: 1fr; }
  .s3-row { flex-direction: column; }
  .s3-row-label { flex: none; }
}
@media (max-width: 575px) {
  .s1-product-grid { grid-template-columns: 1fr; }
  .s4-advantage-grid { grid-template-columns: 1fr; }
}
</style>

<main class="agf-main">

<!-- HERO -->
<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">Strategy &amp; Investment</h1>
      <p class="ud-hero-slogan">"Investment justification, 10-year strategic objectives, detailed funding requirements, and project readiness status."</p>
      <a href="<?= base_url('about') ?>" class="ud-hero-btn"><i class="fas fa-arrow-left-long"></i> Back to About</a>
    </div>
  </div>
</div>



<!-- S1: GLOBAL OPPORTUNITY -->
<section class="agf-about" id="s1">
  <div class="container">
    <div class="s1-hero-box">
      <h3>Global Opportunity — Why This Investment</h3>
      <p>The growing international demand for value-added food products, organic agricultural inputs, sustainable agriculture and traceable supply chains continues to create significant opportunities for integrated manufacturing platforms.</p>
      <p class="mt-3">Rapid demographic growth, urbanization, industrialization and regional market integration continue to expand demand for locally manufactured, high-quality agricultural and food products.</p>
    </div>
  </div>
</section>

<!-- S2: STRATEGIC ADVANTAGES OF ZAMBIA -->
<section style="background:#F2F3F5; padding:80px 0;" id="s2">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Strategic Advantages of <span>Zambia</span></h2>
    </div>
    <div class="s4-advantage-grid">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-shield-check"></i></div>
        <h5>Political Stability</h5>
        <p>Stable democracy with investor-friendly policies.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-geo-alt"></i></div>
        <h5>Central Location</h5>
        <p>Strategic access to SADC, COMESA and AfCFTA markets.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-water"></i></div>
        <h5>Abundant Resources</h5>
        <p>Over 42 million hectares of arable land and freshwater.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-rocket-takeoff"></i></div>
        <h5>Industrialization Agenda</h5>
        <p>Strong government push for agro-industrial development.</p>
      </div>
    </div>
  </div>
</section>

<!-- S3: COMPETITIVE ADVANTAGES -->
<section class="agf-about" id="s3">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Competitive <span>Advantages</span></h2>
    </div>
    <div class="s4-advantage-grid">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-arrow-down-up"></i></div>
        <h5>Vertical Integration</h5>
        <p>Complete control from farm to finished product.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-box-seam"></i></div>
        <h5>Input Security</h5>
        <p>Internal raw material supply chain.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-people"></i></div>
        <h5>Contract Farming</h5>
        <p>5,000+ farmers integrated into the value chain.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-lightbulb"></i></div>
        <h5>Innovation-Driven</h5>
        <p>R&amp;D-powered product portfolio and manufacturing.</p>
      </div>
    </div>
  </div>
</section>

<!-- S4: 10-YEAR STRATEGIC TARGETS -->
<section style="background:#F2F3F5; padding:80px 0;" id="s4">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>10-Year Strategic <span>Targets</span> (2026–2035)</h2>
    </div>
    <div class="s4-advantage-grid">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-dollar-sign"></i></div>
        <h5>$196M+</h5>
        <p>Cumulative Net Profit</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-globe"></i></div>
        <h5>$100M+</h5>
        <p>Annual Export Revenue</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-users"></i></div>
        <h5>50,000+</h5>
        <p>Sustainable Jobs Created</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-seedling"></i></div>
        <h5>5,000+</h5>
        <p>Contract Farmers</p>
      </div>
    </div>
    <p class="mt-3" style="font-size:13px; color:#999; font-style:italic;">These are project objectives and financial-model projections, not historical performance or current sales.</p>
  </div>
</section>

<!-- S5: FUND ALLOCATION -->
<section class="agf-about" id="s5">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Fund Allocation <span>(USD 63.2M)</span></h2>
    </div>
    <div class="s3-table-box">
      <div class="s3-header">
        <i class="fas fa-coins"></i>
        <h4>Total Project Investment Cost (TPIC) — USD 63,209,692</h4>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-hard-hat"></i> Engineering, Procurement &amp; Construction (EPC)</div>
        <div class="s3-row-value">USD 1,377,745 — 18%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-industry"></i> Industrial Manufacturing Infrastructure</div>
        <div class="s3-row-value">USD 15,170,326 — 24%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-cogs"></i> Industrial Processing Lines</div>
        <div class="s3-row-value">USD 13,274,035 — 21%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-microscope"></i> Research, QA &amp; Innovation Laboratories</div>
        <div class="s3-row-value">USD 3,792,581 — 6%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-seedling"></i> Agricultural Production Infrastructure</div>
        <div class="s3-row-value">USD 5,056,775 — 8%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-leaf"></i> Livestock &amp; Bio-Resources Infrastructure</div>
        <div class="s3-row-value">USD 2,528,388 — 4%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-bolt"></i> Utilities, Energy &amp; Water Systems</div>
        <div class="s3-row-value">USD 3,160,485 — 5%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-laptop"></i> Digital Traceability &amp; ACIDS Systems</div>
        <div class="s3-row-value">USD 1,264,194 — 2%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-clipboard-check"></i> Project Management &amp; Regulatory Compliance</div>
        <div class="s3-row-value">USD 1,264,194 — 2%</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-money-bill-wave"></i> Working Capital &amp; Commercial Launch</div>
        <div class="s3-row-value">USD 4,424,678 — 7%</div>
      </div>
    </div>
    <p class="mt-3" style="font-size:13px; color:#999; font-style:italic;">Financial reconciliation required: The listed category amounts currently total USD 51,313,401, leaving USD 11,896,291 unreconciled. The EPC amount also does not correspond to 18% of the stated TPIC. Final Sources &amp; Uses must reconcile exactly to USD 63,209,692 / 100.00% before lender or investor submission.</p>
  </div>
</section>

<!-- S6: PROJECT MATURITY & BANKABILITY -->
<section style="background:#F2F3F5; padding:80px 0;" id="s6">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Project Maturity &amp; <span>Bankability</span></h2>
    </div>
    <div class="s5-vm-grid">
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-building"></i> Milestone</div>
        <h3>Company Incorporated</h3>
        <p>Registered with ZRA, investment license obtained from ZDA.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-hand-holding-usd"></i> Milestone</div>
        <h3>Tax Incentives Secured</h3>
        <p>5-year Ministry of Finance investment incentives: Ref. No. ZDA/DG/DUTY, 22 January 2026.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-utensils"></i> Milestone</div>
        <h3>AFOOPROC Pilot Operational</h3>
        <p>Pilot food-processing and validation platform operational.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-box-open"></i> Milestone</div>
        <h3>11 Products Developed</h3>
        <p>Initial 11-product portfolio developed; five products ZBS-certified.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-map-marked-alt"></i> Milestone</div>
        <h3>97 ha Agricultural Land</h3>
        <p>Secured; expansion toward 2,000+ ha planned.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-microscope"></i> Milestone</div>
        <h3>CERIQA Scientific Platform</h3>
        <p>CERIQA scientific and quality platform established.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-file-signature"></i> Milestone</div>
        <h3>Notarized Off-Take Arrangements</h3>
        <p>Identified for designated markets.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-gavel"></i> Milestone</div>
        <h3>Regulatory Pathway Structured</h3>
        <p>Through ZAMRA, ZEMA and applicable international requirements.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-cogs"></i> Milestone</div>
        <h3>Technology Specifications Defined</h3>
        <p>Procurement requirements and specifications defined.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-university"></i> Milestone</div>
        <h3>SBLC In Readiness</h3>
        <p>Standby Letter of Credit via Absa Bank Zambia Plc.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-industry"></i> Milestone</div>
        <h3>Industrial Development Planned</h3>
        <p>Under the proposed financing through ANINOVA INDUSTRIES.</p>
      </div>
      <div class="s5-vm-card">
        <div class="s5-vm-badge"><i class="fas fa-search"></i> Milestone</div>
        <h3>Due Diligence Subject</h3>
        <p>All material assumptions remain subject to independent lender/investor due diligence and third-party verification where required.</p>
      </div>
    </div>
    <p class="mt-4" style="font-size:13px; color:#999; font-style:italic;">These establish the Company's corporate, investment, pilot-operation and project-development foundation, not historical industrial manufacturing revenue.</p>
  </div>
</section>

</main>
<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
