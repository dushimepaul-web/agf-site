<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* ================================================================
   AGF SHOP — Product Catalogue
   Matches AGF design system with green accent
============================================================ */
.agf-shop {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* HERO — Same as profil-societe */
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

/* MAIN */
.agf-shop-main {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

/* FILTER BAR */
.agf-filter-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 2rem;
    overflow-x: auto;
    padding-bottom: 4px;
    flex-wrap: wrap;
}
.agf-filter-tab {
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.agf-filter-tab:hover {
    background: #f1f5f9;
    color: #19232B;
    border-color: #cbd5e1;
}
.agf-filter-tab.active {
    background: #116E63;
    color: #fff;
    border-color: #116E63;
}
.agf-filter-tab .count {
    font-size: 0.72rem;
    opacity: 0.7;
}

/* SEARCH */
.agf-shop-search {
    display: flex;
    max-width: 400px;
    margin-bottom: 2rem;
}
.agf-shop-search input {
    flex: 1;
    padding: 10px 16px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 30px 0 0 30px;
    color: #19232B;
    font-size: 0.9rem;
    outline: none;
    font-family: 'Inter', sans-serif;
}
.agf-shop-search input:focus {
    border-color: #116E63;
    box-shadow: 0 0 0 3px rgba(17,110,99,0.1);
}
.agf-shop-search input::placeholder {
    color: #9ca3af;
}
.agf-shop-search button {
    padding: 10px 20px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 30px 30px 0;
    color: #19232B;
    cursor: pointer;
    transition: all 0.2s ease;
}
.agf-shop-search button:hover {
    background: #116E63;
    color: #fff;
    border-color: #116E63;
}

/* PRODUCT GRID */
.agf-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    transition: opacity 0.3s ease;
}

/* PRODUCT CARD */
.agf-shop-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.agf-shop-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    border-color: #dcbb07;
}
.agf-shop-card-img {
    width: 100%;
    aspect-ratio: 4/3;
    background-size: cover;
    background-position: center;
    background-color: #f8f9fa;
    position: relative;
    transition: transform 0.5s ease;
}
.agf-shop-card:hover .agf-shop-card-img {
    transform: scale(1.04);
}
.agf-shop-card-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(transparent 60%, rgba(0,0,0,0.5));
}
.agf-shop-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    background: #116E63;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 8px rgba(17,110,99,0.25);
}
.agf-shop-card-body {
    padding: 1.1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.agf-shop-card-cat {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #116E63;
    margin-bottom: 6px;
    display: block;
}
.agf-shop-card-title {
    font-family: 'Yantramanav', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: #19232B;
    margin-bottom: 6px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.agf-shop-card-title:hover {
    color: #116E63;
}
.agf-shop-card-desc {
    font-size: 0.8rem;
    color: #757F95;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 1rem;
    flex: 1;
}
.agf-shop-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}
.agf-shop-card-price {
    font-size: 0.95rem;
    font-weight: 700;
    color: #116E63;
}
.agf-shop-card-action {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(17,110,99,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #116E63;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
.agf-shop-card:hover .agf-shop-card-action {
    background: #dcbb07;
    color: #fff;
    transform: translateX(3px);
}

/* EMPTY STATE */
.agf-shop-empty {
    text-align: center;
    padding: 4rem 2rem;
    color: #757F95;
}
.agf-shop-empty i {
    font-size: 3rem;
    opacity: 0.4;
    display: block;
    margin-bottom: 1rem;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .ud-hero {
        padding: 100px 0 60px;
    }
    .ud-hero-title {
        font-size: 38px !important;
    }
}
@media (max-width: 768px) {
    .ud-hero { padding: 80px 0 50px; }
    .ud-hero-title { font-size: 30px !important; }
    .agf-shop-main { padding: 1.25rem 1rem; }
    .agf-products-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.875rem; }
    .agf-shop-card-body { padding: 0.85rem; }
    .agf-shop-card-title { font-size: 0.88rem; }
    .agf-shop-card-desc { display: none; }
    .agf-shop-card-price { font-size: 0.85rem; }
    .agf-filter-bar { gap: 6px; }
    .agf-filter-tab { padding: 6px 14px; font-size: 0.8rem; }
}
@media (max-width: 480px) {
    .agf-products-grid { grid-template-columns: 1fr 1fr; gap: 0.625rem; }
    .agf-shop-card-img { aspect-ratio: 1; }
}
</style>

<main class="agf-main agf-shop">

<!-- HERO -->
<div class="ud-hero" style="background: url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container">
    <div class="ud-hero-content">
      <h1 class="ud-hero-title">Our Products</h1>
      <p class="ud-hero-slogan">"Discover our range of nutraceuticals, fortified foods and organic agricultural inputs."</p>
      <a href="<?= base_url('') ?>" class="ud-hero-btn">
        <i class="fas fa-arrow-left-long"></i> Back to Home
      </a>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="agf-shop-main">
    <!-- Filter -->
    <div class="agf-filter-bar" id="filterBar">
        <a href="javascript:void(0)" class="agf-filter-tab active" data-cat="all" onclick="filterProducts('all', this)">
            <i class="bi bi-grid-fill"></i> All <span class="count"><?= count($produits) ?></span>
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="javascript:void(0)" class="agf-filter-tab" data-cat="<?= htmlspecialchars($cat['slug']) ?>" onclick="filterProducts('<?= htmlspecialchars($cat['slug']) ?>', this)">
                <?= htmlspecialchars($cat['nom']) ?> <span class="count"><?= $cat['total_produits'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Search -->
    <div class="agf-shop-search">
        <input type="text" placeholder="Search products..." id="shopSearch" autocomplete="off">
        <button onclick="searchProducts()"><i class="bi bi-search"></i></button>
    </div>

    <!-- Products Grid -->
    <div class="agf-products-grid" id="productsGrid">
        <?php foreach ($produits as $p):
            $img = !empty($p['image']) ? base_url($p['image']) : base_url('assets/backend/images/default-avatar.jpg');
        ?>
            <div class="agf-shop-card" onclick="window.location.href='<?= base_url('shop/detail/' . $p['slug']) ?>'">
                <div class="agf-shop-card-img" style="background-image:url('<?= $img ?>')">
                    <?php if ($p['est_certifie']): ?>
                        <span class="agf-shop-badge"><i class="bi bi-patch-check-fill"></i> Certified</span>
                    <?php endif; ?>
                </div>
                <div class="agf-shop-card-body">
                    <span class="agf-shop-card-cat"><?= htmlspecialchars($p['categorie_nom'] ?? '') ?></span>
                    <h3 class="agf-shop-card-title"><?= htmlspecialchars($p['nom']) ?></h3>
                    <p class="agf-shop-card-desc"><?= htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 80, '...')) ?></p>
                    <div class="agf-shop-card-footer">
                        <span class="agf-shop-card-price"><?= htmlspecialchars($p['prix'] ?? 'On request') ?></span>
                        <span class="agf-shop-card-action"><i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($produits)): ?>
        <div class="agf-shop-empty">
            <i class="bi bi-bag-x"></i>
            <h5>No products available</h5>
            <p>Check back later for new products.</p>
        </div>
    <?php endif; ?>
