<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= esc(lang('App.item_categories')) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-tags me-2" style="color:#6c5ce7"></i><?= esc(lang('App.item_categories')) ?></h2>
    <a href="/items" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> <?= esc(lang('App.items')) ?></a>
</div>

<div class="row g-4">
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><i class="bi bi-plus-circle me-1"></i> <?= esc(lang('App.add_category')) ?></div>
        <div class="card-body">
            <form action="/items/categories/store" method="POST" class="d-flex gap-2">
<?= csrf_field() ?>
                <input type="text" name="name" class="form-control" required placeholder="<?= lang('App.category_name_ph') ?>">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
            </form>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><?= esc(lang('App.all_categories')) ?> (<?= count($categories) ?>)</div>
        <div class="card-body p-0">
            <?php if (empty($categories)): ?>
                <p class="text-center py-4 text-muted"><?= esc(lang('App.no_categories')) ?></p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                <?php foreach ($categories as $cat): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($cat['name']) ?>
                        <form action="/items/categories/<?= $cat['id'] ?>/delete" method="POST" data-confirm="<?= lang('App.delete_category_confirm') ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
