<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
/* ================================================================
   AGF SHOP — Product Detail
   Matches AGF design system with green accent
============================================================ */
.agf-detail {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* BREADCRUMB */
.agf-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 2rem;
    font-size: 0.85rem;
    color: #757F95;
    flex-wrap: wrap;
}
.agf-breadcrumb a {
    color: #116E63;
    text-decoration: none;
    transition: all 0.2s ease;
}
.agf-breadcrumb a:hover {
    text-decoration: underline;
    color: #dcbb07;
}
.agf-breadcrumb .sep {
    opacity: 0.4;
}

/* LAYOUT */
.agf-product-layout {
    display: flex;
    gap: 2.5rem;
}
@media (max-width: 768px) {
    .agf-product-layout {
        flex-direction: column;
        gap: 1.5rem;
    }
}
.agf-product-image-col {
    flex: 1;
    min-width: 0;
}
.agf-product-info-col {
    flex: 1;
    min-width: 0;
}

/* IMAGE */
.agf-product-image-box {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}
.agf-product-image-box img {
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
    padding: 2rem;
    transition: transform 0.4s ease;
}
.agf-product-image-box:hover img {
    transform: scale(1.05);
}
.agf-product-certified-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    background: #116E63;
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 4px 12px rgba(17,110,99,0.25);
}

/* INFO */
.agf-product-category-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    background: rgba(17,110,99,0.1);
    color: #116E63;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}
.agf-product-title {
    font-family: 'Yantramanav', sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: #19232B;
    line-height: 1.3;
    letter-spacing: -0.5px;
    margin-bottom: 1rem;
}
.agf-product-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: #116E63;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.agf-product-price small {
    font-size: 0.8rem;
    font-weight: 500;
    color: #757F95;
}

.agf-product-desc-box {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}
.agf-product-desc-box h4 {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 6px;
    color: #19232B;
}
.agf-product-desc-box h4 i {
    color: #116E63;
}
.agf-product-desc-box p {
    color: #757F95;
    font-size: 0.9rem;
    line-height: 1.7;
}

.agf-product-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 1.5rem;
}
.agf-meta-item {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 14px;
}
.agf-meta-item-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #757F95;
    margin-bottom: 4px;
}
.agf-meta-item-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: #19232B;
}

.agf-product-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.agf-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #dcbb07;
    color: #fff !important;
    border: none;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}
.agf-btn-primary:hover {
    background: #116E63;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(17,110,99,0.25);
}
.agf-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: transparent;
    color: #64748b;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.agf-btn-outline:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #19232B;
}

/* RELATED */
.agf-related-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #f0f0f0;
}
.agf-related-section h3 {
    font-family: 'Yantramanav', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #19232B;
}
.agf-related-section h3 i {
    color: #116E63;
}
.agf-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.25rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .agf-detail-main { padding: 1rem; }
    .agf-product-title { font-size: 1.3rem; }
    .agf-product-price { font-size: 1.2rem; }
    .agf-product-image-box { min-height: 280px; }
    .agf-product-image-box img { max-height: 350px; padding: 1rem; }
    .agf-product-meta-grid { grid-template-columns: 1fr; }
    .agf-modal { width: 95%; max-height: 90vh; }
    .agf-form-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .agf-product-actions { flex-direction: column; }
    .agf-btn-primary, .agf-btn-outline { width: 100%; justify-content: center; }
}

