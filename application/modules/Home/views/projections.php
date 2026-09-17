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

<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">Financial Projections</h1>
      <p class="ud-hero-slogan">"Detailed 10-year income statement: product sales, pre-operating expenses, CAPEX, OPEX, taxes, loan repayment and cash flow."</p>
      <a href="<?= base_url('about') ?>" class="ud-hero-btn"><i class="fas fa-arrow-left-long"></i> Back to About</a>
    </div>
  </div>
</div>



<!-- S1: 10-YEAR PRODUCT SALES -->
<section class="agf-about" id="s1">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>10-Year Product <span>Sales</span> (11 Products)</h2>
    </div>
    <div class="s3-table-box">
      <div class="s3-header">
        <i class="fas fa-table"></i>
        <h4>Revenue by Product — Total: USD 663.8M</h4>
      </div>
      <div style="overflow-x:auto;">
        <table class="agf-table">
          <thead>
            <tr><th>Product</th><th>Y1</th><th>Y2</th><th>Y3</th><th>Y4</th><th>Y5</th><th>Y6</th><th>Y7</th><th>Y8</th><th>Y9</th><th>Y10</th><th>Total</th></tr>
          </thead>
          <tbody>
            <tr><td><strong>Bioactive Complex</strong></td><td>2.16M</td><td>2.70M</td><td>3.24M</td><td>3.78M</td><td>4.32M</td><td>4.86M</td><td>5.24M</td><td>5.40M</td><td>7.02M</td><td>8.64M</td><td><strong>$47.4M</strong></td></tr>
            <tr><td><strong>Immune Boosting</strong></td><td>4.86M</td><td>5.40M</td><td>5.94M</td><td>6.59M</td><td>7.67M</td><td>8.75M</td><td>9.13M</td><td>10.37M</td><td>11.45M</td><td>13.07M</td><td><strong>$83.2M</strong></td></tr>
            <tr><td><strong>Antioxidant Complex</strong></td><td>2.66M</td><td>3.02M</td><td>3.38M</td><td>3.74M</td><td>4.82M</td><td>5.90M</td><td>6.62M</td><td>7.34M</td><td>8.42M</td><td>9.50M</td><td><strong>$55.4M</strong></td></tr>
            <tr><td><strong>Regenerator Plus</strong></td><td>6.48M</td><td>7.56M</td><td>8.64M</td><td>9.72M</td><td>10.80M</td><td>15.12M</td><td>17.28M</td><td>20.52M</td><td>23.76M</td><td>27.00M</td><td><strong>$146.9M</strong></td></tr>
            <tr><td><strong>Soymilk Powder</strong></td><td>2.88M</td><td>3.36M</td><td>3.84M</td><td>4.32M</td><td>4.80M</td><td>6.24M</td><td>7.20M</td><td>7.68M</td><td>9.12M</td><td>10.08M</td><td><strong>$59.5M</strong></td></tr>
            <tr><td><strong>Plant Milk Blend</strong></td><td>1.66M</td><td>1.80M</td><td>1.94M</td><td>2.23M</td><td>2.52M</td><td>2.66M</td><td>2.84M</td><td>3.10M</td><td>3.53M</td><td>3.96M</td><td><strong>$26.2M</strong></td></tr>
            <tr><td><strong>Vacuum Dried Tofu</strong></td><td>2.73M</td><td>3.15M</td><td>3.53M</td><td>3.99M</td><td>3.99M</td><td>4.41M</td><td>5.25M</td><td>5.67M</td><td>6.09M</td><td>7.35M</td><td><strong>$46.2M</strong></td></tr>
            <tr><td><strong>Bio-control Complex</strong></td><td>0.59M</td><td>0.65M</td><td>0.71M</td><td>0.83M</td><td>0.95M</td><td>1.13M</td><td>1.25M</td><td>1.31M</td><td>1.31M</td><td>1.49M</td><td><strong>$10.2M</strong></td></tr>
            <tr><td><strong>Mixed Juice Blend</strong></td><td>3.36M</td><td>3.84M</td><td>4.32M</td><td>4.80M</td><td>5.76M</td><td>6.24M</td><td>6.72M</td><td>8.16M</td><td>9.60M</td><td>11.04M</td><td><strong>$63.8M</strong></td></tr>
            <tr><td><strong>Bioshield Fertilizer</strong></td><td>2.34M</td><td>2.70M</td><td>3.06M</td><td>3.42M</td><td>3.78M</td><td>3.78M</td><td>5.00M</td><td>5.22M</td><td>6.30M</td><td>7.38M</td><td><strong>$43.0M</strong></td></tr>
            <tr><td><strong>Bio-fertilizer</strong></td><td>4.32M</td><td>5.04M</td><td>5.76M</td><td>6.48M</td><td>7.20M</td><td>9.36M</td><td>10.01M</td><td>11.52M</td><td>10.08M</td><td>12.24M</td><td><strong>$82.0M</strong></td></tr>
          </tbody>
          <tfoot>
            <tr><td><strong>Total Sales</strong></td><td><strong>34.0M</strong></td><td><strong>39.2M</strong></td><td><strong>44.4M</strong></td><td><strong>49.9M</strong></td><td><strong>56.6M</strong></td><td><strong>68.5M</strong></td><td><strong>76.5M</strong></td><td><strong>86.3M</strong></td><td><strong>96.7M</strong></td><td><strong>111.8M</strong></td><td><strong>$663.8M</strong></td></tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- S2: GROSS MARGIN & NET PROFIT -->
