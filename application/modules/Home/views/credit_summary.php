<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
.ud-hero{position:relative;background-size:cover!important;background-position:center!important;background-repeat:no-repeat!important;padding:140px 0 80px;display:flex;align-items:center;justify-content:center;text-align:center;z-index:1}
.ud-hero::before{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(11,28,57,.75);z-index:-1}
.ud-hero-content{position:relative;z-index:1}
.ud-hero-title{font-family:'Yantramanav',sans-serif;font-size:56px!important;font-weight:800!important;color:#fff!important;margin-bottom:10px;line-height:1.1;text-shadow:0 2px 10px rgba(0,0,0,.3)}
.ud-hero-title,.ud-hero-title span,.ud-hero-content h1,.ud-hero-content h1 span,div.ud-hero .ud-hero-title,div.ud-hero .ud-hero-content h1{color:#fff!important}
h1.ud-hero-title{color:#fff!important}
.ud-hero-slogan{color:rgba(255,255,255,.85)!important;font-size:18px;font-style:italic;margin-bottom:25px}
.ud-hero-btn{display:inline-flex;align-items:center;gap:8px;background:#dcbb07;color:#fff!important;padding:14px 28px;border-radius:50px 50px 50px 0;font-weight:600;font-size:14px;text-decoration:none;transition:all .4s ease;border:none;cursor:pointer;margin:0 6px 8px}
.ud-hero-btn:hover{background:#116E63;color:#fff!important;transform:translateY(-3px);box-shadow:0 10px 25px rgba(17,110,99,.4)}
.ud-hero-btn.alt{background:#116E63}
.ud-hero-btn.alt:hover{background:#dcbb07}
.agf-main{font-family:'Roboto',sans-serif!important;padding-top:0!important;margin-top:0!important}
.agf-main h1,.agf-main h2,.agf-main h3,.agf-main h4{font-family:'Yantramanav',sans-serif!important;color:#19232B!important;font-weight:600;line-height:1.2}
.agf-main p{color:#757F95;line-height:1.8}
.cs-tabs{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;max-width:860px;margin:36px auto 0;padding:0 20px}
.cs-tabs a{display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;text-decoration:none;padding:11px 22px;border-radius:50px 50px 50px 0;border:2px solid #116E63;color:#116E63;background:#fff;transition:all .3s ease}
.cs-tabs a.on{background:#116E63;color:#fff}
.cs-tabs a:hover{background:#dcbb07;border-color:#dcbb07;color:#fff}
.cs-cta{max-width:860px;margin:0 auto;padding:10px 20px 70px;text-align:center}
.cs-cta a{display:inline-flex;align-items:center;gap:8px;background:#dcbb07;color:#fff!important;padding:15px 32px;border-radius:50px 50px 50px 0;font-weight:700;font-size:15px;text-decoration:none;transition:all .4s ease}
.cs-cta a:hover{background:#116E63;transform:translateY(-3px);box-shadow:0 10px 25px rgba(17,110,99,.4)}

.cs-wrap{max-width:860px;margin:0 auto;padding:40px 20px 10px}
.cs-wrap h2{font-size:30px;font-weight:800;color:#19232B!important;margin:46px 0 14px;padding-bottom:8px;border-bottom:3px solid #B8902F}
.cs-wrap h2:first-child{margin-top:0}
.cs-wrap h3{font-size:20px;font-weight:700;color:#B8902F!important;margin:28px 0 10px}
.cs-wrap p{color:#757F95;line-height:1.8;text-align:justify;margin-bottom:14px}
.cs-wrap ul{list-style:none;padding:0;margin:12px 0 18px}
.cs-wrap ul li{position:relative;padding:5px 0 5px 28px;color:#757F95;line-height:1.7}
.cs-wrap ul li::before{content:"\25A0";position:absolute;left:0;top:9px;color:#B8902F;font-size:12px}
.cs-tbl{overflow-x:auto;margin:20px 0 24px;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,.09)}
.cs-tbl table{border-collapse:collapse;width:100%;font-size:15px;background:#fff}
.cs-tbl th{background:#B8902F;color:#fff;text-align:left;padding:12px 14px}
.cs-tbl td{padding:10px 14px;border-bottom:1px solid #e3e3e3;vertical-align:top;color:#757F95}
.cs-tbl tr:nth-child(even) td{background:#F2F2F2}
.cs-tbl tr:hover td{background:#F3E9CF}
.cs-tbl td:first-child{font-weight:700;color:#19232B}
.cs-tbl tr.total td{background:#19232B;color:#fff;font-weight:700}
.cs-tbl tr.total td:first-child{color:#fff}
.cs-num{text-align:right!important;white-space:nowrap}
.cs-seq{display:flex;flex-wrap:wrap;gap:8px;align-items:center;justify-content:center;background:#fff;border:2px solid #B8902F;border-radius:14px;padding:18px 16px;margin:18px 0 24px}
.cs-seq span{font-size:13px;font-weight:700;color:#19232B}
.cs-seq i{color:#B8902F;font-size:12px;font-style:normal}
.cs-note{font-size:13px;color:#94A3B8;font-style:italic;text-align:justify}
@media(max-width:720px){
  .ud-hero-title{font-size:36px!important}
  .cs-wrap{padding:30px 16px 6px}
  .cs-wrap h2{font-size:24px}
  .cs-tbl table{font-size:14px}
  .cs-tbl th,.cs-tbl td{padding:9px 10px}
}
</style>

<section class="ud-hero" style="background-image:url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container ud-hero-content">
    <h1 class="ud-hero-title">Executive Credit Summary</h1>
    <p class="ud-hero-slogan">Senior Secured Development Facility of USD 63,209,692 &mdash; memo sections 2.0 to 2.6 and 14.0</p>
    <a href="<?= base_url() ?>" class="ud-hero-btn"><i class="fas fa-home"></i> Back to Home</a>
    <a href="<?= base_url('investor') ?>" class="ud-hero-btn alt"><i class="fas fa-user-plus"></i> Investor Registration</a>
  </div>
</section>

<div class="cs-tabs">
  <a href="<?= base_url('credit-summary') ?>" class="on"><i class="fas fa-file-invoice-dollar"></i> Executive Credit Summary</a>
  <a href="<?= base_url('investor') ?>"><i class="fas fa-user-plus"></i> Investor Registration</a>
</div>

<main class="agf-main">

<!-- ============ MEMO 2.0 - 2.6 / 14.0 : SENIOR SECURED DEVELOPMENT FINANCING ============ -->
<div class="cs-wrap">

<h2>EXECUTIVE CREDIT SUMMARY</h2>

<p>African Green Farmers Limited requests USD 63,209,692 as a Senior Secured Development Facility for the controlled industrialization of ANINOVA INDUSTRIES and its associated agricultural, SERIQA LABORATORIES, utility, digital and commercialization infrastructure.</p>

<div class="cs-tbl">
<table>
<thead><tr><th>Financing Term</th><th>Proposed Structure</th></tr></thead>
<tbody>
<tr><td>Facility Amount</td><td>USD 63,209,692</td></tr>
<tr><td>Facility Type</td><td>Senior Secured Development Facility</td></tr>
<tr><td>Purpose</td><td>ANINOVA INDUSTRIES industrialization and supporting infrastructure</td></tr>
<tr><td>Proposed Tenor</td><td>10 years</td></tr>
<tr><td>Principal Grace Period</td><td>5 years</td></tr>
<tr><td>Proposed Interest Rate</td><td>8.0% p.a., subject to lender approval</td></tr>
<tr><td>Principal Repayment</td><td>Years 6-10</td></tr>
<tr><td>Repayment Frequency</td><td>Quarterly</td></tr>
<tr><td>Primary Debt Service</td><td>Operating free cash flow from manufacturing and exports</td></tr>
<tr><td>Credit Protection</td><td>Senior security, SBLC, controlled accounts, DSRA, milestone controls and financial covenants</td></tr>
<tr><td>Final Maturity</td><td>End of Year 10</td></tr>
</tbody>
</table>
</div>

<p class="cs-note">Financing terms above are proposed terms and remain subject to lender credit approval and definitive documentation.</p>

<h2>RECONCILED ALLOCATION OF FUNDS</h2>

<p>The following allocation reconciles exactly to the requested USD 63,209,692 and 100% of Project investment cost.</p>

<div class="cs-tbl">
<table>
<thead><tr><th>Category</th><th class="cs-num">Amount (USD)</th><th class="cs-num">%</th></tr></thead>
<tbody>
<tr><td>Engineering, Procurement &amp; Construction (EPC)</td><td class="cs-num">11,377,745</td><td class="cs-num">18%</td></tr>
<tr><td>Industrial Manufacturing Infrastructure</td><td class="cs-num">15,170,326</td><td class="cs-num">24%</td></tr>
<tr><td>Industrial Processing Line Systems</td><td class="cs-num">13,274,035</td><td class="cs-num">21%</td></tr>
<tr><td>SERIQA LABORATORIES &mdash; Research, Quality Assurance &amp; Innovation Infrastructure</td><td class="cs-num">3,792,581</td><td class="cs-num">6%</td></tr>
<tr><td>Agricultural Production Infrastructure</td><td class="cs-num">5,056,775</td><td class="cs-num">8%</td></tr>
<tr><td>Livestock &amp; Bioresource Infrastructure</td><td class="cs-num">2,528,388</td><td class="cs-num">4%</td></tr>
<tr><td>Utilities, Energy &amp; Water Systems</td><td class="cs-num">3,160,485</td><td class="cs-num">5%</td></tr>
<tr><td>Digital Traceability, Automation &amp; ACIDS Systems</td><td class="cs-num">1,264,194</td><td class="cs-num">2%</td></tr>
<tr><td>Project Management, Regulatory Compliance &amp; Certification</td><td class="cs-num">1,264,194</td><td class="cs-num">2%</td></tr>
<tr><td>Initial Working Capital &amp; Commercial Launch</td><td class="cs-num">4,424,678</td><td class="cs-num">7%</td></tr>
<tr><td>Contingency &amp; Implementation Reserve</td><td class="cs-num">1,896,291</td><td class="cs-num">3%</td></tr>
<tr class="total"><td>TOTAL PROJECT INVESTMENT COST</td><td class="cs-num">63,209,692</td><td class="cs-num">100%</td></tr>
</tbody>
</table>
</div>

<h2>CAPITAL DEPLOYMENT &amp; DISBURSEMENT CONTROL</h2>

<p>Project funds will be deployed exclusively against the approved budget through lender-controlled accounts and verified milestone-based drawdowns.</p>

<p>Disbursements will be supported by appropriate documentary evidence and, where required, independent verification of procurement, construction, equipment delivery, installation, integration, commissioning and other approved milestones.</p>

<p>Capital deployment will remain subject to agreed conditions precedent, approved budgets, lender controls and satisfactory evidence of Project progress. Unused, misapplied or unsupported funds will remain subject to lender control in accordance with the definitive financing documentation.</p>

<h2>DEBT REPAYMENT STRUCTURE</h2>

<div class="cs-tbl">
<table>
<thead><tr><th>Period</th><th>Repayment Structure</th></tr></thead>
<tbody>
<tr><td>Years 1-5</td><td>Principal grace; interest servicing at proposed 8.0% p.a.</td></tr>
<tr><td>Years 6-10</td><td>Quarterly principal amortization plus interest on declining principal</td></tr>
<tr><td>End of Year 10</td><td>Full repayment of original USD 63,209,692 principal</td></tr>
</tbody>
</table>
</div>

<p>On an equal-principal basis, annual principal amortization during Years 6-10 would be approximately USD 12.642 million, with interest declining as principal is repaid.</p>

<p>Primary repayment source: operating free cash flow from ANINOVA INDUSTRIES manufacturing and international sales.</p>

<p>Secondary support: approved agricultural, biological-input and other Project revenues.</p>

<p class="cs-note">Definitive repayment mechanics remain subject to lender approval and financing documentation.</p>

<h2>LENDER SECURITY &amp; CREDIT PROTECTION</h2>

<p>Subject to due diligence and definitive legal documentation, the proposed protection package includes:</p>

<ul>
<li>Senior first-ranking security over agreed Project assets and financed equipment;</li>
<li>Controlled Project collection and disbursement accounts;</li>
<li>Debt Service Reserve Account targeted at not less than 12 months of scheduled debt service, subject to lender approval and final sizing;</li>
<li>Standby Letter of Credit as additional bank-backed credit enhancement, subject to acceptable issuing-bank terms and enforceability;</li>
<li>Assignment of material Project contracts, receivables and applicable insurance proceeds;</li>
<li>Milestone-based drawdown controls;</li>
<li>Independent technical, financial and legal due diligence;</li>
<li>FAT/SAT, commissioning and performance-verification controls for major equipment packages;</li>
<li>Financial reporting and lender audit rights;</li>
<li>Debt Service Coverage Ratio and other agreed financial covenants;</li>
<li>Negative pledge and agreed restrictions on additional indebtedness;</li>
<li>Change-of-control and material-disposal restrictions; and</li>
<li>Additional guarantees, undertakings or security where required following lender due diligence.</li>
</ul>

<p>The final security package will be determined through lender legal, financial, technical and asset due diligence.</p>

<h2>FINANCIAL MODEL &amp; DOWNSIDE CONTROL</h2>

<p>The current financial model projects:</p>

<ul>
<li>USD 196.9 million cumulative net profit;</li>
<li>USD 30.65 million NPV;</li>
<li>15.78% IRR;</li>
<li>1.48 profitability index; and</li>
<li>Year 9 discounted payback.</li>
</ul>

<p>These figures are forward-looking financial-model projections, not historical results.</p>

<p>Before financial close, the model is to be subjected to lender-grade sensitivity analysis covering construction cost, implementation delays, production ramp-up, capacity utilization, pricing, raw-material costs, foreign exchange, operating expenditure, interest exposure, working capital and downside Debt Service Coverage Ratio scenarios.</p>

<h2>LENDER CREDIT APPRAISAL REQUEST</h2>

<p>African Green Farmers Limited requests formal credit appraisal and independent technical, financial and legal due diligence for the proposed USD 63,209,692 Senior Secured Development Facility, subject to lender approval, satisfactory security, definitive financing terms and execution of financing documentation.</p>

<h2>LENDER ACTION REQUEST</h2>

<p>African Green Farmers Limited requests formal credit appraisal and independent due diligence for the USD 63,209,692 Senior Secured Development Facility to finance the controlled industrialization of ANINOVA INDUSTRIES and associated agricultural, biological, utility, digital and commercialization infrastructure.</p>

<p>The proposed financing architecture provides a defined sequence:</p>

<div class="cs-seq">
<span>Secured Capital</span><i class="fa-solid fa-arrow-right"></i>
<span>Controlled Deployment</span><i class="fa-solid fa-arrow-right"></i>
<span>Verified Project Execution</span><i class="fa-solid fa-arrow-right"></i>
<span>Commissioning &amp; Qualification</span><i class="fa-solid fa-arrow-right"></i>
<span>Commercial Launch</span><i class="fa-solid fa-arrow-right"></i>
<span>Operating Cash Flow</span><i class="fa-solid fa-arrow-right"></i>
<span>Scheduled Debt Repayment</span>
</div>

<p>African Green Farmers Limited is prepared to provide the corporate, land, investment, technical, EPC, procurement, commercial, regulatory and financial documentation required for independent lender due diligence and definitive credit consideration.</p>

</div>
<!-- ============ FIN MEMO ============ -->

<div class="cs-cta">
  <a href="<?= base_url('investor') ?>"><i class="fas fa-user-plus"></i> Continue to Investor Registration</a>
</div>

</main>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
