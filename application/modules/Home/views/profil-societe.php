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
.agf-value-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #116E63, #19232B); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.agf-value-icon i { font-size: 32px; color: #fff; }
.agf-value-card h4 { font-size: 18px; color: #19232B !important; margin-bottom: 10px; }
.agf-value-card p { font-size: 14px; color: #757F95; margin: 0; }

/* Tech Card */
.agf-tech-card { display: flex; gap: 20px; padding: 25px; background: #fff; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 20px; transition: all 0.3s ease; }
.agf-tech-card:hover { transform: translateX(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.agf-tech-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #116E63, #dcbb07); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
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
.agf-callout { background: linear-gradient(135deg, #116E63, #19232B); color: #fff; padding: 30px; border-radius: 16px; margin-top: 30px; }
.agf-callout p { color: #fff !important; margin: 0; }

@media (max-width: 991px) {
  .agf-sh h2 { font-size: 36px; }
  .agf-vm-grid, .agf-fact-grid { grid-template-columns: 1fr; }
}
</style>

<main class="agf-main">

<!-- HERO -->
<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">Company Profile</h1>
      <p class="ud-hero-slogan">"Résumé exécutif, profil de la société, contexte stratégique, vision et mission"</p>
      <a href="<?= base_url('about') ?>" class="ud-hero-btn">
        <i class="fas fa-arrow-left-long"></i> Back to About
      </a>
    </div>
  </div>
</div>

<!-- SECTION INDEX -->
<section style="padding:60px 0 0;">
  <div class="container">
    <div class="agf-section-index">
      <h4>Sections on this page — 6 total</h4>
      <ol>
        <li><a href="#s1">Résumé exécutif</a></li>
        <li><a href="#s2">Résumé exécutif du crédit</a></li>
        <li><a href="#s3">Profil de la société</a></li>
        <li><a href="#s4">Contexte &amp; justification stratégique</a></li>
        <li><a href="#s5">Énoncé de vision</a></li>
        <li><a href="#s6">Énoncé de mission</a></li>
      </ol>
    </div>
  </div>
</section>

<!-- SECTION 1: RÉSUMÉ EXÉCUTIF -->
<section class="agf-about" id="s1">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Résumé <span>exécutif</span></h2>
    </div>
    <p>African Green Farmers Limited (A.G.F Limited) est une société d'investissement agro-industrielle zambienne à capitaux privés, qui met en place une entreprise de bioéconomie circulaire entièrement intégrée et activée par le système ACIDS, dédiée au développement et à la fabrication, ainsi qu'à la commercialisation domestique, régionale et internationale, de plusieurs gammes de produits : (1) extraits naturels et bioactifs de qualité GMP — de qualité brute à haute pureté, (2) nutraceutiques, (3) compléments alimentaires, (4) aliments et boissons « clean-label », y compris des aliments et boissons fonctionnels et fortifiés — à travers l'installation intégrée ANINOVA INDUSTRIES (Advanced Natural, Integrated Nutraceutical, Organic &amp; Value-Added Agro-Bio Industries).</p>
    <p class="mt-3">Le capital d'amorçage d'ANINOVA INDUSTRIES est une facilité de développement senior sécurisée de USD 63 millions destinée à établir l'une des industries de fabrication agro-industrielle intégrée d'Afrique australe.</p>
    <p class="mt-3">Le projet combine production agricole organique, fabrication industrielle, intrants agricoles biologiques, laboratoires de recherche, systèmes d'assurance qualité, infrastructure logistique et réseaux de commercialisation au sein d'un modèle opérationnel unique et verticalement intégré, conçu pour maximiser la valeur ajoutée, renforcer la sécurité de la chaîne d'approvisionnement et générer des flux de trésorerie durables à long terme, sous un dispositif de sécurité SBLC (Standby Letter of Credit) et DSRA (Debt Service Reserve Account).</p>
    <p class="mt-3">Les produits de santé d'ANINOVA INDUSTRIES incluent une poudre de lait de soja instantanée fortifiée, un mélange de lait végétal fortifié (soja + amandes + noix de coco), des mélanges de jus de fruits enrichis, ainsi que des extraits botaniques standardisés d'une pureté ultra-premium (99 %) issus, pour l'essentiel, de cultures agricoles organiques locales (fruits, légumes, feuilles botaniques, racines, semences/graines, noix, etc.).</p>
    <p class="mt-3">Les technologies de fabrication avancées de l'installation ANINOVA INDUSTRIES incluent :</p>
    <div class="row g-4 mt-3">
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-microscope"></i></div>
          <div class="agf-tech-content">
            <h4>1. Ingénierie métabolique de précision</h4>
            <p>Cellules/tissus végétaux et microbiens, l'élicitation et la fermentation, la biosynthèse, la biotransformation enzymatique et la fabrication.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-snow"></i></div>
          <div class="agf-tech-content">
            <h4>2. Extraction à froid cryogénique</h4>
            <p>Assistée par azote liquide (LN2) et la lyophilisation en gâteaux secs.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-droplet"></i></div>
          <div class="agf-tech-content">
            <h4>3. Séchage sous vide actif/dynamique</h4>
            <p>Pour les émulsions/extraits botaniques riches en lipides à haute viscosité.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-gear"></i></div>
          <div class="agf-tech-content">
            <h4>4. Micronisation cryogénique avancée</h4>
            <p>Assistée par azote liquide.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-flask"></i></div>
          <div class="agf-tech-content">
            <h4>5. Extraction hybride/intégrée</h4>
            <p>Éthanol/acétate d'éthyle et CO2 supercritique.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="bi bi-stars"></i></div>
          <div class="agf-tech-content">
            <h4>6. Purification bioactive multi-étapes</h4>
            <p>À ultra-haute pureté.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-capsules"></i></div>
          <div class="agf-tech-content">
            <h4>7. Formulation et fabrication de dosages GMP</h4>
            <p>Avancée avec fortification de précision.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-chart-line"></i></div>
          <div class="agf-tech-content">
            <h4>8. Fortification de précision</h4>
            <p>En macronutriments, micronutriments et bioactifs.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="agf-tech-card">
          <div class="agf-tech-icon"><i class="fas fa-box"></i></div>
          <div class="agf-tech-content">
            <h4>9. Emballage GMP hermétique à azote</h4>
            <p>Protection optimale des produits.</p>
          </div>
        </div>
      </div>
    </div>
    <p class="mt-4"><strong>La plateforme intègre :</strong></p>
    <ul class="mt-3" style="list-style: none; padding: 0;">
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Plus de 2 000 hectares de systèmes de production agricole organique intégrés</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Plus de 5 000 agriculteurs contractuels</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> L'installation de fabrication avancée ANINOVA INDUSTRIES sur 5 hectares</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Les laboratoires scientifiques avancés CERIQA LABORATORIES</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> ABINOVA AGRO-ESTATES</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Les systèmes ABINOVA LIVESTOCK BIORESOURCE</li>
      <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Le réseau de supermarchés NATURAL HEALTH FOOD SUPERMARKETS</li>
      <li style="padding: 8px 0;"><i class="fas fa-check-circle" style="color: #116E63; margin-right: 10px;"></i> Des canaux de commercialisation domestiques, régionaux et internationaux</li>
    </ul>
    <p class="mt-4">L'installation soutient le passage d'une unité de transformation pilote existante à une plateforme de production industrielle pleinement intégrée à grande échelle.</p>
  </div>
</section>

<!-- SECTION 2: RÉSUMÉ EXÉCUTIF DU CRÉDIT -->
<section style="background:#F2F3F5; padding:80px 0;" id="s2">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Résumé exécutif <span>du crédit</span></h2>
    </div>
    <p>A.G.F Limited, à travers sa division de fabrication « ABINOVA INDUSTRIES », présente une plateforme de production agro-industrielle et nutraceutique entièrement intégrée, comprenant un portefeuille commercial initial d'au moins 11 produits phares standardisés et « clean-label », destinés à être fabriqués en phase I via ABIPROF INDUSTRIES, en coopération technique avec les laboratoires CERIQA, détenus en propre.</p>
    <p class="mt-3">Les CERIQA LABORATORIES d'A.G.F Limited poursuivent en continu la R&amp;D sur la découverte de produits innovants, alimentant la fabrication industrielle d'ABINOVA INDUSTRIES de nouveaux produits organiques fondés sur des preuves, destinés à la commercialisation domestique, régionale et mondiale.</p>
    <h4 class="mt-4" style="color: #116E63; font-size: 18px; font-weight: 700;"><i class="bi bi-diagram-3"></i> Organigramme des unités stratégiques de gestion d'A.G.F Limited</h4>
    <div class="agf-callout mt-4">
      <p>Le projet est conçu pour créer un écosystème industriel évolutif, capable de soutenir un remboursement de dette durable tout en générant un impact significatif sur le développement économique, social, technologique et environnemental.</p>
    </div>
  </div>
</section>

<!-- SECTION 3: PROFIL DE LA SOCIÉTÉ -->
<section class="agf-about" id="s3">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Profil de <span>la société</span></h2>
    </div>
    <div class="agf-table-wrap">
      <table class="agf-table">
        <thead>
          <tr><th>Élément</th><th>Détail</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>Nom légal</strong></td><td>African Green Farmers (A.G.F) Limited</td></tr>
          <tr><td><strong>TPIN (Tax Pay Incorporation Number)</strong></td><td>2003675243</td></tr>
          <tr><td><strong>Licence d'investissement</strong></td><td>ZDA/59004/10/2025</td></tr>
          <tr><td><strong>Incitations fiscales (5 ans)</strong></td><td>Réf. ZDA/DG/DUTY, 22 janvier 2026 (Ministère des Finances)</td></tr>
          <tr><td><strong>Mécanisme SBLC (Standby Letter of Credit)</strong></td><td>En préparation auprès d'Absa Bank Zambia</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- SECTION 4: CONTEXTE & JUSTIFICATION STRATÉGIQUE -->
<section style="background:#F2F3F5; padding:80px 0;" id="s4">
  <div class="container">
    <div class="agf-sh mb-4">
      <h2>Contexte &amp; justification <span>stratégique</span></h2>
    </div>
    <p>La République de Zambie présente l'une des destinations d'investissement agro-industriel les plus intéressantes d'Afrique, portée par une stabilité politique, une localisation stratégique au centre de l'Afrique australe, d'abondantes ressources en eau douce, et plus de 42 millions d'hectares de terres arables, dont une grande partie reste disponible à des coûts d'acquisition très compétitifs. Grâce à l'accès aux marchés régionaux via la SADC, le COMESA et la ZLECAf (AfCFTA), la Zambie offre une porte d'entrée vers une base de consommateurs en expansion rapide, tout en offrant des opportunités significatives de commercialisation agricole, de valeur ajoutée industrielle et de croissance tirée par l'exportation.</p>
    <p class="mt-3">A.G.F Limited est stratégiquement positionnée pour capitaliser sur ces avantages structurels à travers une plateforme agro-industrielle et biotechnologique verticalement intégrée, combinant agriculture régénérative à grande échelle, fabrication avancée, recherche scientifique en laboratoire et systèmes de contrôle qualité, ainsi que commercialisation à l'export. Le projet transforme des ressources agricoles sous-utilisées en produits alimentaires, nutraceutiques et agricoles biologiques riches en bioactifs et en nutriments, réduisant la dépendance aux importations, générant des devises, créant des emplois durables, favorisant l'industrialisation rurale et un développement économique durable à long terme, tout en offrant de solides rendements commerciaux et une valeur d'investissement de qualité institutionnelle.</p>
    <div class="agf-values-grid mt-5">
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-shield-check"></i></div>
        <h4>Stabilité politique</h4>
        <p>Démocratie stable et environnement favorable aux investisseurs.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-geo-alt"></i></div>
        <h4>Localisation stratégique</h4>
        <p>Accès aux marchés SADC, COMESA et ZLECAf.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-water"></i></div>
        <h4>Ressources abondantes</h4>
        <p>Plus de 42 millions d'hectares de terres arables.</p>
      </div>
      <div class="agf-value-card">
        <div class="agf-value-icon"><i class="bi bi-rocket-takeoff"></i></div>
        <h4>Industrialisation</h4>
        <p>Agenda gouvernemental fort pour le développement agro-industriel.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 5: ÉNONCÉ DE VISION -->
<section class="agf-about" id="s5">
  <div class="container">
    <div class="agf-sh text-center mb-4">
      <h2>Énoncé de <span>vision</span></h2>
    </div>
    <div class="agf-vm-grid">
      <div class="agf-vm-card" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto;">
        <span style="display: inline-block; background: rgba(17,110,99,0.1); color: #116E63; padding: 8px 20px; border-radius: 50px; font-size: 13px; font-weight: 600;"><i class="bi bi-eye"></i> Vision</span>
        <h3 style="font-size: 24px; margin-top: 20px;">Devenir une entreprise agro-industrielle africaine de premier plan, moteur d'une fabrication alimentaire durable, d'une agriculture régénérative (bio), d'une industrialisation rurale et d'une valeur ajoutée orientée vers l'exportation.</h3>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 6: ÉNONCÉ DE MISSION -->
<section style="background:#F2F3F5; padding:80px 0;" id="s6">
  <div class="container">
    <div class="agf-sh text-center mb-4">
      <h2>Énoncé de <span>mission</span></h2>
    </div>
    <div class="agf-vm-grid">
      <div class="agf-vm-card" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto;">
        <span style="display: inline-block; background: rgba(17,110,99,0.1); color: #116E63; padding: 8px 20px; border-radius: 50px; font-size: 13px; font-weight: 600;"><i class="bi bi-bullseye"></i> Mission</span>
        <h3 style="font-size: 24px; margin-top: 20px;">Développer un écosystème agro-industriel évolutif qui transforme les ressources agricoles en produits alimentaires et agricoles riches en bioactifs et en nutriments, grâce à des technologies de transformation avancées et une participation rurale inclusive à grande échelle.</h3>
      </div>
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

</main>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