</div>

</main>

<script>
const API_URL = '<?= base_url("shop/apiProducts") ?>';
let currentCat = 'all';
let searchTimer = null;

function filterProducts(cat, el) {
    currentCat = cat;
    document.querySelectorAll('.agf-filter-tab').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');

    const grid = document.getElementById('productsGrid');
    grid.style.opacity = '0.4';

    fetch(API_URL + '?category=' + encodeURIComponent(cat))
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                grid.innerHTML = data.html || '<div class="agf-shop-empty"><i class="bi bi-bag-x"></i><h5>No products</h5></div>';
                grid.style.opacity = '1';
                grid.querySelectorAll('.agf-shop-card').forEach((card, i) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'none';
                    }, i * 50);
                });
            }
        })
        .catch(() => { grid.style.opacity = '1'; });
}

document.getElementById('shopSearch').addEventListener('input', function() {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    searchTimer = setTimeout(() => {
        const grid = document.getElementById('productsGrid');
        grid.style.opacity = '0.4';
        fetch(API_URL + '?category=' + currentCat + '&q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    grid.innerHTML = data.html || '<div class="agf-shop-empty"><i class="bi bi-search"></i><h5>No results</h5></div>';
                    grid.style.opacity = '1';
                }
            })
            .catch(() => { grid.style.opacity = '1'; });
    }, 300);
});

document.getElementById('shopSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') e.preventDefault();
});
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>