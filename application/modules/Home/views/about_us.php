<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* variables de la charte (ex-CSS statique supprim&eacute;) */
:root { --g: #B8902F; --d: #3C3C3C; --m: #7A7A7A; --l: #F2F2F2; --gl: #F3E9CF; }

/* =========================================================
   HERO - bandeau pleine largeur (image + voile sombre)
   ========================================================= */
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

/* =========================================================
   MISE EN PAGE
   ========================================================= */
.agf-main {
  font-family: 'Roboto', sans-serif !important;
  padding-top: 0 !important;
  margin-top: 0 !important;
}
.agf-page {
  --g: #B8902F;
  --m: #7A7A7A;
  --l: #F2F2F2;
  --gl: #F3E9CF;
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

/* puces */
.agf-page ul.gl { list-style: none; padding: 0; margin: 12px 0; }
.agf-page ul.gl li {
  position: relative;
  padding: 5px 0 5px 28px;
}
.agf-page ul.gl li::before {
  content: '\25A0';
  position: absolute;
  left: 0;
  color: var(--g);
  font-size: 12px;
  top: 9px;
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
.agf-page td:first-child { font-weight: 700; }
.agf-page tr:hover td { background: var(--gl); }

/* apparition au scroll */
.agf-page .reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s, transform .7s; }
.agf-page .reveal.in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
  .agf-page .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
}

/* responsive */
@media (max-width: 720px) {
  .agf-page table.rs thead { display: none; }
  .agf-page table.rs tr { display: block; border-bottom: 3px solid var(--g); margin-bottom: 10px; }
  .agf-page table.rs td { display: flex; gap: 10px; border: 0; padding: 6px 14px; }
  .agf-page table.rs td::before {
    content: attr(data-l);
    font-weight: 700;
    color: var(--g);
    flex: 0 0 105px;
  }
}
</style><style>
/* variables de la charte (ex-CSS statique supprime) */
:root { --g: #B8902F; --d: #3C3C3C; --m: #7A7A7A; --l: #F2F2F2; --gl: #F3E9CF; }

/* =========================================================
   HERO - bandeau pleine largeur (image + voile sombre)
   ========================================================= */
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

/* =========================================================
   MISE EN PAGE
   ========================================================= */
.agf-main {
  font-family: 'Roboto', sans-serif !important;
  padding-top: 0 !important;
  margin-top: 0 !important;
}
.agf-page {
  --g: #B8902F;
  --m: #7A7A7A;
  --l: #F2F2F2;
  --gl: #F3E9CF;
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

/* puces */
.agf-page ul.gl { list-style: none; padding: 0; margin: 12px 0; }
.agf-page ul.gl li {
  position: relative;
  padding: 5px 0 5px 28px;
}
.agf-page ul.gl li::before {
  content: '\25A0';
  position: absolute;
  left: 0;
  color: var(--g);
  font-size: 12px;
  top: 9px;
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
.agf-page td:first-child { font-weight: 700; }
.agf-page tr:hover td { background: var(--gl); }

/* apparition au scroll */
.agf-page .reveal { opacity: 0; transform: translateY(22px); transition: opacity .7s, transform .7s; }
.agf-page .reveal.in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
  .agf-page .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
}

/* responsive */
@media (max-width: 720px) {
  .agf-page table.rs thead { display: none; }
  .agf-page table.rs tr { display: block; border-bottom: 3px solid var(--g); margin-bottom: 10px; }
  .agf-page table.rs td { display: flex; gap: 10px; border: 0; padding: 6px 14px; }
  .agf-page table.rs td::before {
    content: attr(data-l);
    font-weight: 700;
    color: var(--g);
    flex: 0 0 105px;
  }
}
</style><script>
(function () {
  var els = document.querySelectorAll('.agf-page .reveal');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (x) {
      if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); }
    });
  }, { threshold: 0.06 });
  els.forEach(function (el) { io.observe(el); });
})();
</script>

