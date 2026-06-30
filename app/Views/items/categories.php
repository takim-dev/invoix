<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Item Categories<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-tags me-2" style="color:#6c5ce7"></i>Item Categories</h2>
    <a href="/items" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Items</a>
</div>

<div class="row g-4">
<div class="col-md-6">
    <div class="card">
        <div class="card-header"><i class="bi bi-plus-circle me-1"></i> Add Category</div>
        <div class="card-body">
            <form action="/items/categories/store" method="POST" class="d-flex gap-2">
                <input type="text" name="name" class="form-control" required placeholder="Category name">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
            </form>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header">All Categories (<?= count($categories) ?>)</div>
        <div class="card-body p-0">
            <?php if (empty($categories)): ?>
                <p class="text-center py-4 text-muted">No categories yet</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                <?php foreach ($categories as $cat): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($cat['name']) ?>
                        <form action="/items/categories/<?= $cat['id'] ?>/delete" method="POST" data-confirm="Delete this category?">
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
