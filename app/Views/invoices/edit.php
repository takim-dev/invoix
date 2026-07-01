<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Edit Invoice<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-pencil me-2" style="color:#c9a84c"></i>Edit Invoice</h2>
    <a href="/invoices/<?= $invoice['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<form action="/invoices/<?= $invoice['id'] ?>/update" method="POST">
<?= csrf_field() ?>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-1"></i> Invoice Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" value="<?= esc($invoice['invoice_number']) ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company *</label>
                        <select name="company_id" class="form-select" required>
                            <?php foreach ($companies as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $invoice['company_id'] == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" class="form-control" value="<?= $invoice['invoice_date'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= $invoice['due_date'] ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person me-1"></i> Client Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="client_name" class="form-control" required value="<?= esc($invoice['client_name']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client Email</label>
                        <input type="email" name="client_email" class="form-control" value="<?= esc($invoice['client_email']) ?>">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Client Address</label>
                        <textarea name="client_address" class="form-control" rows="2"><?= esc($invoice['client_address']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-check me-1"></i> Line Items</span>
                <button type="button" class="btn btn-sm btn-primary" onclick="addRow()"><i class="bi bi-plus-lg me-1"></i> Add Row</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width:35%;">Description</th>
                                <th style="width:20%;">Item</th>
                                <th style="width:10%;">Qty</th>
                                <th style="width:15%;">Price</th>
                                <th style="width:15%;">Total</th>
                                <th style="width:5%;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><input type="text" name="item_description[]" class="form-control form-control-sm" required value="<?= esc($item['description']) ?>"></td>
                                <td><select name="item_id[]" class="form-select form-select-sm" onchange="fillPrice(this)"><option value="">--</option><?php foreach ($allItems as $it): ?><option value="<?= $it['id'] ?>" data-price="<?= $it['unit_price'] ?>" data-currency="<?= esc($it['currency'] ?? 'USD') ?>" <?= $item['item_id'] == $it['id'] ? 'selected' : '' ?>><?= esc($it['name']) ?></option><?php endforeach; ?></select></td>
                                <td><input type="number" name="item_quantity[]" class="form-control form-control-sm" value="<?= $item['quantity'] ?>" min="1" onchange="calcRow(this)"></td>
                                <td><input type="number" name="item_price[]" class="form-control form-control-sm" value="<?= $item['unit_price'] ?>" step="0.01" onchange="calcRow(this)"></td>
                                <td><span class="row-total fw-bold" style="color:#4ade80;">$<?= number_format($item['total'], 2) ?></span></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= esc($invoice['notes']) ?></textarea>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-calculator me-1"></i> Summary</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= $invoice['status']==='draft'?'selected':'' ?>>Draft</option>
                        <option value="sent" <?= $invoice['status']==='sent'?'selected':'' ?>>Sent</option>
                        <option value="paid" <?= $invoice['status']==='paid'?'selected':'' ?>>Paid</option>
                        <option value="unpaid" <?= $invoice['status']==='unpaid'?'selected':'' ?>>Unpaid</option>
                        <option value="cancelled" <?= $invoice['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-select" id="currencySelect" onchange="updateCurrencySymbol()">
                        <option value="USD" <?= ($invoice['currency'] ?? 'USD')==='USD'?'selected':'' ?>>USD - US Dollar</option>
                        <option value="IDR" <?= ($invoice['currency'] ?? '')==='IDR'?'selected':'' ?>>IDR - Indonesian Rupiah</option>
                        <option value="MYR" <?= ($invoice['currency'] ?? '')==='MYR'?'selected':'' ?>>MYR - Malaysian Ringgit</option>
                        <option value="CNY" <?= ($invoice['currency'] ?? '')==='CNY'?'selected':'' ?>>CNY - Chinese Yuan</option>
                        <option value="INR" <?= ($invoice['currency'] ?? '')==='INR'?'selected':'' ?>>INR - Indian Rupee</option>
                        <option value="EUR" <?= ($invoice['currency'] ?? '')==='EUR'?'selected':'' ?>>EUR - Euro</option>
                        <option value="SAR" <?= ($invoice['currency'] ?? '')==='SAR'?'selected':'' ?>>SAR - Saudi Riyal</option>
                        <option value="VND" <?= ($invoice['currency'] ?? '')==='VND'?'selected':'' ?>>VND - Vietnamese Dong</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" class="form-control" value="<?= $invoice['tax_rate'] ?>" step="0.01" min="0" max="100">
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span id="subtotal">$<?= number_format($invoice['subtotal'], 2) ?></span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax</span><span id="taxAmt">$<?= number_format($invoice['tax_amount'], 2) ?></span></div>
                <hr>
                <div class="d-flex justify-content-between"><strong>Total</strong><strong id="grandTotal" style="color:#c9a84c;font-size:1.3rem;">$<?= number_format($invoice['total'], 2) ?></strong></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i> Update Invoice</button>
    </div>
</div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const itemOptions = `<?php foreach ($allItems as $it): ?><option value="<?= $it['id'] ?>" data-price="<?= $it['unit_price'] ?>"><?= esc($it['name']) ?></option><?php endforeach; ?>`;

function addRow() {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="item_description[]" class="form-control form-control-sm" required placeholder="Service/Product"></td>
        <td><select name="item_id[]" class="form-select form-select-sm" onchange="fillPrice(this)"><option value="">--</option>${itemOptions}</select></td>
        <td><input type="number" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1" onchange="calcRow(this)"></td>
        <td><input type="number" name="item_price[]" class="form-control form-control-sm" value="0" step="0.01" onchange="calcRow(this)"></td>
        <td><span class="row-total fw-bold" style="color:#4ade80;">$0.00</span></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>`;
    document.getElementById('itemsBody').appendChild(tr);
    filterItemOptions();
}

function removeRow(btn) {
    if (document.querySelectorAll('#itemsBody tr').length > 1) {
        btn.closest('tr').remove();
        updateTotals();
    }
}

function fillPrice(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.price) {
        const priceInput = sel.closest('tr').querySelector('input[name="item_price[]"]');
        priceInput.value = parseFloat(opt.dataset.price).toFixed(2);
        const descInput = sel.closest('tr').querySelector('input[name="item_description[]"]');
        if (!descInput.value) descInput.value = opt.text;
        calcRow(priceInput);
    }
}

function calcRow(el) {
    const tr = el.closest('tr');
    const qty = parseFloat(tr.querySelector('input[name="item_quantity[]"]').value) || 0;
    const price = parseFloat(tr.querySelector('input[name="item_price[]"]').value) || 0;
    tr.querySelector('.row-total').textContent = window.currencySymbol + (qty * price).toFixed(2);
    updateTotals();
}

function updateCurrencySymbol() {
    const select = document.getElementById('currencySelect');
    const symbols = {'USD':'$','IDR':'Rp','MYR':'RM','CNY':'¥','INR':'₹','EUR':'€','SAR':'﷼','VND':'₫'};
    window.currencySymbol = symbols[select.value] || '$';
    filterItemOptions();
    updateTotals();
}
window.currencySymbol = '$';

function filterItemOptions() {
    const cur = document.getElementById('currencySelect').value;
    document.querySelectorAll('select[name="item_id[]"]').forEach(sel => {
        const currentVal = sel.value;
        let selectedValid = false;
        sel.querySelectorAll('option').forEach(opt => {
            if (!opt.value) return;
            const match = (opt.dataset.currency || 'USD') === cur;
            opt.hidden = !match;
            if (match && opt.value === currentVal) selectedValid = true;
        });
        if (currentVal && !selectedValid) {
            sel.value = '';
            const priceInput = sel.closest('tr').querySelector('input[name="item_price[]"]');
            if (priceInput) { priceInput.value = '0'; calcRow(priceInput); }
        }
    });
}

filterItemOptions();

function updateTotals() {
    let sub = 0;
    document.querySelectorAll('#itemsBody tr').forEach(tr => {
        const q = parseFloat(tr.querySelector('input[name="item_quantity[]"]')?.value) || 0;
        const p = parseFloat(tr.querySelector('input[name="item_price[]"]')?.value) || 0;
        sub += q * p;
    });
    const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
    const tax = sub * (taxRate / 100);
    document.getElementById('subtotal').textContent = window.currencySymbol + sub.toFixed(2);
    document.getElementById('taxAmt').textContent = window.currencySymbol + tax.toFixed(2);
    document.getElementById('grandTotal').textContent = window.currencySymbol + (sub + tax).toFixed(2);
}

document.querySelector('input[name="tax_rate"]').addEventListener('input', updateTotals);
</script>
<?= $this->endSection() ?>
