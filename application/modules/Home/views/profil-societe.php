<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* UD HERO — Same as abiprof-industries */
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
      <h1 class="ud-hero-title">Company Profile</h1>
      <p class="ud-hero-slogan">"Executive Summary, Company Profile, Strategic Context, Vision &amp; Mission"</p>
      <a href="<?= base_url('about') ?>" class="ud-hero-btn">
        <i class="fas fa-arrow-left-long"></i> Back to About
      </a>
    </div>
  </div>
</div>



<!-- SECTION 1: EXECUTIVE SUMMARY -->
<section class="agf-about mb-4" id="s1">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Executive <span>Summary</span></h2>
    </div>

    <!-- Hero Box — Introduction -->
    <div class="s1-hero-box">
      <h3>African Green Farmers Limited (A.G.F Limited)</h3>
      <p>Duly incorporated on 12 March 2025, African Green Farmers (A.G.F.) Limited is a <span class="s1-highlight">privately owned Zambian agro-industrial enterprise</span> holding a Zambia Development Agency (ZDA) Investment Licence (ZDA/59004/10/2025) and five-year Ministry of Finance investment incentives (Ref. No. ZDA/DG/DUTY, 22 January 2026). A.G.F. is currently at the <span class="s1-highlight">operational pilot and industrialization-preparation stage</span>, with pilot food processing, product development and process validation underway. The USD 63.21 million financing requirement is <span class="s1-highlight">industrialization, scale-up and commercialization capital—not start-up capital</span>—to develop the planned integrated industrial manufacturing platform through <span class="s1-highlight">ANINOVA INDUSTRIES</span> (Advanced Natural, Integrated Nutraceutical, Organic &amp; Value-Added Agro-Bio Industries).</p>
      <p style="margin-top:12px; font-size:14px; color:#888; font-style:italic;">Current operations do not represent established industrial manufacturing or material commercial revenue generation. Industrial production, sales and export revenues are prospective following financing, construction, commissioning, qualification, applicable regulatory approvals and commercial launch.</p>
    </div>

    <!-- 4 Product Categories -->
    <div class="s1-product-grid">
      <div class="s1-product-card">
        <div class="s1-num">1</div>
        <h5>Natural Extracts &amp; Bioactives</h5>
        <p>GMP Quality — from crude to high-purity grade</p>
      </div>
      <div class="s1-product-card">
        <div class="s1-num">2</div>
        <h5>Nutraceuticals</h5>
        <p>Nutritionally and therapeutically targeted products</p>
      </div>
      <div class="s1-product-card">
        <div class="s1-num">3</div>
        <h5>Dietary Supplements</h5>
        <p>Advanced formulations for health</p>
      </div>
      <div class="s1-product-card">
        <div class="s1-num">4</div>
        <h5>Clean-Label Foods &amp; Beverages</h5>
        <p>Including functional and fortified products</p>
      </div>
    </div>

    <!-- Capital USD 63M -->
    <div class="s1-capital-box">
      <div class="s1-capital-icon"><i class="fas fa-coins"></i></div>
      <div>
        <div class="s1-capital-amount">USD 63,209,692</div>
        <div class="s1-capital-label">Secured Senior Development Facility — Industrialization, scale-up and commercialization capital (not start-up capital) for ANINOVA INDUSTRIES, destined to establish one of Southern Africa's leading integrated agro-industrial manufacturing operations.</div>
      </div>
    </div>

    <!-- Vertically Integrated Model -->
    <div class="s1-sub-title">
      <i class="fas fa-link"></i>
      <h4>Vertically Integrated Operational Model</h4>
    </div>
    <p style="font-size: 16px;">The project combines organic farming production, industrial manufacturing, organic agricultural inputs, research laboratories, quality assurance systems, logistics infrastructure, and commercialization networks within a single vertically integrated operational model designed to <strong>maximize value addition</strong>, strengthen <strong>supply chain security</strong>, and generate <strong>sustainable long-term cash flows</strong>, underpinned by <strong>SBLC</strong> (Standby Letter of Credit) and <strong>DSRA</strong> (Debt Service Reserve Account) security mechanisms.</p>

    <!-- Health Products -->
    <div class="s1-sub-title mt-5">
      <i class="fas fa-heartbeat"></i>
      <h4>ANINOVA INDUSTRIES Health Products</h4>
    </div>
    <p style="font-size: 16px;">ANINOVA INDUSTRIES health products include:</p>
    <div class="row g-3 mt-2">
      <div class="col-md-6">
        <div class="s1-platform-item">
          <i class="fas fa-check-circle"></i>
          <span>Fortified instant soy milk powder</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="s1-platform-item">
          <i class="fas fa-check-circle"></i>
          <span>Fortified plant-based milk blend (soy + almond + coconut)</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="s1-platform-item">
          <i class="fas fa-check-circle"></i>
          <span>Enriched fruit juice blends</span>
        </div>
      </div>
      <div class="col-md-12">
        <div class="s1-platform-item">
          <i class="fas fa-check-circle"></i>
          <span>Standardized botanical extracts at ultra-premium purity (99%) sourced from local organic agricultural crops (fruits, vegetables, botanical leaves, roots, seeds/grains, nuts, etc.)</span>
        </div>
      </div>
    </div>

    <!-- Advanced Manufacturing Technologies -->
    <div class="s1-sub-title mt-5">
      <i class="fas fa-cogs"></i>
      <h4>Advanced Manufacturing Technologies — ANINOVA INDUSTRIES</h4>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-microscope"></i></div>
          <div class="agf-tech-content">
            <h4>1. Precision Metabolic Engineering</h4>
            <p>Plant and microbial cell/tissue systems, elicitation and fermentation, biosynthesis, enzymatic biotransformation, and manufacturing.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-snow"></i></div>
          <div class="agf-tech-content">
            <h4>2. Cryogenic Cold-Chain Extraction</h4>
            <p>Liquid nitrogen (LN2) assisted and dry-cake lyophilization.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-droplet"></i></div>
          <div class="agf-tech-content">
            <h4>3. Active/Dynamic Vacuum Drying</h4>
            <p>For high-viscosity, lipid-rich botanical emulsions/extracts.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-gear"></i></div>
          <div class="agf-tech-content">
            <h4>4. Advanced Cryogenic Micronization</h4>
            <p>Liquid nitrogen assisted.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-flask"></i></div>
          <div class="agf-tech-content">
            <h4>5. Hybrid/Integrated Extraction</h4>
            <p>Ethanol/ethyl acetate and supercritical CO2.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-stars"></i></div>
          <div class="agf-tech-content">
            <h4>6. Multi-Stage Bioactive Purification</h4>
            <p>Ultra-high purity.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-capsules"></i></div>
          <div class="agf-tech-content">
            <h4>7. GMP Dosage Formulation &amp; Manufacturing</h4>
            <p>Advanced with precision fortification.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-chart-line"></i></div>
          <div class="agf-tech-content">
            <h4>8. Precision Fortification</h4>
            <p>Macronutrients, micronutrients, and bioactives.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-box"></i></div>
          <div class="agf-tech-content">
            <h4>9. Nitrogen-Sealed GMP Packaging</h4>
            <p>Optimal product protection.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Integrated Platform -->
    <div class="s1-sub-title mt-5">
      <i class="fas fa-th-large"></i>
      <h4>The Integrated Platform</h4>
    </div>
    <div class="s1-platform-grid">
      <div class="s1-platform-item">
        <i class="fas fa-seedling"></i>
        <span>Over 2,000 hectares of integrated organic farming production systems</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-users"></i>
        <span>Over 5,000 contract farmers</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-industry"></i>
        <span>ANINOVA INDUSTRIES advanced manufacturing facility on 5 hectares</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-flask"></i>
        <span>Advanced CERIQA LABORATORIES scientific platforms (bioactive profiling, characterization, standardization, analytical-method validation, quality control, stability and safety assessment)</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-tractor"></i>
        <span>ABINOVA AGRO-ESTATES</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-leaf"></i>
        <span>ABINOVA LIVESTOCK BIORESOURCE systems (150 dairy cattle and 150 swine)</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-store"></i>
        <span>NATURAL HEALTH FOOD SUPERMARKETS retail network</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-globe"></i>
        <span>Domestic, regional, and international commercialization channels</span>
      </div>
    </div>

    <!-- Closing -->
    <div class="agf-callout mt-4">
      <p>The facility supports the transition from an existing pilot processing unit to a <strong>fully integrated, large-scale industrial production platform</strong>.</p>
    </div>
  </div>