<main class="agf-main">

<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">A.G.F Overview</h1>
      <p class="ud-hero-slogan">"History, vision, mission and institutional foundations of African Green Farmers Limited."</p>
      <a href="<?= base_url() ?>" class="ud-hero-btn"><i class="fas fa-arrow-left-long"></i> Back to Home</a>
    </div>
  </div>
</div>

<div class="agf-page">
<figure class="fig reveal" data-name="African Green Farmers Limited"><img loading="lazy" src="<?= base_url('attachments/Parametres/slide_helo.jpg') ?>" alt="African Green Farmers Limited" onerror="this.parentNode.classList.add('missing')"></figure>

<h2 class="s1 reveal">EXECUTIVE SUMMARY</h2>

<p class="reveal">African Green Farmers Limited, incorporated on 12 March 2025, is a Zambian agro-industrial and advanced biomanufacturing enterprise developing an integrated resource-to-product business model. The Company holds ZDA Investment Licence No. ZDA/59004/10/2025, five-year Ministry of Finance investment incentives under Ref. No. ZDA/DG/DUTY dated 22 January 2026, and 97 ha of secured agricultural land in Chibombo District. Its confirmed foundation includes an operational AFOOPROF pilot food-processing unit, 12 developed flagship products, five ZBS-certified products within applicable scope, defined ANINOVA INDUSTRIES manufacturing architecture and documented commercial arrangements.</p>

<p class="reveal">A.G.F. seeks USD 63,209,692 in Senior Secured Development Financing to establish ANINOVA INDUSTRIES as its core industrial manufacturing operation, supported by agricultural, biological, scientific, utility, digital and commercialization infrastructure. The planned manufacturing facility will provide controlled, hygienic and scalable production for tiered standardized botanical and natural extracts, purified ingredients, nutraceuticals, superfood supplements, clean-label and fortified organic functional foods and beverages, phytomedicines, Improved Traditional Medicines (ITMs) and biological agricultural inputs, subject to applicable validation, registration and market requirements. The Project is designed to advance sustainable socio-economic development and gender parity, contribute to the fight against chronic malnutrition and dietary imbalance, and alleviate selected dietary- and lifestyle-related burdens through scientifically characterized, standardized botanical and natural bioactive compounds, while enabling internationally scalable commercialization of African organic products across the United States of America, Canada and Europe, underpinned by current and future high-volume off-take agreements.</p>

<p class="reveal">The proposed financing comprises a 10-year tenor with a 5-year principal grace period, followed by quarterly principal amortization during Years 6 to 10, with proposed lender protections including senior security, controlled accounts, milestone-based drawdowns, DSRA, SBLC credit enhancement, assigned contracts and receivables, insurance assignments, financial covenants and independent due diligence. The Project is presently transitioning from its confirmed pilot-stage foundation toward industrial implementation; construction, equipment procurement, commissioning, qualification, regulatory approvals, commercial manufacturing and export operations remain implementation-dependent. The requested facility is therefore intended to provide controlled capital deployment through industrialization and commercial ramp-up, with debt repayment principally from operating free cash flow generated by manufacturing and international sales.</p>

<h2 class="s1 reveal">COMPANY PROFILE</h2>

<h3 class="s2 reveal">Confirmed Corporate, Investment &amp; Project Status</h3>

