<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
:root { --g: #B8902F; --d: #3C3C3C; --m: #7A7A7A; --l: #F2F2F2; --gl: #F3E9CF; }
/* UD HERO &mdash; Same as profil-societe */
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

/* Enhanced Section 1 &mdash; Executive Summary */
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

/* Platform List &mdash; Cards */
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

/* Section 2 &mdash; Credit Executive Summary */
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

/* Section 3 &mdash; Company Profile */
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

/* Section 4 &mdash; Strategic Context */
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

/* Section 5&6 &mdash; Vision & Mission Statement */
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

.agf-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 40px 20px 70px;
}

/* =========================================================
   CONTENU - uniquement les classes utilisees par la page
   ========================================================= */
.agf-page h2,
.agf-page h3,
.agf-page h4 {
  font-family: 'Yantramanav', sans-serif !important;
  font-weight: 600;
  line-height: 1.2;
  color: #19232B;
}
.agf-page h2.s1 {
  font-size: 28px;
  margin: 48px 0 16px;
  padding-bottom: 8px;
  border-bottom: 3px solid var(--g);
}
.agf-page h3.s2 {
  color: var(--g) !important;
  font-size: 22px;
  margin: 32px 0 10px;
}
.agf-page p {
  color: #757F95;
  line-height: 1.8;
  text-align: justify;
}
.agf-page .note {
  color: var(--m);
  font-style: italic;
  font-size: 15px;
}

/* listes a puces */
.agf-page ul.gl { list-style: none; padding: 0; margin: 12px 0; }
.agf-page ul.gl li { position: relative; padding: 5px 0 5px 28px; }
.agf-page ul.gl li::before {
  content: '\25A0';
  position: absolute;
  left: 0;
  top: 9px;
  color: var(--g);
  font-size: 12px;
}

/* images */
.agf-page .fig { margin: 26px 0; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 26px rgba(0,0,0,.15); }
.agf-page .fig img { width: 100%; display: block; max-height: 460px; object-fit: cover; }
.agf-page .fig.missing {
  border: 2px dashed var(--g);
  background: #fafafa;
  padding: 70px 20px;
  text-align: center;
  color: var(--m);
  font-size: 14px;
  box-shadow: none;
}
.agf-page .fig.missing img { display: none; }
.agf-page .fig.missing::after { content: attr(data-name); font-weight: 700; }