</section>


<!-- COUNTER -->
<div class="agf-counter">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-cash-stack"></i></div>
          <div>
            <span class="agf-cbox-num">$63.2M</span>
            <h6 class="agf-cbox-title">Investment Sought</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-geo-alt"></i></div>
          <div>
            <span class="agf-cbox-num">2,000+</span>
            <h6 class="agf-cbox-title">Hectares Platform</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-box-seam"></i></div>
          <div>
            <span class="agf-cbox-num">11</span>
            <h6 class="agf-cbox-title">Flagship Products</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="agf-cbox">
          <div class="agf-cbox-icon"><i class="bi bi-people"></i></div>
          <div>
            <span class="agf-cbox-num">5,000+</span>
            <h6 class="agf-cbox-title">Contract Farmers</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SECTION 2: CREDIT EXECUTIVE SUMMARY -->
<section style="background:#F2F3F5; padding:80px 0;" id="s2">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Credit <span>Executive Summary</span></h2>
    </div>
    <div class="s2-hero-box">
      <p>A.G.F. Limited seeks a USD 63,209,692 senior secured development facility to finance industrialization, scale-up, commissioning and commercialization of the planned <span class="s2-highlight">ANINOVA INDUSTRIES</span> platform.</p>
    </div>
    <div class="s2-hero-box">
      <p>The credit proposition is supported by the secured ZDA Investment Licence, five-year Ministry of Finance investment incentives, operational pilot platform, secured agricultural land, developed product portfolio, CERIQA Laboratories, notarized off-take arrangements and defined implementation programme.</p>
    </div>
    <div class="s2-hero-box">
      <p>The proposed financing structure comprises an <span class="s2-highlight">SBLC mechanism through Absa Bank Zambia Plc, escrow, DSRA, milestone-based disbursement and independent verification</span>.</p>
    </div>
    <div class="s2-flowchart-box">
      <h4><i class="bi bi-diagram-3"></i> A.G.F Limited Strategic Business Unit Flowchart</h4>
      <div class="text-center">
        <img src="<?= base_url('assets/image/sbu-flowchart.jpeg') ?>" alt="A.G.F Limited Strategic Business Unit Flowchart" class="img-fluid" style="border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); max-width: 100%;">
      </div>
    </div>
    <div class="agf-callout">
      <p>The project is designed to create a <strong>scalable industrial ecosystem</strong>, capable of sustaining durable debt repayment while generating significant economic, social, technological, and environmental development impact.</p>
    </div>
  </div>
