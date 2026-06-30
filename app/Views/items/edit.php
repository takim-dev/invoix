<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Edit Item<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-pencil me-2" style="color:#6c5ce7"></i>Edit Item</h2>
    <a href="/items" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row justify-content-center">
<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <form action="/items/<?= $item['id'] ?>/update" method="POST">
                <div class="mb-3">
                    <label class="form-label">Item Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?= esc($item['name']) ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- None --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="<?= esc($item['unit']) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Unit Price *</label>
                        <input type="number" name="unit_price" class="form-control" step="0.01" min="0" required value="<?= $item['unit_price'] ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-select">
                            <?php foreach (['USD','IDR','SGD','JPY','CNY','MYR'] as $c): ?>
                                <option value="<?= $c ?>" <?= ($item['currency'] ?? 'USD') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"><?= esc($item['description']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Item</button>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