<div class="tw reveal">
<table class="rs">
<thead>
<tr><th>Item</th><th>Confirmed Status</th></tr>
</thead>
<tbody>
<tr><td data-l="Item">Incorporation</td><td data-l="Confirmed Status">Confirmed - 12 March 2025</td></tr>
<tr><td data-l="Item">ZDA Investment Licence</td><td data-l="Confirmed Status">ZDA/59004/10/2025</td></tr>
<tr><td data-l="Item">Ministry of Finance Investment Incentives</td><td data-l="Confirmed Status">Ref. ZDA/DG/DUTY, 22 January 2026</td></tr>
<tr><td data-l="Item">Agricultural Land</td><td data-l="Confirmed Status">97 ha secured in Chibombo District</td></tr>
<tr><td data-l="Item">AFOOPROF Pilot Food Processing Unit</td><td data-l="Confirmed Status">Operational</td></tr>
<tr><td data-l="Item">Initial Flagship Portfolio</td><td data-l="Confirmed Status">12 products developed</td></tr>
<tr><td data-l="Item">ZBS Certification</td><td data-l="Confirmed Status">Five products certified within applicable scope</td></tr>
<tr><td data-l="Item">International Commercial Arrangements</td><td data-l="Confirmed Status">Documented off-take/distribution arrangements</td></tr>
<tr><td data-l="Item">ANINOVA INDUSTRIES Architecture</td><td data-l="Confirmed Status">Defined</td></tr>
<tr><td data-l="Item">Industrial Equipment &amp; EPC Specifications</td><td data-l="Confirmed Status">Defined</td></tr>
</tbody>
</table>
</div>

<h3 class="s2 reveal">Executive Leadership &amp; Core Responsibilities</h3>

<div class="tw reveal">
<table class="rs">
<thead>
<tr><th>No.</th><th>Position</th><th>Qualification</th><th>Key Roles</th></tr>
</thead>
<tbody>
<tr><td data-l="No.">1</td><td data-l="Position">Board of Directors</td><td data-l="Qualification">Corporate Governance, Investment Oversight &amp; Strategic Stewardship</td><td data-l="Key Roles">Fiduciary governance, strategic authorization, investment oversight and executive accountability</td></tr>
<tr><td data-l="No.">2</td><td data-l="Position">Chief Executive Officer (CEO)</td><td data-l="Qualification">PhD in Business Administration</td><td data-l="Key Roles">Corporate leadership, strategy, capital formation, investor/lender relations and overall execution</td></tr>
<tr><td data-l="No.">3</td><td data-l="Position">Managing Director (MD)</td><td data-l="Qualification">PhD in Biochemical Engineering</td><td data-l="Key Roles">Enterprise operations, industrial strategy, manufacturing execution and performance delivery</td></tr>
<tr><td data-l="No.">4</td><td data-l="Position">Chief Operations &amp; Industrialization Officer (COIO)</td><td data-l="Qualification">PhD in Bioprocess Engineering</td><td data-l="Key Roles">Industrial scale-up, plant operations, process integration and commissioning</td></tr>
<tr><td data-l="No.">5</td><td data-l="Position">Chief Scientific &amp; Innovation Officer (CSIO)</td><td data-l="Qualification">PhD in Natural Products Chemistry</td><td data-l="Key Roles">Natural-product discovery, characterization, innovation and technology development</td></tr>
<tr><td data-l="No.">6</td><td data-l="Position">Chief Financial &amp; Administration Officer (CFAO)</td><td data-l="Qualification">Master's in Corporate Finance</td><td data-l="Key Roles">Treasury, financial planning, budget control and lender reporting</td></tr>
<tr><td data-l="No.">7</td><td data-l="Position">Chief Commercial &amp; Market Development Officer (CCMDO)</td><td data-l="Qualification">Master's in International Marketing</td><td data-l="Key Roles">Market development, off-take, distribution, exports and commercial growth</td></tr>
<tr><td data-l="No.">8</td><td data-l="Position">Chief AI, Automation &amp; Advanced Technology Officer (CAIAATO)</td><td data-l="Qualification">PhD in Industrial Artificial Intelligence</td><td data-l="Key Roles">AI, automation, robotics, digital integration and data intelligence</td></tr>
<tr><td data-l="No.">9</td><td data-l="Position">General Counsel &amp; Corporate Secretary (GCCS)</td><td data-l="Qualification">Master's in Corporate &amp; Commercial Law</td><td data-l="Key Roles">Governance, contracts, financing documentation and regulatory compliance</td></tr>
<tr><td data-l="No.">10</td><td data-l="Position">Director of Quality Assurance (DQA)</td><td data-l="Qualification">Master's in Pharmaceutical Quality Assurance</td><td data-l="Key Roles">Quality systems, GMP/ISO implementation, validation and audit readiness</td></tr>
<tr><td data-l="No.">11</td><td data-l="Position">Director of Quality Control &amp; Analytical Sciences (DQCAS)</td><td data-l="Qualification">PhD in Analytical Chemistry</td><td data-l="Key Roles">Analytical methods, testing, specifications and product-release assurance</td></tr>
<tr><td data-l="No.">12</td><td data-l="Position">Director of Pharmaceutical Innovation, Technology Transfer &amp; Industrial Product Development (DPITTIP)</td><td data-l="Qualification">PhD in Pharmaceutical Sciences</td><td data-l="Key Roles">Formulation, technology transfer, scale-up and dosage-form development</td></tr>
<tr><td data-l="No.">13</td><td data-l="Position">Head of Research &amp; Strategic Partnerships (HRSP)</td><td data-l="Qualification">PhD in Industrial Biotechnology</td><td data-l="Key Roles">Applied research, technology partnerships and institutional collaboration</td></tr>
<tr><td data-l="No.">14</td><td data-l="Position">Director of Advanced Scientific Research, Formulation &amp; Biological Industrial Development (DASRFBID)</td><td data-l="Qualification">PhD in Industrial Biotechnology</td><td data-l="Key Roles">Biological process development, formulation and industrial biotechnology</td></tr>
<tr><td data-l="No.">15</td><td data-l="Position">Director of Livestock, Agriculture &amp; Health-Biosecurity Systems (DLAHBS)</td><td data-l="Qualification">Master's in Agricultural Biotechnology</td><td data-l="Key Roles">Agriculture, livestock, feedstock security, bioresource recovery and biosecurity</td></tr>
</tbody>
</table>
</div>