</section>

<!-- SECTION 3: COMPANY PROFILE -->
<section class="agf-about" id="s3">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Company <span>Profile</span></h2>
    </div>
    <div class="s3-table-box">
      <div class="s3-header">
        <i class="fas fa-building"></i>
        <h4>Legal &amp; Administrative Information</h4>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-tag"></i> Legal Name</div>
        <div class="s3-row-value">African Green Farmers (A.G.F) Limited</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-hashtag"></i> TPIN (Tax Pay Incorporation Number)</div>
        <div class="s3-row-value">2003675243</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-file-alt"></i> Investment License</div>
        <div class="s3-row-value">ZDA/59004/10/2025</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-percent"></i> Tax Incentives (5 years)</div>
        <div class="s3-row-value">Ref. ZDA/DG/DUTY, 22 January 2026 (Ministry of Finance)</div>
      </div>
      <div class="s3-row">
        <div class="s3-row-label"><i class="fas fa-university"></i> SBLC Mechanism</div>
        <div class="s3-row-value">In preparation with Absa Bank Zambia</div>
      </div>
    </div>
    <p style="font-size:13px; color:#999; font-style:italic; margin-top:12px;">These establish the Company's corporate, investment, pilot-operation and project-development foundation, not historical industrial manufacturing revenue.</p>
  </div>
