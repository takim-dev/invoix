<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= esc(lang('App.create_invoice')) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-plus-circle me-2" style="color:#c9a84c"></i><?= esc(lang('App.create_invoice')) ?></h2>
    <div class="d-flex gap-2">
        <a href="/help" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-question-circle me-1"></i> <?= esc(lang('App.help')) ?></a>
        <a href="/invoices" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> <?= esc(lang('App.back')) ?></a>
    </div>
</div>

<form action="/invoices/store" method="POST">
<?= csrf_field() ?>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-1"></i> <?= esc(lang('App.invoice_details')) ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.invoice_number')) ?></label>
                        <input type="text" name="invoice_number" id="invoiceNumber" class="form-control" value="" readonly placeholder="<?= esc(lang('App.select_company_first')) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.company_name')) ?> *</label>
                        <select name="company_id" id="companySelect" class="form-select" required>
                            <option value=""><?= esc(lang('App.select_company')) ?></option>
                            <?php foreach ($companies as $c): ?>
                                <option value="<?= $c['id'] ?>" data-code="<?= esc($c['code'] ?? '') ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.invoice_date')) ?> *</label>
                        <input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.due_date')) ?></label>
                        <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person me-1"></i> <?= esc(lang('App.client_info')) ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.client_name')) ?> *</label>
                        <input type="text" name="client_name" class="form-control" required placeholder="<?= esc(lang('App.client_name_ph')) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= esc(lang('App.client_email')) ?></label>
                        <input type="email" name="client_email" class="form-control" placeholder="<?= esc(lang('App.client_email_ph')) ?>">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label"><?= esc(lang('App.client_address')) ?></label>
                        <textarea name="client_address" class="form-control" rows="2" placeholder="<?= esc(lang('App.client_address_ph')) ?>"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-check me-1"></i> <?= esc(lang('App.line_items')) ?></span>
                <button type="button" class="btn btn-sm btn-primary" onclick="addRow()"><i class="bi bi-plus-lg me-1"></i> <?= esc(lang('App.add_row')) ?></button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width:35%;"><?= esc(lang('App.description')) ?></th>
                                <th style="width:20%;"><?= esc(lang('App.items')) ?></th>
                                <th style="width:10%;"><?= esc(lang('App.table_qty')) ?></th>
                                <th style="width:15%;"><?= esc(lang('App.table_price')) ?></th>
                                <th style="width:15%;"><?= esc(lang('App.total')) ?></th>
                                <th style="width:5%;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr>
                                <td><input type="text" name="item_description[]" class="form-control form-control-sm" required placeholder="<?= esc(lang('App.service_product_ph')) ?>"></td>
                                <td><select name="item_id[]" class="form-select form-select-sm" onchange="fillPrice(this)"><option value="">--</option><?php foreach ($items as $it): ?><option value="<?= $it['id'] ?>" data-price="<?= $it['unit_price'] ?>" data-currency="<?= esc($it['currency'] ?? 'USD') ?>"><?= esc($it['name']) ?></option><?php endforeach; ?></select></td>
                                <td><input type="number" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1" onchange="calcRow(this)"></td>
                                <td><input type="number" name="item_price[]" class="form-control form-control-sm" value="0" step="0.01" onchange="calcRow(this)"></td>
                                <td><span class="row-total fw-bold" style="color:#4ade80;">$0.00</span></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <label class="form-label"><?= esc(lang('App.notes')) ?></label>
                <textarea name="notes" class="form-control" rows="2" placeholder="<?= esc(lang('App.notes_ph')) ?>"></textarea>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-calculator me-1"></i> <?= esc(lang('App.summary')) ?></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label"><?= esc(lang('App.status')) ?></label>
                    <select name="status" class="form-select">
                        <option value="draft"><?= esc(lang('App.draft')) ?></option>
                        <option value="sent"><?= esc(lang('App.sent')) ?></option>
                        <option value="paid"><?= esc(lang('App.paid')) ?></option>
                        <option value="unpaid"><?= esc(lang('App.unpaid')) ?></option>
                        <option value="cancelled"><?= esc(lang('App.cancelled')) ?></option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= esc(lang('App.currency')) ?></label>
                    <select name="currency" class="form-select" id="currencySelect" onchange="updateCurrencySymbol()">
                        <option value="USD">USD - US Dollar</option>
                        <option value="IDR">IDR - Indonesian Rupiah</option>
                        <option value="MYR">MYR - Malaysian Ringgit</option>
                        <option value="CNY">CNY - Chinese Yuan</option>
                        <option value="INR">INR - Indian Rupee</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="SAR">SAR - Saudi Riyal</option>
                        <option value="VND">VND - Vietnamese Dong</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= esc(lang('App.tax_rate')) ?> (%)</label>
                    <input type="number" name="tax_rate" class="form-control" value="11" step="0.01" min="0" max="100">
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted"><?= esc(lang('App.subtotal')) ?></span><span id="subtotal">$0.00</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted"><?= esc(lang('App.tax')) ?></span><span id="taxAmt">$0.00</span></div>
                <hr>
                <div class="d-flex justify-content-between"><strong><?= esc(lang('App.total')) ?></strong><strong id="grandTotal" style="color:#c9a84c;font-size:1.3rem;">$0.00</strong></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i> <?= esc(lang('App.create_invoice')) ?></button>
    </div>
</div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let rowIdx = 1;
const itemOptions = `<?php foreach ($items as $it): ?><option value="<?= $it['id'] ?>" data-price="<?= $it['unit_price'] ?>" data-currency="<?= esc($it['currency'] ?? 'USD') ?>"><?= esc($it['name']) ?></option><?php endforeach; ?>`;

function addRow() {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="item_description[]" class="form-control form-control-sm" required placeholder="<?= esc(lang('App.service_product_ph')) ?>"></td>
        <td><select name="item_id[]" class="form-select form-select-sm" onchange="fillPrice(this)"><option value="">--</option>${itemOptions}</select></td>
        <td><input type="number" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1" onchange="calcRow(this)"></td>
        <td><input type="number" name="item_price[]" class="form-control form-control-sm" value="0" step="0.01" onchange="calcRow(this)"></td>
        <td><span class="row-total fw-bold" style="color:#4ade80;">$0.00</span></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>`;
    document.getElementById('itemsBody').appendChild(tr);
    rowIdx++;
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

// Fetch the next invoice number whenever the company changes.
// The number embeds both user.code and company.code, so it can only be
// generated once the company is known.
async function refreshInvoiceNumber() {
    const companySelect = document.getElementById('companySelect');
    const numberInput = document.getElementById('invoiceNumber');
    const companyId = companySelect.value;
    if (!companyId) {
        numberInput.value = '';
        numberInput.placeholder = '<?= lang('App.select_company_first') ?>';
        return;
    }
    try {
        const res = await fetch('<?= site_url('invoices/generate-number') ?>?company_id=' + encodeURIComponent(companyId), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data && data.number) {
            numberInput.value = data.number;
            numberInput.placeholder = '';
        } else {
            numberInput.value = '';
            numberInput.placeholder = '<?= lang('App.cannot_generate_number') ?>';
        }
    } catch (e) {
        numberInput.value = '';
        numberInput.placeholder = '<?= lang('App.error_fetch_number') ?>';
    }
}
document.getElementById('companySelect').addEventListener('change', refreshInvoiceNumber);
</script>
<?= $this->endSection() ?>