<h2 class="s1 reveal">STRATEGIC INVESTMENT PROPOSITION</h2>

<p class="reveal">The Project integrates:</p>

<ul class="gl">
<li class="reveal">Secured agricultural resources with scalable expansion;</li>
<li class="reveal">Vertically integrated resource-to-product processing;</li>
<li class="reveal">Advanced manufacturing biotechnology;</li>
<li class="reveal">SERIQA LABORATORIES scientific and analytical assurance;</li>
<li class="reveal">Tiered extract purification and standardization;</li>
<li class="reveal">Controlled formulation and dosage-form development;</li>
<li class="reveal">Documented commercial channels;</li>
<li class="reveal">Export-oriented value addition;</li>
<li class="reveal">Scalable industrial infrastructure; and</li>
<li class="reveal">Structured senior-secured financing with controlled deployment and lender protections.</li>
</ul>

<p class="reveal">The integrated model is designed to advance sustainable socio-economic development and gender parity, contribute to the fight against chronic malnutrition and dietary imbalance, alleviate selected dietary- and lifestyle-related burdens through scientifically characterized standardized botanical/natural bioactive compounds, and enable internationally scalable commercialization of African organic products across the United States of America, Canada and Europe, supported by current and future high-volume off-take agreements.</p>

<h3 class="s2 reveal">Organic Resource &amp; Vertical-Integration Advantage</h3>

<p class="reveal">Integration of controlled agricultural production, livestock bioresources, scientific characterization, industrial processing, formulation and commercialization provides a traceable resource-to-product structure.</p>

<h3 class="s2 reveal">Industrial Value-Addition Advantage</h3>

<p class="reveal">Transformation of agricultural and biological resources into cost-effective and affordable standardized botanical extracts with tiered purity, up to 99.9% where applicable and validated, nutraceuticals, superfood supplements, clean-label and fortified functional organic foods and beverages, and biological agricultural products, including biofertilizers and bioprotectants.</p>

