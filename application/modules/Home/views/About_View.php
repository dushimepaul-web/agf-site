<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<!-- PAGE HERO -->
<section class="agf-page-hero">
  <div class="container">
    <span class="agf-eyebrow agf-eyebrow-gold"><i class="bi bi-building"></i> About A.G.F</span>
    <h1>A Zambian agro-industrial company, fully integrated.</h1>
    <p>History, vision, mission and institutional foundations of African Green Farmers (A.G.F) Limited.</p>
  </div>
</section>

<!-- SECTION 1: WHO WE ARE -->
<section class="agf-section agf-section-white">
  <div class="container">
    <div class="agf-presentation">
      <div class="agf-presentation-text">
        <h2>Who We Are</h2>
        <p>African Green Farmers Limited (A.G.F Limited) is a privately-owned Zambian agro-industrial investment company establishing a fully integrated circular bio-economy enterprise, powered by ACIDS (Advanced Computational Intelligence & Decision Sciences), focused on developing, manufacturing and marketing high-value agricultural, nutritional and health solutions.</p>
        <p>A.G.F Limited is seeking a USD 63 million senior development facility to establish one of Southern Africa's most integrated agro-industrial manufacturing operations.</p>
        <p>The project combines organic agricultural production, industrial manufacturing, organic agricultural inputs, research laboratories, quality assurance systems, logistics infrastructure and marketing networks within a single integrated vertical operating model — designed to maximize value addition, strengthen supply chain security and generate sustainable long-term cash flows.</p>
      </div>
      <div class="agf-fact-grid">
        <div class="agf-fact-card">
          <span class="agf-fact-label">Legal Name</span>
          <span class="agf-fact-value">African Green Farmers (A.G.F) Limited</span>
        </div>
        <div class="agf-fact-card">
          <span class="agf-fact-label">Incorporation Date</span>
          <span class="agf-fact-value">March 12, 2025</span>
        </div>
        <div class="agf-fact-card">
          <span class="agf-fact-label">TPIN</span>
          <span class="agf-fact-value">2003675243</span>
        </div>
        <div class="agf-fact-card">
          <span class="agf-fact-label">Investment License</span>
          <span class="agf-fact-value">ZDA/59004/10/2025</span>
        </div>
        <div class="agf-fact-card">
          <span class="agf-fact-label">Tax Incentives</span>
          <span class="agf-fact-value">5 Years — Ref. ZDA/DG/DUTY</span>
        </div>
        <div class="agf-fact-card">
          <span class="agf-fact-label">SBLC</span>
          <span class="agf-fact-value">In progress via Absa Bank Zambia</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 2: VISION & MISSION -->
<section class="agf-section agf-section-light">
  <div class="container">
    <div class="agf-vm-grid">
      <div class="agf-vm-card">
        <span class="agf-eyebrow"><i class="bi bi-eye"></i> Vision</span>
        <h3>Become a leading African agro-industrial enterprise.</h3>
        <p>Become a leading African agro-industrial enterprise, driving sustainable food manufacturing, regenerative (organic) agriculture, rural industrialization and export-oriented value addition.</p>
      </div>
      <div class="agf-vm-card">
        <span class="agf-eyebrow"><i class="bi bi-bullseye"></i> Mission</span>
        <h3>Transform agricultural resources into high-value products.</h3>
        <p>Develop a scalable agro-industrial ecosystem that transforms agricultural resources into bioactive- and nutrient-rich food and agricultural products through advanced processing technologies and large-scale inclusive rural participation.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 3: OUR VALUES -->
<section class="agf-section agf-section-white">
  <div class="container">
    <div class="agf-section-header">
      <span class="agf-eyebrow"><i class="bi bi-heart"></i> Our Values</span>
      <h2>What guides every decision at A.G.F Limited.</h2>
    </div>
    <div class="agf-values-grid">
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-arrow-down-up"></i></div>
        <h4>Vertical Integration</h4>
        <p>From farm to finished product, complete control of the value chain.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-microscope"></i></div>
        <h4>Scientific Rigor</h4>
        <p>Continuous research and validation through CERIQA Laboratories.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-people"></i></div>
        <h4>Rural Impact</h4>
        <p>Over 5,000 contract farmers integrated into the value chain.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-arrow-repeat"></i></div>
        <h4>Sustainability</h4>
        <p>Regenerative agriculture and circular bio-economy at the core of the model.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section class="agf-cta-band">
  <div class="container">
    <h2>Discover the full project.</h2>
    <p>Integrated food processing and organic fertilizer manufacturing platform.</p>
    <div class="agf-cta-actions">
      <a href="<?= base_url('about') ?>" class="agf-btn agf-btn-gold">Our Project</a>
      <a href="<?= base_url('about') ?>" class="agf-btn agf-btn-outline">Strategic Units</a>
    </div>
  </div>
</section>

<style>
/* PAGE HERO */
.agf-page-hero {
  background: #1a365d;
  color: #fff;
  padding: 60px 0 48px;
}
.agf-page-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(24px, 4vw, 36px);
  color: #fff;
  margin: 8px 0 12px;
}
.agf-page-hero p {
  color: rgba(255,255,255,0.8);
  font-size: 17px;
  max-width: 60ch;
  margin: 0;
}

/* FACTS GRID */
.agf-fact-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1px;
  background: #e2e8f0;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
}
.agf-fact-card {
  background: #fff;
  padding: 20px;
}
.agf-fact-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #64748b;
  margin-bottom: 4px;
}
.agf-fact-value {
  font-size: 15px;
  font-weight: 600;
  color: #1a365d;
}
@media (max-width: 600px) {
  .agf-fact-grid { grid-template-columns: 1fr; }
}

/* VISION & MISSION */
.agf-vm-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}
.agf-vm-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 32px;
}
.agf-vm-card h3 {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  color: #1a365d;
  margin: 8px 0 12px;
}
.agf-vm-card p {
  font-size: 15px;
  color: #64748b;
  line-height: 1.7;
  margin: 0;
}
@media (max-width: 768px) {
  .agf-vm-grid { grid-template-columns: 1fr; }
}

/* VALUES */
.agf-values-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}
.agf-value-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 28px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.agf-value-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.agf-value-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #eff6ff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}
.agf-value-icon i {
  font-size: 24px;
  color: #1e40af;
}
.agf-value-card h4 {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin: 0 0 8px;
}
.agf-value-card p {
  font-size: 14px;
  color: #64748b;
  margin: 0;
  line-height: 1.5;
}
@media (max-width: 992px) {
  .agf-values-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .agf-values-grid { grid-template-columns: 1fr; }
}

/* CTA BAND */
.agf-cta-band {
  background: #1a365d;
  color: #fff;
  padding: 48px 0;
  text-align: center;
}
.agf-cta-band h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(22px, 3vw, 30px);
  color: #fff;
  margin: 0 0 12px;
}
.agf-cta-band p {
  color: rgba(255,255,255,0.8);
  margin: 0 0 28px;
}
</style>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