<section style="background:#F2F3F5; padding:80px 0;" id="s2">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Gross Margin &amp; <span>Net Profit</span></h2>
    </div>
    <div class="s4-advantage-grid">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-dollar-sign"></i></div>
        <h5>$633.1M</h5>
        <p>Gross Margin (10 Years)</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-chart-line"></i></div>
        <h5>$196.9M</h5>
        <p>Net Profit (10 Years)</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-percentage"></i></div>
        <h5>29.7%</h5>
        <p>Net Profit Margin</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-university"></i></div>
        <h5>$45.1M</h5>
        <p>Total Loan Repayment</p>
      </div>
    </div>
    <div class="s4-advantage-grid mt-4">
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-chart-bar"></i></div>
        <h5>$30.65M</h5>
        <p>Net Present Value (NPV)</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-percentage"></i></div>
        <h5>15.78%</h5>
        <p>Internal Rate of Return (IRR)</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-balance-scale"></i></div>
        <h5>1.48</h5>
        <p>Profitability Index</p>
      </div>
      <div class="s4-advantage-card">
        <div class="s4-icon"><i class="fas fa-calendar-check"></i></div>
        <h5>Year 9</h5>
        <p>Discounted Payback</p>
      </div>
    </div>
    <p class="mt-4" style="font-size:13px; color:#999; font-style:italic;">These are forward-looking projections, not historical commercial performance.</p>
  </div>
</section>