<h3 class="s2 reveal">Technology Advantage</h3>

<p class="reveal">Deployment of integrated, automated and pharmaceutical-grade manufacturing technologies for extraction, biotransformation, purification, formulation and finished-product manufacturing.</p>

<h3 class="s2 reveal">Vertical-Integration Advantage</h3>

<p class="reveal">Integration of agriculture, livestock bioresources, scientific characterization, advanced manufacturing, formulation, quality systems and international commercialization.</p>

<h2 class="s1 reveal">PROJECT READINESS &amp; IMPLEMENTATION STATUS</h2>

<div class="tw reveal">
<table class="rs">
<thead>
<tr><th>Project Element</th><th>Status</th></tr>
</thead>
<tbody>
<tr><td data-l="Project Element">Corporate incorporation</td><td data-l="Status">Confirmed - 12 March 2025</td></tr>
<tr><td data-l="Project Element">ZDA Investment Licence</td><td data-l="Status">Confirmed</td></tr>
<tr><td data-l="Project Element">Ministry of Finance incentives</td><td data-l="Status">Confirmed</td></tr>
<tr><td data-l="Project Element">97 ha agricultural land</td><td data-l="Status">Secured</td></tr>
<tr><td data-l="Project Element">AFOOPROF pilot unit</td><td data-l="Status">Operational</td></tr>
<tr><td data-l="Project Element">12-product portfolio</td><td data-l="Status">Developed</td></tr>
<tr><td data-l="Project Element">Five ZBS-certified products</td><td data-l="Status">Confirmed within applicable scope</td></tr>
<tr><td data-l="Project Element">Commercial arrangements</td><td data-l="Status">Documented</td></tr>
<tr><td data-l="Project Element">ANINOVA INDUSTRIES architecture</td><td data-l="Status">Defined</td></tr>
<tr><td data-l="Project Element">EPC/equipment specifications</td><td data-l="Status">Defined</td></tr>
<tr><td data-l="Project Element">Industrial facility construction</td><td data-l="Status">Planned</td></tr>
<tr><td data-l="Project Element">Industrial equipment procurement</td><td data-l="Status">Planned</td></tr>
<tr><td data-l="Project Element">Full-scale commissioning</td><td data-l="Status">Planned</td></tr>
<tr><td data-l="Project Element">Product-specific regulatory approvals</td><td data-l="Status">Implementation-dependent</td></tr>
<tr><td data-l="Project Element">Commercial-scale manufacturing</td><td data-l="Status">Implementation-dependent</td></tr>
<tr><td data-l="Project Element">2,000+ ha expansion</td><td data-l="Status">Planned</td></tr>
<tr><td data-l="Project Element">5,000+ farmer network</td><td data-l="Status">Planned</td></tr>
<tr><td data-l="Project Element">Quotations</td><td data-l="Status">Available</td></tr>
</tbody>
</table>
</div>

<p class="reveal"><strong>Status principle:</strong> Only completed or independently supportable achievements are presented as confirmed. Planned, implementation-dependent and projected outcomes are expressly identified as such.</p>

<h2 class="s1 reveal">PROJECT IMPLEMENTATION TIMELINE</h2>

<p class="reveal">The overall Project implementation programme is 60 months. Within this programme, the ANINOVA INDUSTRIES facility construction, installation, integration and initial commissioning are targeted for six months through accelerated continuous day-and-night multi-shift EPC execution.</p>