</section>

<!-- SECTION 4: STRATEGIC CONTEXT & RATIONALE -->
<section style="background:#F2F3F5; padding:80px 0;" id="s4">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Strategic Context &amp; <span>Rationale</span></h2>
    </div>
    <div class="s4-hero-box">
      <h3><i class="fas fa-globe-africa"></i> Why Zambia, Why Now</h3>
      <p>The Republic of Zambia presents one of Africa's most compelling agro-industrial investment destinations, driven by political stability, strategic central location in Southern Africa, abundant freshwater resources, and over 42 million hectares of arable land, much of which remains available at highly competitive acquisition costs. With access to regional markets via SADC, COMESA, and the African Continental Free Trade Area (AfCFTA), Zambia offers a gateway to a rapidly expanding consumer base while providing significant opportunities in agricultural commercialization, industrial value addition, and export-driven growth.</p>
    </div>
    <div class="s2-hero-box">
      <p>A.G.F Limited is strategically positioned to capitalize on these structural advantages through a vertically integrated agro-industrial and biotechnology platform, combining large-scale regenerative agriculture, advanced manufacturing, laboratory scientific research, and quality control systems, alongside export commercialization. The project transforms underutilized agricultural resources into food, nutraceutical, and organic agricultural products rich in bioactives and nutrients, reducing import dependency, generating foreign currency, creating sustainable jobs, fostering rural industrialization and long-term sustainable economic development, while delivering strong commercial returns and institutional-grade investment value.</p>
    </div>
    <div class="s4-advantage-grid">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-shield-check"></i></div>
        <h5>Political Stability</h5>
        <p>Stable democracy and investor-friendly environment.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-geo-alt"></i></div>
        <h5>Strategic Location</h5>
        <p>Access to SADC, COMESA, and AfCFTA markets.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-water"></i></div>
        <h5>Abundant Resources</h5>
        <p>Over 42 million hectares of arable land.</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="bi bi-rocket-takeoff"></i></div>
        <h5>Industrialization</h5>
        <p>Strong government agenda for agro-industrial development.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 5 & 6: VISION & MISSION STATEMENT -->
<section class="agf-about mb-4" id="s5">
  <div class="container">
    <div class="agf-sh text-center mb-4">
      <h2>Vision &amp; <span>Mission</span> Statement</h2>
    </div>
    <div class="s5-vm-grid">
      <div class="s5-vm-card" id="s5-card">
        <div class="s5-vm-badge"><i class="bi bi-eye"></i> Vision</div>
        <h3>To become a leading African agro-industrial enterprise, driving sustainable food manufacturing, regenerative (organic) agriculture, rural industrialization, and export-oriented value addition.</h3>
      </div>
      <div class="s5-vm-card" id="s6">
        <div class="s5-vm-badge"><i class="bi bi-bullseye"></i> Mission</div>
        <h3>To develop a scalable agro-industrial ecosystem that transforms agricultural resources into food and agricultural products rich in bioactives and nutrients, through advanced processing technologies and inclusive large-scale rural participation.</h3>
      </div>
    </div>
    <p style="text-align:center; font-size:13px; color:#999; font-style:italic; margin-top:20px;">These are project objectives and financial-model projections, not historical performance or current sales. Target: 50,000+ employment opportunities, USD 100 million+ annual export earnings, 5,000+ contracted farmers.</p>
  </div>
</section>



</main>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