<!-- S3: INCOME STATEMENT SUMMARY -->
<section class="agf-about" id="s3">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Income Statement <span>Summary</span></h2>
    </div>
    <div class="s3-table-box">
      <div class="s3-header">
        <i class="fas fa-chart-bar"></i>
        <h4>10-Year Financial Summary</h4>
      </div>
      <div style="overflow-x:auto;">
        <table class="agf-table">
          <thead>
            <tr><th>Item</th><th>Y1</th><th>Y2</th><th>Y3</th><th>Y4</th><th>Y5</th><th>Y6</th><th>Y7</th><th>Y8</th><th>Y9</th><th>Y10</th><th>Total</th></tr>
          </thead>
          <tbody>
            <tr><td><strong>Total Sales</strong></td><td>34.0M</td><td>39.2M</td><td>44.4M</td><td>49.9M</td><td>56.6M</td><td>68.5M</td><td>76.5M</td><td>86.3M</td><td>96.7M</td><td>111.8M</td><td><strong>$663.8M</strong></td></tr>
            <tr><td><strong>COGS</strong></td><td>2.0M</td><td>2.6M</td><td>6.9M</td><td>8.2M</td><td>6.4M</td><td>0.6M</td><td>0.8M</td><td>0.6M</td><td>1.3M</td><td>1.4M</td><td><strong>$30.7M</strong></td></tr>
            <tr><td><strong>Gross Margin</strong></td><td>32.0M</td><td>36.7M</td><td>37.4M</td><td>41.7M</td><td>50.3M</td><td>67.8M</td><td>75.8M</td><td>85.7M</td><td>95.4M</td><td>110.4M</td><td><strong>$633.1M</strong></td></tr>
            <tr><td><strong>OPEX</strong></td><td>63.1M</td><td>18.5M</td><td>18.8M</td><td>20.8M</td><td>27.8M</td><td>38.4M</td><td>39.5M</td><td>41.3M</td><td>41.4M</td><td>42.3M</td><td><strong>$351.8M</strong></td></tr>
            <tr><td><strong>EBIT</strong></td><td style="color:#dc3545;">-31.1M</td><td style="color:#28a745;">18.2M</td><td style="color:#28a745;">18.6M</td><td style="color:#28a745;">20.9M</td><td style="color:#28a745;">22.4M</td><td style="color:#28a745;">29.5M</td><td style="color:#28a745;">36.3M</td><td style="color:#28a745;">44.3M</td><td style="color:#28a745;">54.1M</td><td style="color:#28a745;">68.1M</td><td><strong>$281.3M</strong></td></tr>
            <tr><td><strong>Tax (30%)</strong></td><td style="color:#dc3545;">-9.3M</td><td>5.4M</td><td>5.6M</td><td>6.3M</td><td>6.7M</td><td>8.8M</td><td>10.9M</td><td>13.3M</td><td>16.2M</td><td>20.4M</td><td><strong>$84.4M</strong></td></tr>
            <tr><td><strong>Loan Repayment</strong></td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td>4.5M</td><td><strong>$45.1M</strong></td></tr>
          </tbody>
          <tfoot>
            <tr><td><strong>Net Profit</strong></td><td style="color:#dc3545;"><strong>-21.8M</strong></td><td style="color:#28a745;"><strong>12.7M</strong></td><td style="color:#28a745;"><strong>13.0M</strong></td><td style="color:#28a745;"><strong>14.6M</strong></td><td style="color:#28a745;"><strong>15.7M</strong></td><td style="color:#28a745;"><strong>20.6M</strong></td><td style="color:#28a745;"><strong>25.4M</strong></td><td style="color:#28a745;"><strong>31.0M</strong></td><td style="color:#28a745;"><strong>37.8M</strong></td><td style="color:#28a745;"><strong>47.7M</strong></td><td><strong>$196.9M</strong></td></tr>
          </tfoot>
        </table>
      </div>
    </div>
    <p class="mt-4" style="font-size:14px; color:#757F95;">Total project cost over 10 years (pre-operating + CAPEX + OPEX): USD 351,828,612, generating cumulative net profit of USD 196,912,896 over the same period.</p>
  </div>
</section>

<!-- S4: SENSITIVITY & DISCLAIMER -->
<section style="background:#F2F3F5; padding:80px 0;" id="s4">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Sensitivity Analysis &amp; <span>Requirements</span></h2>
    </div>
    <p class="mb-4">The financial model should be supported by lender-grade sensitivity and downside analysis covering:</p>
    <div class="s1-platform-grid">
      <div class="s1-platform-item">
        <i class="fas fa-arrow-up"></i>
        <span>Construction-cost escalation</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-clock"></i>
        <span>Commissioning delays</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-chart-line"></i>
        <span>Production ramp-up and capacity utilization</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-tag"></i>
        <span>Selling-price compression</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-industry"></i>
        <span>Raw-material costs</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-exchange-alt"></i>
        <span>Foreign-exchange movements</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-chart-area"></i>
        <span>Operating-cost inflation</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-percent"></i>
        <span>Interest-rate exposure</span>
      </div>
      <div class="s1-platform-item">
        <i class="fas fa-money-check-alt"></i>
        <span>Debt-service coverage</span>
      </div>
    </div>
    <div class="s1-hero-box mt-4">
      <h3>Financial Reconciliation</h3>
      <p>The complete Sources &amp; Uses schedule and financial model must reconcile exactly to <span class="s1-highlight">USD 63,209,692 / 100.00%</span> before lender/investor submission.</p>
    </div>
    <div class="s1-hero-box">
      <h3>Commercial Status</h3>
      <p>A.G.F. Limited is currently an <span class="s1-highlight">operational pilot-stage enterprise</span>, not an established industrial manufacturing or material revenue-generating enterprise. Industrial manufacturing, material sales, exports and associated operating cash flows are prospective and dependent on successful financing, implementation, commissioning, qualification, regulatory approvals and commercial launch.</p>
      <p class="mt-3">Major industrial systems will be procured through qualified OEM/EPC counterparties under defined specifications, performance requirements, FAT/SAT, commissioning, qualification/validation, warranties, training, technology-transfer obligations and documented battery limits.</p>
    </div>
  </div>
</section>

</main>
<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>