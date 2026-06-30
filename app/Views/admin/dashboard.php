<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Admin Panel<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    .admin-users-table {
        min-width: 980px;
    }

    .admin-user-name {
        color: var(--bs-body-color);
        font-weight: 700;
        white-space: nowrap;
    }

    .admin-user-email {
        display: inline-block;
        max-width: 260px;
        overflow: hidden;
        color: var(--bs-secondary-color);
        text-overflow: ellipsis;
        vertical-align: middle;
        white-space: nowrap;
    }

    .admin-user-count {
        font-weight: 700;
        color: var(--bs-body-color);
    }

    .admin-user-limits {
        color: var(--bs-secondary-color);
        font-size: 0.84rem;
        white-space: nowrap;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 62px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 72px;
        border-radius: 999px;
        font-weight: 700;
    }

    .status-badge-active {
        background: rgba(34, 197, 94, 0.16);
        color: #22c55e;
    }

    .status-badge-pending {
        background: rgba(234, 179, 8, 0.18);
        color: #eab308;
    }

    .status-badge-blocked {
        background: rgba(239, 68, 68, 0.16);
        color: #ef4444;
    }

    [data-bs-theme="light"] .role-badge-admin {
        background: rgba(101, 113, 255, 0.12) !important;
        color: #4f46e5 !important;
    }

    [data-bs-theme="light"] .role-badge-user {
        background: rgba(107, 114, 128, 0.12) !important;
        color: #4b5563 !important;
    }

    [data-bs-theme="light"] .status-badge-active {
        background: rgba(22, 163, 74, 0.12);
        color: #15803d;
    }

    [data-bs-theme="light"] .status-badge-pending {
        background: rgba(202, 138, 4, 0.14);
        color: #a16207;
    }

    [data-bs-theme="light"] .status-badge-blocked {
        background: rgba(220, 38, 38, 0.12);
        color: #b91c1c;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-shield-lock me-2" style="color:#6c5ce7"></i>Admin Panel</h2>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(108,92,231,0.15);color:#6c5ce7;"><i class="bi bi-people"></i></div>
            <div class="stat-number" style="color:#6c5ce7"><?= $total_users ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(253,121,168,0.15);color:#fd79a8;"><i class="bi bi-building"></i></div>
            <div class="stat-number" style="color:#fd79a8"><?= $total_companies ?></div>
            <div class="stat-label">Total Companies</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(0,206,209,0.15);color:#00ced1;"><i class="bi bi-file-earmark-text"></i></div>
            <div class="stat-number" style="color:#00ced1"><?= $total_invoices ?></div>
            <div class="stat-label">Total Invoices</div>
        </div>
    </div>
</div>

<div class="card datatable-card">
    <div class="card-header">User Management</div>
    <div class="card-body">
        <div class="table-wrap">
            <table id="adminUsersTable" class="table table-hover dt-server admin-users-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Companies</th>
                        <th>Invoices</th>
                        <th>Limits</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#adminUsersTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'desc']],
            ajax: '<?= site_url('admin/users/datatables') ?>',
            columnDefs: [
                { targets: 0, searchable: false },
                { targets: [5, 6, 7], searchable: false },
                { targets: 8, orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search users...',
                lengthMenu: 'Show _MENU_ per page',
                info: 'Showing _START_ to _END_ of _TOTAL_',
                emptyTable: 'No users found',
                zeroRecords: 'No users match your search',
                processing: 'Loading users...',
                paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
            },
            dom: '<"dt-toolbar d-flex justify-content-between align-items-center mb-3"<"dt-length d-flex align-items-center gap-2"l><"dt-search"f>>rt<"dt-footer d-flex justify-content-between align-items-center mt-3"<"dt-info"i><"dt-pagination"p>>'
        });
    });
</script>
<?= $this->endSection() ?>