<div class="tw reveal">
<table class="rs">
<thead>
<tr><th>No.</th><th>Project Component</th><th>Critical Activities</th><th>Timeline</th></tr>
</thead>
<tbody>
<tr><td data-l="No.">1</td><td data-l="Project Component">Financial Close &amp; Mobilization</td><td data-l="Critical Activities">Financing close, controlled accounts, governance and mobilization</td><td data-l="Timeline">Months 1-2</td></tr>
<tr><td data-l="No.">2</td><td data-l="Project Component">ANINOVA INDUSTRIES Facility EPC</td><td data-l="Critical Activities">Construction, controlled environments, utilities and cleanrooms</td><td data-l="Timeline">Months 1-6</td></tr>
<tr><td data-l="No.">3</td><td data-l="Project Component">SERIQA LABORATORIES Development</td><td data-l="Critical Activities">Fit-out, utilities and environmental controls</td><td data-l="Timeline">Months 2-12</td></tr>
<tr><td data-l="No.">4</td><td data-l="Project Component">ANINOVA INDUSTRIES Equipment</td><td data-l="Critical Activities">Procurement, delivery, installation, integration and commissioning</td><td data-l="Timeline">Months 2-15</td></tr>
<tr><td data-l="No.">5</td><td data-l="Project Component">SERIQA LABORATORIES Equipment</td><td data-l="Critical Activities">Procurement, delivery, installation and qualification support</td><td data-l="Timeline">Months 2-15</td></tr>
<tr><td data-l="No.">6</td><td data-l="Project Component">Agricultural Infrastructure</td><td data-l="Critical Activities">Land development, irrigation and feedstock establishment</td><td data-l="Timeline">Months 2-9</td></tr>
<tr><td data-l="No.">7</td><td data-l="Project Component">Livestock &amp; Bioresource Systems</td><td data-l="Critical Activities">Livestock systems, biosecurity and resource recovery</td><td data-l="Timeline">Months 2-10</td></tr>
<tr><td data-l="No.">8</td><td data-l="Project Component">Process Validation &amp; Product Development</td><td data-l="Critical Activities">Characterization, formulation, standardization and validation</td><td data-l="Timeline">Months 10-15</td></tr>
<tr><td data-l="No.">9</td><td data-l="Project Component">Regulatory &amp; Product Certification</td><td data-l="Critical Activities">Testing, dossiers, registrations and applicable approvals</td><td data-l="Timeline">Months 10-24</td></tr>
<tr><td data-l="No.">10</td><td data-l="Project Component">Commercial Launch</td><td data-l="Critical Activities">Manufacturing ramp-up and domestic/off-take fulfilment</td><td data-l="Timeline">Months 15-24</td></tr>
<tr><td data-l="No.">11</td><td data-l="Project Component">Export Commercialization</td><td data-l="Critical Activities">USA/Canada distribution, European development and wider exports</td><td data-l="Timeline">Months 24-48</td></tr>
<tr><td data-l="No.">12</td><td data-l="Project Component">Capacity Expansion &amp; Optimization</td><td data-l="Critical Activities">Portfolio expansion, R&amp;D, automation and export growth</td><td data-l="Timeline">Months 48-60</td></tr>
</tbody>
</table>
</div>

<p class="reveal"><strong>Total Project Implementation Period: 60 Months</strong></p>

<p class="reveal"><strong>ANINOVA INDUSTRIES Facility Construction, Installation, Integration &amp; Initial Commissioning Target: 6 Months through accelerated continuous day-and-night multi-shift EPC execution.</strong></p>

<h2 class="s1 reveal">FOR AND ON BEHALF OF</h2>

<p class="reveal"><strong>AFRICAN GREEN FARMERS LIMITED</strong></p>

<p class="reveal"><strong>HARIMENSHI Alexis</strong><br>
<strong>Chief Executive Officer (CEO)</strong><br>
Republic of Zambia</p>
</div>

</main>

<script>
(function () {
  var els = document.querySelectorAll('.agf-page .reveal');
  if (!els.length) return;
  if (!('IntersectionObserver' in window)) {
    els.forEach(function (el) { el.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (x) {
      if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); }
    });
  }, { threshold: 0.06 });
  els.forEach(function (el) { io.observe(el); });
})();
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>