/* ORDER MODAL */
.agf-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; visibility: hidden; transition: all 0.3s ease;
    padding: 1rem;
}
.agf-modal-overlay.open { opacity: 1; visibility: visible; }
.agf-modal {
    background: #fff; border-radius: 20px; width: 100%; max-width: 520px;
    max-height: 90vh; overflow-y: auto;
    transform: translateY(30px) scale(0.95); transition: all 0.3s ease;
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
}
.agf-modal-overlay.open .agf-modal { transform: translateY(0) scale(1); }
.agf-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px; border-bottom: 1px solid #f0f0f0;
    position: sticky; top: 0; background: #fff; z-index: 2;
    border-radius: 20px 20px 0 0;
}
.agf-modal-header h3 {
    font-family: 'Yantramanav', sans-serif; font-size: 1.15rem;
    font-weight: 700; color: #19232B; margin: 0;
    display: flex; align-items: center; gap: 8px;
}
.agf-modal-header h3 i { color: #116E63; }
.agf-modal-close {
    width: 36px; height: 36px; border-radius: 50%; border: none;
    background: #f1f5f9; color: #64748b; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s ease;
}
.agf-modal-close:hover { background: #fee2e2; color: #dc2626; }
.agf-modal-body { padding: 24px; }

/* Product Summary in modal */
.agf-order-product {
    display: flex; gap: 14px; align-items: center;
    background: #f8f9fa; border-radius: 14px; padding: 14px;
    margin-bottom: 20px; border: 1px solid #f0f0f0;
}
.agf-order-product-img {
    width: 70px; height: 70px; border-radius: 12px;
    background-size: cover; background-position: center;
    background-color: #e2e8f0; flex-shrink: 0;
}
.agf-order-product-info { flex: 1; min-width: 0; }
.agf-order-product-cat {
    font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.5px; color: #116E63; display: block; margin-bottom: 2px;
}
.agf-order-product-info h4 {
    font-family: 'Yantramanav', sans-serif; font-size: 0.95rem;
    font-weight: 700; color: #19232B; margin: 0 0 4px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.agf-order-product-price {
    font-size: 0.85rem; font-weight: 700; color: #116E63;
}

/* Form */
.agf-form-group { margin-bottom: 16px; }
.agf-form-group label {
    display: block; font-size: 0.8rem; font-weight: 600;
    color: #19232B; margin-bottom: 6px;
}
.agf-form-group label span { color: #dc2626; }
.agf-form-group input,
.agf-form-group select,
.agf-form-group textarea {
    width: 100%; padding: 11px 14px; border: 1px solid #e2e8f0;
    border-radius: 10px; font-size: 0.9rem; font-family: 'Inter', sans-serif;
    color: #19232B; background: #fff; transition: all 0.2s ease;
    outline: none;
}
.agf-form-group input:focus,
.agf-form-group select:focus,
.agf-form-group textarea:focus {
    border-color: #116E63; box-shadow: 0 0 0 3px rgba(17,110,99,0.1);
}
.agf-form-group input::placeholder,
.agf-form-group textarea::placeholder { color: #9ca3af; }
.agf-form-group textarea { resize: vertical; }
.agf-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.agf-btn-full {
    width: 100%; justify-content: center; padding: 14px;
    font-size: 1rem; margin-top: 8px;
}

/* TOAST */
.agf-toast-container {
    position: fixed; bottom: 20px; right: 20px;
    z-index: 9998; width: 90%; max-width: 420px;
}
.agf-toast {
    background: #19232B; border-radius: 14px; overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    border: 1px solid rgba(220,187,7,0.3);
    animation: toastSlideUp 0.4s ease;
}
@keyframes toastSlideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.agf-toast-header {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 16px; background: #111; border-bottom: 1px solid rgba(220,187,7,0.2);
    color: #fff; font-size: 0.85rem; font-weight: 600;
}
.agf-toast-header i { color: #dcbb07; font-size: 1rem; }
.agf-toast-header strong { flex: 1; }
.agf-toast-close {
    background: none; border: none; color: rgba(255,255,255,0.5);
    cursor: pointer; padding: 4px; font-size: 0.75rem;
    transition: color 0.2s;
}
.agf-toast-close:hover { color: #fff; }
.agf-toast-body {
    padding: 16px;
}
.agf-toast-body p {
    color: rgba(255,255,255,0.85); font-size: 0.85rem;
    line-height: 1.6; margin: 0 0 12px;
}
.agf-toast-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.agf-toast-btn-whatsapp {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; background: #25d366; color: #fff;
    border-radius: 8px; font-size: 0.8rem; font-weight: 600;
    text-decoration: none; transition: all 0.2s ease;
}
.agf-toast-btn-whatsapp:hover { background: #1da851; color: #fff; transform: translateY(-1px); }
.agf-toast-btn-email {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; background: #dcbb07; color: #fff;
    border-radius: 8px; font-size: 0.8rem; font-weight: 600;
    text-decoration: none; transition: all 0.2s ease;
}
.agf-toast-btn-email:hover { background: #c5a206; color: #fff; transform: translateY(-1px); }
@keyframes toastSlideDown {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(30px); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.agf-product-image-col, .agf-product-info-col {
    animation: fadeInUp 0.5s ease forwards;
}
.agf-product-info-col {
    animation-delay: 0.1s;
}

/* Related Product Card */
.agf-related-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}
.agf-related-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    border-color: #dcbb07;
}
.agf-related-card-img {
    width: 100%;
    aspect-ratio: 4/3;
    background-size: cover;
    background-position: center;
    background-color: #f8f9fa;
    position: relative;
}
.agf-related-card:hover .agf-related-card-img {
    transform: scale(1.04);
}
.agf-related-card-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(transparent 60%, rgba(0,0,0,0.5));
}
.agf-related-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    background: #116E63;
    color: #fff;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 3px;
}
.agf-related-card-body {
    padding: 1rem;
}
.agf-related-card-cat {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #116E63;
    display: block;
    margin-bottom: 4px;
}
.agf-related-card-title {
    font-family: 'Yantramanav', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    color: #19232B;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 0.5rem;
}
.agf-related-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.agf-related-card-price {
    font-size: 0.85rem;
    font-weight: 700;
    color: #116E63;
}
.agf-related-card-action {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(17,110,99,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #116E63;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}
.agf-related-card:hover .agf-related-card-action {
    background: #dcbb07;
    color: #fff;
}
</style>

<main class="agf-main agf-detail">
<div class="agf-detail-main" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem;">

    <!-- Breadcrumb -->
    <div class="agf-breadcrumb">
        <a href="<?= base_url('shop') ?>"><i class="bi bi-house"></i> Shop</a>
        <span class="sep">/</span>
        <a href="<?= base_url('shop/category/' . $produit['categorie_slug']) ?>"><?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($produit['nom']) ?></span>
    </div>

    <div class="agf-product-layout">
        <!-- Image -->
        <div class="agf-product-image-col">
            <div class="agf-product-image-box">
                <?php $img = !empty($produit['image']) ? base_url($produit['image']) : ''; ?>
                <?php if (!empty($img)): ?>
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                <?php else: ?>
                    <i class="bi bi-image" style="font-size:4rem;color:#9ca3af;opacity:0.3;"></i>
                <?php endif; ?>
                <?php if ($produit['est_certifie']): ?>
                    <span class="agf-product-certified-badge"><i class="bi bi-patch-check-fill"></i> Certified Product</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info -->
        <div class="agf-product-info-col">
            <span class="agf-product-category-tag"><i class="bi bi-tag"></i> <?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></span>
            <h1 class="agf-product-title"><?= htmlspecialchars($produit['nom']) ?></h1>

            <?php if (!empty($produit['prix'])): ?>
                <div class="agf-product-price">
                    <?= htmlspecialchars($produit['prix']) ?>
                </div>
            <?php endif; ?>

            <div class="agf-product-desc-box">
                <h4><i class="bi bi-info-circle"></i> Description</h4>
                <p><?= nl2br(htmlspecialchars($produit['description'] ?? 'No description available.')) ?></p>
            </div>

            <div class="agf-product-meta-grid">
                <?php if (!empty($produit['conditionnement'])): ?>
                    <div class="agf-meta-item">
                        <div class="agf-meta-item-label">Packaging</div>
                        <div class="agf-meta-item-value"><i class="bi bi-box" style="margin-right:4px;color:#116E63;"></i> <?= htmlspecialchars($produit['conditionnement']) ?></div>
                    </div>
                <?php endif; ?>
                <div class="agf-meta-item">
                    <div class="agf-meta-item-label">Category</div>
                    <div class="agf-meta-item-value"><i class="bi bi-collection" style="margin-right:4px;color:#116E63;"></i> <?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></div>
                </div>
                <?php if ($produit['est_certifie']): ?>
                    <div class="agf-meta-item">
                        <div class="agf-meta-item-label">Certification</div>
                        <div class="agf-meta-item-value"><i class="bi bi-patch-check-fill" style="margin-right:4px;color:#116E63;"></i> Certified</div>
                    </div>
                <?php endif; ?>
                <div class="agf-meta-item">
                    <div class="agf-meta-item-label">Availability</div>
                    <div class="agf-meta-item-value"><i class="bi bi-check-circle-fill" style="margin-right:4px;color:#116E63;"></i> Available</div>
                </div>
            </div>

            <div class="agf-product-actions">
                <button type="button" class="agf-btn-primary" onclick="openOrderModal()">
                    <i class="bi bi-whatsapp"></i> Contact to Order
                </button>
                <button class="agf-btn-outline" onclick="shareProduct()">
                    <i class="bi bi-share"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
        <div class="agf-related-section">
            <h3><i class="bi bi-grid"></i> Related Products</h3>
            <div class="agf-related-grid">
                <?php foreach ($related as $r):
                    $rimg = !empty($r['image']) ? base_url($r['image']) : base_url('assets/backend/images/default-avatar.jpg');
                ?>
                    <div class="agf-related-card" onclick="window.location.href='<?= base_url('shop/detail/' . $r['slug']) ?>'">
                        <div class="agf-related-card-img" style="background-image:url('<?= $rimg ?>')">
                            <?php if ($r['est_certifie']): ?>
                                <span class="agf-related-badge"><i class="bi bi-patch-check-fill"></i> Certified</span>
                            <?php endif; ?>
                        </div>
                        <div class="agf-related-card-body">
                            <span class="agf-related-card-cat"><?= htmlspecialchars($r['categorie_nom'] ?? '') ?></span>
                            <h3 class="agf-related-card-title"><?= htmlspecialchars($r['nom']) ?></h3>
                            <div class="agf-related-card-footer">
                                <span class="agf-related-card-price"><?= htmlspecialchars($r['prix'] ?? '') ?></span>
                                <span class="agf-related-card-action"><i class="bi bi-arrow-right"></i></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>
</main>

<!-- TOAST PRIX RÉEL -->
<div class="agf-toast-container">
    <div class="agf-toast" id="priceToast">
        <div class="agf-toast-header">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Important Information</strong>
            <button class="agf-toast-close" onclick="closeToast()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="agf-toast-body">
            <p>Please contact us via WhatsApp (+260) 777 844 844 or Email agfcompany2026@gmail.com to get the actual price.</p>
            <div class="agf-toast-actions">
                <a href="https://wa.me/260777844844?text=<?= urlencode("Hello, I would like to know the actual price of " . $produit['nom'] . ".\n\nSource: www.agf.com\nProduct: " . $produit['nom'] . "\nProduct link: " . base_url('shop/detail/' . $produit['slug'])) ?>" target="_blank" class="agf-toast-btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
                <?php
                    $mailto_subject = 'Price inquiry: ' . $produit['nom'];
                    $mailto_body = "Hello,\n\nI would like to know the actual price of " . $produit['nom'] . ".\n\nSource: www.agf.com\nProduct: " . $produit['nom'] . "\nProduct link: " . base_url('shop/detail/' . $produit['slug']);
                    $mailto_url = 'mailto:agfcompany2026@gmail.com?subject=' . urlencode($mailto_subject) . '&body=' . urlencode($mailto_body);
                ?>
                <a href="<?= $mailto_url ?>" class="agf-toast-btn-email">
                    <i class="bi bi-envelope-fill"></i> Email
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ORDER MODAL -->
<div class="agf-modal-overlay" id="orderModal">
    <div class="agf-modal">
        <div class="agf-modal-header">
            <h3><i class="bi bi-cart-check"></i> Finalize your order</h3>
            <button class="agf-modal-close" onclick="closeOrderModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="agf-modal-body">
            <!-- Product Summary -->
            <div class="agf-order-product">
                <div class="agf-order-product-img" style="background-image:url('<?= !empty($produit['image']) ? base_url($produit['image']) : '' ?>')"></div>
                <div class="agf-order-product-info">
                    <span class="agf-order-product-cat"><?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></span>
                    <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                    <?php if (!empty($produit['prix'])): ?>
                        <span class="agf-order-product-price"><?= htmlspecialchars($produit['prix']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <form id="orderForm" onsubmit="sendOrderWhatsApp(event)">
                <div class="agf-form-group">
                    <label>Full Name <span>*</span></label>
                    <input type="text" id="orderName" placeholder="Your first and last name" required>
                </div>
                <div class="agf-form-group">
                    <label>Phone <span>*</span></label>
                    <input type="tel" id="orderPhone" placeholder="Ex: 25779666439" required>
                </div>
                <div class="agf-form-group">
                    <label>WhatsApp Number</label>
                    <input type="tel" id="orderWhatsApp" placeholder="Number to contact on WhatsApp">
                </div>
                <div class="agf-form-row">
                    <div class="agf-form-group">
                        <label>Country <span>*</span></label>
                        <input type="text" id="orderCountry" placeholder="Your country" required>
                    </div>
                    <div class="agf-form-group">
                        <label>City <span>*</span></label>
                        <input type="text" id="orderCity" placeholder="Your city" required>
                    </div>
                </div>
                <div class="agf-form-group">
                    <label>Full Delivery Address <span>*</span></label>
                    <textarea id="orderAddress" placeholder="Neighborhood, street, number, landmark..." rows="3" required></textarea>
                </div>

                <button type="submit" class="agf-btn-primary agf-btn-full">
                    <i class="bi bi-whatsapp"></i> Send Order via WhatsApp
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const ORDER_PRODUCT = <?= json_encode([
    'nom' => $produit['nom'],
    'prix' => $produit['prix'] ?? '',
    'slug' => $produit['slug'],
    'categorie' => $produit['categorie_nom'] ?? '',
]) ?>;
const WHATSAPP_NUMBER = '<?= ltrim(preg_replace('/[^0-9]/', '', $this->Model->get_setting('whatsapp_number', $this->Model->get_setting('site_phone', '+260777844844'))), '+') ?>';

function openOrderModal() {
    document.getElementById('orderModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeOrderModal() {
    document.getElementById('orderModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) closeOrderModal();
});

// TOAST - show for 5 seconds
setTimeout(function() {
    var toast = document.getElementById('priceToast');
    if (toast) {
        toast.style.animation = 'toastSlideDown 0.4s ease forwards';
        setTimeout(function() { toast.remove(); }, 400);
    }
}, 5000);
function closeToast() {
    var toast = document.getElementById('priceToast');
    if (toast) {
        toast.style.animation = 'toastSlideDown 0.4s ease forwards';
        setTimeout(function() { toast.remove(); }, 400);
    }
}

function sendOrderWhatsApp(e) {
    e.preventDefault();
    const name = document.getElementById('orderName').value.trim();
    const phone = document.getElementById('orderPhone').value.trim();
    const whatsapp = document.getElementById('orderWhatsApp').value.trim();
    const country = document.getElementById('orderCountry').value;
    const city = document.getElementById('orderCity').value.trim();
    const address = document.getElementById('orderAddress').value.trim();

    if (!name || !phone || !country || !city || !address) {
        alert('Please fill in all required fields.');
        return;
    }

    let msg = `*NEW ORDER*\n\n`;
    msg += `*Product:* ${ORDER_PRODUCT.nom}\n`;
    if (ORDER_PRODUCT.prix) msg += `*Price:* ${ORDER_PRODUCT.prix}\n`;
    msg += `*Link:* ${window.location.href}\n\n`;
    msg += `*Customer Info:*\n`;
    msg += `• Full Name: ${name}\n`;
    msg += `• Phone: ${phone}\n`;
    if (whatsapp) msg += `• WhatsApp: ${whatsapp}\n`;
    msg += `• Country: ${country}\n`;
    msg += `• City: ${city}\n`;
    msg += `• Address: ${address}\n`;

    const url = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(msg)}`;
    window.open(url, '_blank');
    closeOrderModal();
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({ title: '<?= htmlspecialchars($produit["nom"] ?? "") ?>', url: window.location.href }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied!');
    }
}
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>