/* tableaux */
.agf-page .tw { overflow-x: auto; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.09); }
.agf-page table { border-collapse: collapse; width: 100%; font-size: 15px; background: #fff; }
.agf-page th { background: var(--g); color: #fff; text-align: left; padding: 12px 14px; }
.agf-page td { padding: 10px 14px; border-bottom: 1px solid #e3e3e3; vertical-align: top; }
.agf-page tr:nth-child(even) td { background: var(--l); }
.agf-page tr:hover td { background: var(--gl); }
.agf-page td:first-child { font-weight: 700; }

/* chaines / etapes */
.agf-page .flow {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  align-items: center;
  margin: 24px 0;
  padding: 18px;
  background: var(--l);
  border-left: 6px solid var(--g);
  border-radius: 8px;
}
.agf-page .flow b { background: #fff; border: 1px solid var(--g); padding: 6px 13px; border-radius: 20px; font-size: 14px; transition: .2s; }
.agf-page .flow b:hover { background: var(--g); color: #fff; transform: translateY(-2px); }
.agf-page .flow i { color: var(--g); font-style: normal; font-weight: 800; }

/* encadre */
.agf-page .call { margin: 22px 0; padding: 18px 22px; background: var(--l); border-left: 6px solid var(--g); border-radius: 8px; font-weight: 700; }

/* grilles de cartes */
.agf-page .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; margin: 20px 0; }
.agf-page .card {
  background: #fff;
  border: 1px solid #e3e3e3;
  border-top: 4px solid var(--g);
  border-radius: 10px;
  padding: 18px;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
  transition: transform .25s, box-shadow .25s;
}
.agf-page .card:hover { transform: translateY(-6px); box-shadow: 0 12px 26px rgba(0,0,0,.15); }
.agf-page .card .n { color: var(--g); font-weight: 800; font-size: 26px; }
.agf-page .card .pimg { width: 100%; height: 160px; margin-bottom: 12px; }
.agf-page .card .pimg img { width: 100%; height: 100%; object-fit: cover; max-width: none; max-height: none; }
.agf-page a.card { text-decoration: none; color: var(--d); font-weight: 700; font-size: 19px; }
.agf-page a.card:hover { background: var(--g); color: #fff; }

/* statistiques */
.agf-page .stat {
  background: #fff;
  border-bottom: 4px solid var(--g);
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(0,0,0,.13);
}
.agf-page .stat b { display: block; font-size: 42px; color: var(--g); line-height: 1.1; }
.agf-page .stat span { font-size: 14px; color: var(--m); }

/* vignettes photo / logo */
.agf-page .logo {
  background: #fff;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid transparent;
  overflow: hidden;
}
.agf-page .logo img { max-width: 88%; max-height: 88%; }
.agf-page .logo.missing { border: 2px dashed var(--g); background: #fafafa; font-size: 12px; color: var(--m); text-align: center; padding: 10px; }
.agf-page .logo.missing img { display: none; }
.agf-page .logo.missing::after { content: attr(data-name); font-weight: 700; }

/* apparition au scroll */
.agf-page .reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s, transform .7s; }
.agf-page .reveal.in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
  .agf-page .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
}

/* tableaux responsives */
@media (max-width: 720px) {
  .agf-page table.rs thead { display: none; }
  .agf-page table.rs tr { display: block; border-bottom: 3px solid var(--g); margin-bottom: 10px; }
  .agf-page table.rs td { display: flex; gap: 10px; border: 0; padding: 6px 14px; }
  .agf-page table.rs td::before { content: attr(data-l); font-weight: 700; color: var(--g); flex: 0 0 105px; }
}
</style><style>
:root { --g: #B8902F; --d: #3C3C3C; --m: #7A7A7A; --l: #F2F2F2; --gl: #F3E9CF; }
/* UD HERO - Same as profil-societe */
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

/* Enhanced Section 1 - Executive Summary */
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

/* Platform List - Cards */
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

/* Section 2 - Credit Executive Summary */
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

/* Section 3 - Company Profile */
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

/* Section 4 - Strategic Context */
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

/* Section 5&6 - Vision & Mission Statement */
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

.agf-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 40px 20px 70px;
}

/* =========================================================
   CONTENU - uniquement les classes utilisees par la page
   ========================================================= */
.agf-page h2,
.agf-page h3,
.agf-page h4 {
  font-family: 'Yantramanav', sans-serif !important;
  font-weight: 600;
  line-height: 1.2;
  color: #19232B;
}
.agf-page h2.s1 {
  font-size: 28px;
  margin: 48px 0 16px;
  padding-bottom: 8px;
  border-bottom: 3px solid var(--g);
}
.agf-page h3.s2 {
  color: var(--g) !important;
  font-size: 22px;
  margin: 32px 0 10px;
}
.agf-page p {
  color: #757F95;
  line-height: 1.8;
  text-align: justify;
}
.agf-page .note {
  color: var(--m);
  font-style: italic;
  font-size: 15px;
}

/* listes a puces */
.agf-page ul.gl { list-style: none; padding: 0; margin: 12px 0; }
.agf-page ul.gl li { position: relative; padding: 5px 0 5px 28px; }
.agf-page ul.gl li::before {
  content: '\25A0';
  position: absolute;
  left: 0;
  top: 9px;
  color: var(--g);
  font-size: 12px;
}

/* images */
.agf-page .fig { margin: 26px 0; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 26px rgba(0,0,0,.15); }
.agf-page .fig img { width: 100%; display: block; max-height: 460px; object-fit: cover; }
.agf-page .fig.missing {
  border: 2px dashed var(--g);
  background: #fafafa;
  padding: 70px 20px;
  text-align: center;
  color: var(--m);
  font-size: 14px;
  box-shadow: none;
}
.agf-page .fig.missing img { display: none; }
.agf-page .fig.missing::after { content: attr(data-name); font-weight: 700; }

/* tableaux */
.agf-page .tw { overflow-x: auto; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,.09); }
.agf-page table { border-collapse: collapse; width: 100%; font-size: 15px; background: #fff; }
.agf-page th { background: var(--g); color: #fff; text-align: left; padding: 12px 14px; }
.agf-page td { padding: 10px 14px; border-bottom: 1px solid #e3e3e3; vertical-align: top; }
.agf-page tr:nth-child(even) td { background: var(--l); }
.agf-page tr:hover td { background: var(--gl); }
.agf-page td:first-child { font-weight: 700; }

/* chaines / etapes */
.agf-page .flow {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  align-items: center;
  margin: 24px 0;
  padding: 18px;
  background: var(--l);
  border-left: 6px solid var(--g);
  border-radius: 8px;
}
.agf-page .flow b { background: #fff; border: 1px solid var(--g); padding: 6px 13px; border-radius: 20px; font-size: 14px; transition: .2s; }
.agf-page .flow b:hover { background: var(--g); color: #fff; transform: translateY(-2px); }
.agf-page .flow i { color: var(--g); font-style: normal; font-weight: 800; }

/* encadre */
.agf-page .call { margin: 22px 0; padding: 18px 22px; background: var(--l); border-left: 6px solid var(--g); border-radius: 8px; font-weight: 700; }

/* grilles de cartes */
.agf-page .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; margin: 20px 0; }
.agf-page .card {
  background: #fff;
  border: 1px solid #e3e3e3;
  border-top: 4px solid var(--g);
  border-radius: 10px;
  padding: 18px;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
  transition: transform .25s, box-shadow .25s;
}
.agf-page .card:hover { transform: translateY(-6px); box-shadow: 0 12px 26px rgba(0,0,0,.15); }
.agf-page .card .n { color: var(--g); font-weight: 800; font-size: 26px; }
.agf-page .card .pimg { width: 100%; height: 160px; margin-bottom: 12px; }
.agf-page .card .pimg img { width: 100%; height: 100%; object-fit: cover; max-width: none; max-height: none; }
.agf-page a.card { text-decoration: none; color: var(--d); font-weight: 700; font-size: 19px; }
.agf-page a.card:hover { background: var(--g); color: #fff; }

/* statistiques */
.agf-page .stat {
  background: #fff;
  border-bottom: 4px solid var(--g);
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(0,0,0,.13);
}
.agf-page .stat b { display: block; font-size: 42px; color: var(--g); line-height: 1.1; }
.agf-page .stat span { font-size: 14px; color: var(--m); }

/* vignettes photo / logo */
.agf-page .logo {
  background: #fff;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid transparent;
  overflow: hidden;
}
.agf-page .logo img { max-width: 88%; max-height: 88%; }
.agf-page .logo.missing { border: 2px dashed var(--g); background: #fafafa; font-size: 12px; color: var(--m); text-align: center; padding: 10px; }
.agf-page .logo.missing img { display: none; }
.agf-page .logo.missing::after { content: attr(data-name); font-weight: 700; }

/* apparition au scroll */
.agf-page .reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s, transform .7s; }
.agf-page .reveal.in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
  .agf-page .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
}

/* tableaux responsives */
@media (max-width: 720px) {
  .agf-page table.rs thead { display: none; }
  .agf-page table.rs tr { display: block; border-bottom: 3px solid var(--g); margin-bottom: 10px; }
  .agf-page table.rs td { display: flex; gap: 10px; border: 0; padding: 6px 14px; }
  .agf-page table.rs td::before { content: attr(data-l); font-weight: 700; color: var(--g); flex: 0 0 105px; }
}
</style><style>.reveal{opacity:1!important;transform:none!important}</style>

<main class="agf-main">

<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">Sustainability &amp; Impact</h1>

      <p class="ud-hero-slogan">ESG IMPLEMENTATION</p>

      <a href="<?= base_url('about') ?>" class="ud-hero-btn"><i class="fas fa-arrow-left-long"></i> Back to About</a>
    </div>
  </div>
</div>

<div class="agf-page">
<figure class="fig reveal" data-name="Sustainability Community"><img loading="lazy" src="assets/images/sustainability-community.jpg" alt="Community and workforce" onerror="this.parentNode.classList.add('missing')"></figure><h2 class="s1 reveal">ENVIRONMENTAL, SOCIAL &amp; GOVERNANCE IMPLEMENTATION</h2>
<div class="tw reveal"><table class="rs"><thead><tr><th>Area</th><th>Specific Implementation</th></tr>
</thead>
<tbody><tr><td data-l="Area">Environmental Management</td><td data-l="Specific Implementation">Permitting, controlled waste handling, emissions management and resource efficiency</td></tr>
<tr><td data-l="Area">Water &amp; Energy</td><td data-l="Specific Implementation">Process-water control, energy efficiency, monitoring and utility optimization</td></tr>
<tr><td data-l="Area">Occupational Health &amp; Safety</td><td data-l="Specific Implementation">PPE, process-safety controls, containment, emergency response and training</td></tr>
<tr><td data-l="Area">Biological Safety</td><td data-l="Specific Implementation">Biosecurity, contained biological operations and controlled waste decontamination</td></tr>
<tr><td data-l="Area">Agricultural Sustainability</td><td data-l="Specific Implementation">Soil regeneration, irrigation management and traceable inputs</td></tr>
<tr><td data-l="Area">Supply-Chain Integrity</td><td data-l="Specific Implementation">Supplier qualification, raw-material traceability and controlled procurement</td></tr>
<tr><td data-l="Area">Quality &amp; Compliance</td><td data-l="Specific Implementation">GMP/ISO-aligned procedures, validation, CAPA, document control and audit readiness</td></tr>
<tr><td data-l="Area">Governance</td><td data-l="Specific Implementation">Segregated approvals, financial/procurement controls, audit trails and reporting</td></tr>
<tr><td data-l="Area">Data &amp; Cybersecurity</td><td data-l="Specific Implementation">Role-based access, protected data, backups and audit trails</td></tr>
<tr><td data-l="Area">Community &amp; Workforce</td><td data-l="Specific Implementation">Local employment, skills development, worker welfare, gender parity and community engagement</td></tr>
</tbody>
</table></div><h2 class="s1 reveal">STRATEGIC 10-YEAR OBJECTIVES - 2026-2035</h2>
<p class="reveal">The following represent Project objectives and financial-model projections, not historical performance:</p>
<ul class="gl"><li class="reveal">USD 100 million+ annual export earnings target;</li>
<li class="reveal">USD 196.9 million cumulative projected net profit;</li>
<li class="reveal">50,000+ employment opportunities target;</li>
<li class="reveal">5,000+ contracted farmers target;</li>
<li class="reveal">2,000+ ha agricultural expansion target;</li>
<li class="reveal">Scalable industrial manufacturing capacity; and</li>
<li class="reveal">Expanded regional and international export capability.</li>
</ul><p class="reveal">Achievement of these objectives is dependent on financing, construction, commissioning, qualification, regulatory approvals, market conditions, operating performance and successful commercial execution.</p>

<h2 class="s1 reveal">PRINCIPAL RISKS &amp; MITIGATION FRAMEWORK</h2>
<div class="tw reveal"><table class="rs"><thead><tr><th>Principal Risk</th><th>Principal Mitigation</th></tr>
</thead><tbody><tr><td data-l="Principal Risk">Agricultural &amp; Climate</td><td data-l="Principal Mitigation">Irrigation, diversified production and agronomic monitoring</td></tr>
<tr><td data-l="Principal Risk">Feedstock Supply</td><td data-l="Principal Mitigation">Secured 97-ha base, phased expansion, contract farming and qualified suppliers</td></tr>
<tr><td data-l="Principal Risk">Scientific &amp; Quality</td><td data-l="Principal Mitigation">Analytical control, validated methods, traceability and GMP/ISO-aligned systems</td></tr>
<tr><td data-l="Principal Risk">Market &amp; Demand</td><td data-l="Principal Mitigation">Documented commercial channels and export diversification</td></tr>
<tr><td data-l="Principal Risk">EPC &amp; Technology</td><td data-l="Principal Mitigation">Qualified OEM/EPC counterparties, milestone controls, FAT/SAT, commissioning and warranties</td></tr>
<tr><td data-l="Principal Risk">Regulatory</td><td data-l="Principal Mitigation">Product-specific qualification, validation, certification and applicable approvals</td></tr>
<tr><td data-l="Principal Risk">Financial &amp; Liquidity</td><td data-l="Principal Mitigation">Controlled accounts, DSRA, SBLC, milestone drawdowns and financial covenants</td></tr>
<tr><td data-l="Principal Risk">Foreign Exchange</td><td data-l="Principal Mitigation">Export receipts and treasury/currency-risk management</td></tr>
<tr><td data-l="Principal Risk">Human Capital</td><td data-l="Principal Mitigation">Specialist recruitment, training, retention and gender-parity measures</td></tr>
<tr><td data-l="Principal Risk">Livestock &amp; Bioresources</td><td data-l="Principal Mitigation">Veterinary oversight, biosecurity and controlled resource recovery</td></tr>
<tr><td data-l="Principal Risk">Cybersecurity</td><td data-l="Principal Mitigation">Segmented networks, access controls, monitoring, backup and data-integrity controls</td></tr>
</tbody></table></div>
</div>

</main>

<noscript><script>
(function () {
  var els = document.querySelectorAll('.reveal:not(.in)');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.06 });
  els.forEach(function (el) { io.observe(el); });
})();
</script><style>.reveal{opacity:1!important;transform:none!important}</style></noscript>

<script>
(function () {
  var els = document.querySelectorAll('.reveal:not(.in)');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.06 });
  els.forEach(function (el) { io.observe(el); });
})();
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>