<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Items<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    .items-page .topbar {
        gap: 1rem;
        flex-wrap: wrap;
    }

    .items-page .topbar > div {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .items-table {
        min-width: 760px;
    }

    .item-name {
        color: var(--bs-body-color);
        font-weight: 700;
        white-space: nowrap;
    }

    .item-category {
        display: inline-block;
        max-width: 240px;
        overflow: hidden;
        color: var(--bs-secondary-color);
        text-overflow: ellipsis;
        vertical-align: middle;
        white-space: nowrap;
    }

    .item-price {
        color: var(--bs-body-color);
        font-weight: 800;
        white-space: nowrap;
    }

    .item-unit {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        padding: 0.2rem 0.55rem;
        color: var(--bs-secondary-color);
        background: var(--bs-tertiary-bg);
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="items-page">
<div class="topbar">
    <h2><i class="bi bi-box me-2" style="color:#6c5ce7"></i>Items</h2>
    <div>
        <a href="/items/categories" class="btn btn-outline-secondary btn-sm"><i class="bi bi-tags me-1"></i> Categories</a>
        <a href="/items/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Item</a>
    </div>
</div>

<div class="card datatable-card">
    <div class="card-body">
        <div class="table-wrap">
            <table id="itemsTable" class="table table-hover dt-server items-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Unit</th>
                        <th class="text-center">Actions</th>
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
        $('#itemsTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[1, 'asc']],
            ajax: '<?= site_url('items/datatables') ?>',
            columnDefs: [
                { targets: 0, searchable: false },
                { targets: 5, orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search items...',
                lengthMenu: 'Show _MENU_ per page',
                info: 'Showing _START_ to _END_ of _TOTAL_',
                emptyTable: 'No items found',
                zeroRecords: 'No items match your search',
                processing: 'Loading items...',
                paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
            },
            dom: '<"dt-toolbar d-flex justify-content-between align-items-center mb-3"<"dt-length d-flex align-items-center gap-2"l><"dt-search"f>>rt<"dt-footer d-flex justify-content-between align-items-center mt-3"<"dt-info"i><"dt-pagination"p>>'
        });
    });
</script>
<?= $this->endSection() ?>
