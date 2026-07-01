<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= lang('App.companies') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><?= lang('App.company_management') ?></h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;"><?= lang('App.manage_business') ?></p>
    </div>
    <a href="/companies/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> <?= lang('App.add_company') ?>
    </a>
</div>

<?php if (empty($companies)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-building" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:0.3;"></i>
            <h5 class="text-muted"><?= lang('App.no_companies') ?></h5>
            <p class="text-muted mb-3" style="font-size:0.9rem;"><?= lang('App.add_first_company') ?></p>
            <a href="/companies/create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> <?= lang('App.add_first_company_btn') ?>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
    <?php foreach ($companies as $c): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <?php if ($c['logo']): ?>
                            <img src="/uploads/logos/<?= $c['logo'] ?>" alt="<?= lang('App.logo_alt') ?>" style="width:50px;height:50px;object-fit:contain;border-radius:10px;margin-right:1rem;background:#fff;padding:4px;flex-shrink:0;">
                        <?php else: ?>
                            <div style="width:50px;height:50px;border-radius:10px;background:rgba(99,102,241,0.12);display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-right:1rem;color:var(--bs-primary);flex-shrink:0;">
                                <i class="bi bi-building"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold"><?= esc($c['name']) ?></h6>
                            <?php if ($c['email']): ?>
                                <small class="text-muted"><?= esc($c['email']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3" style="font-size:0.85rem;">
                        <?php if ($c['address']): ?>
                            <div class="d-flex align-items-start mb-2">
                                <i class="bi bi-geo-alt me-2 text-muted" style="width:16px;margin-top:2px;"></i>
                                <span class="text-muted"><?= esc($c['address']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($c['phone']): ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-telephone me-2 text-muted" style="width:16px;"></i>
                                <span class="text-muted"><?= esc($c['phone']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($c['tax_number']): ?>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-upc me-2 text-muted" style="width:16px;"></i>
                                <span class="text-muted"><?= lang('App.npwp_label') ?>: <?= esc($c['tax_number']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top d-flex justify-content-end gap-2 py-3">
                    <a href="/companies/<?= $c['id'] ?>/edit" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i> <?= lang('App.edit') ?>
                    </a>
                    <form action="/companies/<?= $c['id'] ?>/delete" method="POST" class="d-inline" data-confirm="<?= lang('App.delete_company_confirm') ?>">
<?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> <?= lang('App.delete') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="mt-4 text-muted" style="font-size:0.85rem;">
        <?= str_replace('{count}', count($companies), lang('App.total_companies')) ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
