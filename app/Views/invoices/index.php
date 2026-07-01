<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= esc(lang('App.invoices')) ?><?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    .invoice-page .topbar {
        gap: 1rem;
        flex-wrap: wrap;
    }

    .invoice-table-card {
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
    }

    .invoice-filter-card {
        margin-bottom: 1rem;
        border-radius: 10px;
    }

    .invoice-filter-grid {
        display: grid;
        grid-template-columns: minmax(180px, 1.1fr) minmax(180px, 1fr) repeat(2, minmax(150px, 0.8fr)) auto;
        gap: 0.9rem;
        align-items: end;
    }

    .invoice-filter-grid .form-label {
        margin-bottom: 0.35rem;
        color: var(--bs-secondary-color);
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .invoice-filter-grid .form-control,
    .invoice-filter-grid .form-select {
        min-height: 38px;
        border-radius: 8px;
    }

    .invoice-filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: flex-end;
        white-space: nowrap;
    }

    .invoice-table-card .card-body {
        padding: 0;
    }

    .invoice-table-card .dataTables_wrapper {
        padding: 1rem 1rem 0;
    }

    .invoice-table-card .dataTables_wrapper > .d-flex:first-child {
        gap: 1rem;
        padding: 0 0 1rem;
        margin: 0 !important;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .invoice-table-card .dataTables_length label,
    .invoice-table-card .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin: 0;
        color: var(--bs-secondary-color);
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .invoice-table-card .dataTables_length select {
        width: auto;
        min-width: 74px;
        height: 36px;
        display: inline-block;
        padding: 0.35rem 2rem 0.35rem 0.75rem;
        border-radius: 8px;
    }

    .invoice-table-card .dataTables_filter input {
        width: min(280px, 42vw);
        height: 36px;
        margin: 0;
        border-radius: 8px;
    }

    .invoice-table-card .table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .invoice-table-card table.dataTable {
        width: 100% !important;
        min-width: 940px;
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .invoice-table-card table.dataTable thead th {
        padding: 0.9rem 1rem;
        color: var(--bs-secondary-color);
        background: var(--bs-tertiary-bg);
        border-bottom: 1px solid var(--bs-border-color);
        font-size: 0.72rem;
        font-weight: 700;
        vertical-align: middle;
    }

    .invoice-table-card table.dataTable tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid var(--bs-border-color);
        vertical-align: middle;
    }

    .invoice-table-card table.dataTable tbody tr:last-child td {
        border-bottom: 0;
    }

    .invoice-number-link {
        color: #6571ff;
        font-weight: 700;
        text-decoration: none;
    }

    .invoice-number-link:hover {
        color: #4f5bd5;
    }

    .invoice-client {
        color: var(--bs-body-color);
        font-weight: 600;
    }

    .invoice-company {
        display: inline-block;
        color: var(--bs-secondary-color);
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .invoice-total {
        font-weight: 800;
        color: var(--bs-body-color);
        white-space: nowrap;
    }

    .invoice-date {
        color: var(--bs-secondary-color);
        white-space: nowrap;
    }

    .invoice-actions {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        white-space: nowrap;
    }

    .invoice-actions .btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
    }

    .invoice-table-card .dataTables_wrapper > .d-flex:last-child {
        gap: 1rem;
        padding: 1rem 0;
        margin: 0 !important;
        border-top: 1px solid var(--bs-border-color);
    }

    .invoice-table-card .dataTables_info {
        padding: 0 !important;
        color: var(--bs-secondary-color);
    }

    .invoice-table-card .dataTables_paginate {
        padding: 0 !important;
    }

    [data-bs-theme="dark"] .invoice-table-card {
        box-shadow: none;
    }

    @media (max-width: 767.98px) {
        .invoice-filter-grid {
            grid-template-columns: 1fr;
        }

        .invoice-filter-actions {
            justify-content: stretch;
        }

        .invoice-filter-actions .btn {
            flex: 1;
        }

        .invoice-table-card .dataTables_wrapper > .d-flex:first-child,
        .invoice-table-card .dataTables_wrapper > .d-flex:last-child {
            flex-direction: column;
            align-items: stretch !important;
        }

        .invoice-table-card .dataTables_filter,
        .invoice-table-card .dataTables_filter label,
        .invoice-table-card .dataTables_filter input {
            width: 100%;
        }

        .invoice-table-card .dataTables_paginate {
            text-align: left !important;
        }
    }

    @media (min-width: 768px) and (max-width: 1199.98px) {
        .invoice-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .invoice-filter-actions {
            justify-content: flex-start;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="invoice-page">
    <div class="topbar">
        <h2><i class="bi bi-file-earmark-text me-2" style="color:#6571ff"></i><?= esc(lang('App.invoices')) ?></h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="/help" class="btn btn-outline-secondary" target="_blank">
                <i class="bi bi-question-circle me-1"></i> <?= esc(lang('App.help')) ?>
            </a>
            <a href="<?= site_url('invoices/export') . '?' . http_build_query($filters ?? []) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> <?= esc(lang('App.download_excel')) ?>
            </a>
            <a href="/invoices/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?= esc(lang('App.new_invoice')) ?></a>
        </div>
    </div>

    <div class="card invoice-filter-card">
        <div class="card-body">
            <form method="GET" action="<?= site_url('invoices') ?>" class="invoice-filter-grid">
                <div>
                    <label class="form-label" for="client"><?= esc(lang('App.client_col')) ?></label>
                    <input list="clientOptions" type="text" id="client" name="client" class="form-control" value="<?= esc($filters['client'] ?? '') ?>" placeholder="<?= esc(lang('App.all_clients')) ?>">
                    <datalist id="clientOptions">
                        <?php foreach (($clientOptions ?? []) as $client): ?>
                            <option value="<?= esc($client['client_name']) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div>
                    <label class="form-label" for="company_id"><?= esc(lang('App.company_col')) ?></label>
                    <select id="company_id" name="company_id" class="form-select">
                        <option value=""><?= esc(lang('App.all_companies')) ?></option>
                        <?php foreach (($companies ?? []) as $company): ?>
                            <option value="<?= $company['id'] ?>" <?= (string)($filters['company_id'] ?? '') === (string)$company['id'] ? 'selected' : '' ?>>
                                <?= esc($company['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" for="date_from"><?= esc(lang('App.from_date')) ?></label>
                    <input type="date" id="date_from" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
                </div>

                <div>
                    <label class="form-label" for="date_to"><?= esc(lang('App.to_date')) ?></label>
                    <input type="date" id="date_to" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
                </div>

                <div class="invoice-filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> <?= esc(lang('App.filter')) ?>
                    </button>
                    <a href="<?= site_url('invoices') ?>" class="btn btn-outline-secondary"><?= esc(lang('App.reset')) ?></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card invoice-table-card">
        <div class="card-body">
            <div class="table-wrap">
                <table id="invoicesTable" class="table table-hover dt-server invoice-table mb-0">
                    <thead>
                        <tr>
                            <th><?= esc(lang('App.invoice_number_col')) ?></th>
                            <th><?= esc(lang('App.client_col')) ?></th>
                            <th><?= esc(lang('App.company_col')) ?></th>
                            <th><?= esc(lang('App.total_col')) ?></th>
                            <th><?= esc(lang('App.status_col')) ?></th>
                            <th><?= esc(lang('App.date_col')) ?></th>
                            <th class="text-center"><?= esc(lang('App.actions_col')) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#invoicesTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[5, 'desc']],
            ajax: {
                url: '<?= site_url('invoices/datatables') ?>',
                data: function (data) {
                    data.client = $('#client').val();
                    data.company_id = $('#company_id').val();
                    data.date_from = $('#date_from').val();
                    data.date_to = $('#date_to').val();
                }
            },
            columnDefs: [
                { targets: 6, orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                search: '',
                searchPlaceholder: '<?= lang('App.search_invoices') ?>',
                lengthMenu: '<?= lang('App.pagination_show') ?>',
                info: '<?= lang('App.pagination_info') ?>',
                emptyTable: '<?= lang('App.no_invoices_found') ?>',
                zeroRecords: '<?= lang('App.no_invoices_match') ?>',
                processing: '<?= lang('App.loading_invoices') ?>',
                paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
            },
            dom: '<"dt-toolbar d-flex justify-content-between align-items-center mb-3"<"dt-length d-flex align-items-center gap-2"l><"dt-search"f>>rt<"dt-footer d-flex justify-content-between align-items-center mt-3"<"dt-info"i><"dt-pagination"p>>'
        });
    });
</script>
<?= $this->endSection() ?>
