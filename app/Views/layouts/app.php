<?php
$settingModel = new \App\Models\SettingModel();
$appName = $settingModel->getSetting('app_name', 'InvoiceApp');
$appLogo = $settingModel->getSetting('app_logo', '');
$footerText = $settingModel->getSetting('footer_text', 'Powered by CodeIgniter 4');
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - <?= esc($appName) ?></title>

    <!-- Theme init (prevent flash) -->
    <script>
        (function(){
            var t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="/assets/vendors/core/core.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="/assets/css/demo1/style.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <style>
        html, body { height: 100%; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .main-wrapper { display: flex; flex: 1; }
        .page-wrapper { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .content-wrapper { padding: 1.5rem; flex: 1; }
        .page-header { padding: 1rem 1.5rem; background: var(--bs-body-bg); border-bottom: 1px solid var(--bs-border-color); display: flex; justify-content: space-between; align-items: center; }
        .page-header h4 { font-weight: 600; margin: 0; font-size: 1.1rem; }
        .footer-wrapper { padding: 0.8rem 1.5rem; border-top: 1px solid var(--bs-border-color); background: var(--bs-body-bg); text-align: center; }
        .footer-section { font-size: 0.8rem; color: var(--bs-secondary-color); }
        .sidebar .nav-link.active { background: rgba(101, 113, 255, 0.12); color: #6571ff; border-radius: 6px; font-weight: 500; }
        .sidebar .nav-link:hover { background: rgba(101, 113, 255, 0.08); border-radius: 6px; }
        .stat-card { border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 1.25rem; background: var(--bs-body-bg); }
        .stat-card .stat-number { font-size: 1.8rem; font-weight: 700; margin: 0.5rem 0 0.2rem; }
        .stat-card .stat-label { font-size: 0.8rem; opacity: 0.7; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .badge { font-size: 0.72rem; padding: 4px 10px; border-radius: 6px; font-weight: 500; }
        .badge-draft { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
        .badge-sent { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .badge-paid { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .badge-unpaid { background: rgba(234, 179, 8, 0.15); color: #facc15; }
        .badge-cancelled { background: rgba(107, 114, 128, 0.15); color: #9ca3af; }
        .card { border: 1px solid var(--bs-border-color); border-radius: 12px; }
        .card-body { padding: 1.25rem; }
        .table th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        .table-hover tbody tr:hover { background-color: var(--bs-tertiary-bg); }
        .theme-toggle { cursor: pointer; background: none; border: 1px solid var(--bs-border-color); border-radius: 8px; padding: 6px 10px; color: var(--bs-body-color); display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; transition: all 0.2s; }
        .theme-toggle:hover { border-color: var(--bs-primary); color: var(--bs-primary); }
        .sidebar-brand { display: inline-flex; align-items: center; gap: 0.6rem; min-width: 0; }
        .sidebar-brand-logo { width: 30px; height: 30px; object-fit: contain; flex: 0 0 auto; border-radius: 6px; }
        .sidebar-brand-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .page-title-brand { display: inline-flex; align-items: center; gap: 0.6rem; min-width: 0; }
        .page-title-logo { width: 26px; height: 26px; object-fit: contain; border-radius: 6px; }

        /* ===== LIGHT THEME FIXES ===== */
        [data-bs-theme="light"] .card { background: var(--bs-body-bg); }
        [data-bs-theme="light"] .card-header { background: var(--bs-tertiary-bg); border-bottom: 1px solid var(--bs-border-color); color: var(--bs-body-color); }
        [data-bs-theme="light"] .card-footer { background: var(--bs-tertiary-bg); border-top: 1px solid var(--bs-border-color); }
        [data-bs-theme="light"] .list-group-item { background: var(--bs-body-bg); border-color: var(--bs-border-color); color: var(--bs-body-color); }
        [data-bs-theme="light"] .table-hover tbody tr:hover { background-color: rgba(99, 102, 241, 0.04); }
        [data-bs-theme="light"] .badge-draft { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
        [data-bs-theme="light"] .badge-sent { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
        [data-bs-theme="light"] .badge-paid { background: rgba(34, 197, 94, 0.12); color: #16a34a; }
        [data-bs-theme="light"] .badge-unpaid { background: rgba(234, 179, 8, 0.12); color: #ca8a04; }
        [data-bs-theme="light"] .badge-cancelled { background: rgba(107, 114, 128, 0.12); color: #4b5563; }
        [data-bs-theme="light"] .stat-icon { background: var(--bs-tertiary-bg); }
        [data-bs-theme="light"] .form-control, [data-bs-theme="light"] .form-select { background: var(--bs-body-bg); border-color: var(--bs-border-color); color: var(--bs-body-color); }
        [data-bs-theme="light"] .form-control:focus, [data-bs-theme="light"] .form-select:focus { border-color: var(--bs-primary); box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15); }
        [data-bs-theme="light"] .form-label { color: var(--bs-body-color); }
        [data-bs-theme="light"] .text-muted, [data-bs-theme="light"] .footer-section { color: var(--bs-secondary-color) !important; }
        [data-bs-theme="light"] .sidebar .nav-link { color: var(--bs-secondary-color); }
        [data-bs-theme="light"] .sidebar .nav-link.active { color: #6571ff; background: rgba(101, 113, 255, 0.1); border-radius: 6px; }
        [data-bs-theme="light"] .btn-outline-secondary { color: var(--bs-secondary-color); border-color: var(--bs-border-color); }
        [data-bs-theme="light"] .btn-outline-secondary:hover { background: var(--bs-secondary-bg-subtle); color: var(--bs-body-color); }

        /* ===== DATA TABLES ===== */
                .dataTables_wrapper { padding: 0; width: 100%; }
                .dataTables_wrapper .dataTables_length { display: flex; align-items: center; gap: 0.5rem; }
                .dataTables_wrapper .dataTables_length label { margin: 0; font-size: 0.85rem; white-space: nowrap; }
                .dataTables_wrapper .dataTables_filter { display: flex; align-items: center; justify-content: flex-end; }
                .dataTables_wrapper .dataTables_filter label { margin: 0; }
                .dataTables_wrapper .dataTables_filter input { min-width: 200px; }
                .dataTables_wrapper .dataTables_info { font-size: 0.82rem; }
                .dataTables_wrapper .dataTables_paginate { text-align: right !important; }
                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    padding: 0.35rem 0.75rem !important;
                    margin: 0 2px !important;
                    border-radius: 6px !important;
                    border: 1px solid var(--bs-border-color) !important;
                    background: var(--bs-body-bg) !important;
                    color: var(--bs-body-color) !important;
                    font-size: 0.82rem;
                    transition: all 0.15s;
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    min-width: 32px;
                    height: 32px;
                }
                .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                    background: rgba(99, 102, 241, 0.1) !important;
                    border-color: var(--bs-primary) !important;
                    color: var(--bs-primary) !important;
                }
                .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                    background: var(--bs-primary) !important;
                    border-color: var(--bs-primary) !important;
                    color: #fff !important;
                    font-weight: 600;
                }
                .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                    opacity: 0.4;
                    cursor: not-allowed;
                }
                .dataTables_wrapper .dataTables_filter input {
                    background: var(--bs-body-bg);
                    border: 1px solid var(--bs-border-color);
                    border-radius: 8px;
                    padding: 0.35rem 0.75rem;
                    color: var(--bs-body-color);
                    font-size: 0.85rem;
                    width: 220px;
                }
                .dataTables_wrapper .dataTables_filter input:focus {
                    border-color: var(--bs-primary);
                    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
                    outline: none;
                }
                .dataTables_wrapper .dataTables_length select {
                    background: var(--bs-body-bg);
                    border: 1px solid var(--bs-border-color);
                    border-radius: 6px;
                    color: var(--bs-body-color);
                    font-size: 0.85rem;
                    padding: 0.3rem 0.5rem;
                    min-width: 80px;
                }
                div.dataTables_wrapper div.dataTables_paginate ul.pagination { margin: 0 !important; padding: 0; display: flex; gap: 2px; }

                .datatable-card {
                    overflow: hidden;
                    border-radius: 10px;
                }
                [data-bs-theme="light"] .datatable-card {
                    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
                }
                .datatable-card .card-body {
                    padding: 0;
                }
                .datatable-card .dataTables_wrapper {
                    padding: 1rem 1rem 0;
                }
                .datatable-card .dt-toolbar {
                    gap: 1rem;
                    padding: 0 0 1rem;
                    margin: 0 !important;
                    border-bottom: 1px solid var(--bs-border-color);
                }
                .datatable-card .dataTables_length label,
                .datatable-card .dataTables_filter label {
                    display: flex;
                    align-items: center;
                    gap: 0.55rem;
                    margin: 0;
                    color: var(--bs-secondary-color);
                    font-size: 0.82rem;
                    white-space: nowrap;
                }
                .datatable-card .dataTables_length select {
                    width: auto;
                    min-width: 74px;
                    height: 36px;
                    display: inline-block;
                    padding: 0.35rem 2rem 0.35rem 0.75rem;
                    border-radius: 8px;
                }
                .datatable-card .dataTables_filter input {
                    width: min(280px, 42vw);
                    height: 36px;
                    margin: 0;
                    border-radius: 8px;
                }
                .datatable-card .table-wrap {
                    width: 100%;
                    overflow-x: auto;
                }
                .datatable-card table.dataTable {
                    width: 100% !important;
                    margin: 0 !important;
                    border-collapse: separate !important;
                    border-spacing: 0;
                }
                .datatable-card table.dataTable thead th {
                    padding: 0.9rem 1rem;
                    color: var(--bs-secondary-color);
                    background: var(--bs-tertiary-bg);
                    border-bottom: 1px solid var(--bs-border-color);
                    font-size: 0.72rem;
                    font-weight: 700;
                    vertical-align: middle;
                }
                .datatable-card table.dataTable tbody td {
                    padding: 0.95rem 1rem;
                    border-bottom: 1px solid var(--bs-border-color);
                    vertical-align: middle;
                }
                .datatable-card table.dataTable tbody tr:last-child td {
                    border-bottom: 0;
                }
                .datatable-card .dt-footer {
                    gap: 1rem;
                    padding: 1rem 0;
                    margin: 0 !important;
                    border-top: 1px solid var(--bs-border-color);
                }
                .datatable-card .dataTables_info,
                .datatable-card .dataTables_paginate {
                    padding: 0 !important;
                }
                .datatable-card .dataTables_info {
                    color: var(--bs-secondary-color);
                }
                .datatable-actions {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.45rem;
                    white-space: nowrap;
                }
                .datatable-actions .btn {
                    width: 36px;
                    height: 36px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0;
                    border-radius: 8px;
                }

                @media (max-width: 767.98px) {
                    .datatable-card .dt-toolbar,
                    .datatable-card .dt-footer {
                        flex-direction: column;
                        align-items: stretch !important;
                    }
                    .datatable-card .dataTables_filter,
                    .datatable-card .dataTables_filter label,
                    .datatable-card .dataTables_filter input {
                        width: 100%;
                    }
                    .datatable-card .dataTables_paginate {
                        text-align: left !important;
                    }
                }
        
        /* ===== CONSISTENT SPACING ===== */
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .topbar h2 { font-size: 1.25rem; font-weight: 600; margin: 0; }
        .page-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .page-section-header h5 { margin: 0; }
        .card .card-body.p-0 .table { margin-bottom: 0; }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="/dashboard" class="sidebar-brand">
                    <?php if (!empty($appLogo)): ?>
                        <img src="<?= esc($appLogo) ?>" alt="<?= esc($appName) ?> logo" class="sidebar-brand-logo">
                    <?php endif; ?>
                    <span class="sidebar-brand-text"><?= esc($appName) ?></span>
                </a>
                <div class="sidebar-toggler">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="sidebar-body">
                <ul class="nav" id="sidebarNav">
                    <li class="nav-item nav-category">Main</li>
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                            <i class="link-icon bi bi-speedometer2"></i>
                            <span class="link-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Business</li>
                    <li class="nav-item">
                        <a href="/companies" class="nav-link <?= str_starts_with(uri_string(), 'companies') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-building"></i>
                            <span class="link-title">Companies</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/items/categories" class="nav-link <?= str_starts_with(uri_string(), 'items/categories') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-tags"></i>
                            <span class="link-title">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/items" class="nav-link <?= str_starts_with(uri_string(), 'items') && !str_starts_with(uri_string(), 'items/categories') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-box"></i>
                            <span class="link-title">Items</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/invoices" class="nav-link <?= str_starts_with(uri_string(), 'invoices') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-file-earmark-text"></i>
                            <span class="link-title">Invoices</span>
                        </a>
                    </li>
                    <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item nav-category">Admin</li>
                    <li class="nav-item">
                        <a href="/admin" class="nav-link <?= uri_string() === 'admin' ? 'active' : '' ?>">
                            <i class="link-icon bi bi-shield-lock"></i>
                            <span class="link-title">Admin Panel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/settings" class="nav-link <?= str_starts_with(uri_string(), 'admin/settings') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-gear"></i>
                            <span class="link-title">App Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/invoice-config" class="nav-link <?= str_starts_with(uri_string(), 'admin/invoice-config') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-sliders"></i>
                            <span class="link-title">Invoice Config</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/auth-settings" class="nav-link <?= str_starts_with(uri_string(), 'admin/auth-settings') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-shield-check"></i>
                            <span class="link-title">Auth Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/email-config" class="nav-link <?= str_starts_with(uri_string(), 'admin/email-config') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-envelope-fill"></i>
                            <span class="link-title">Email Config</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/pages" class="nav-link <?= str_starts_with(uri_string(), 'admin/pages') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-file-earmark-richtext"></i>
                            <span class="link-title">Pages</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item nav-category">Account</li>
                    <li class="nav-item">
                        <a href="/account" class="nav-link <?= str_starts_with(uri_string(), 'account') ? 'active' : '' ?>">
                            <i class="link-icon bi bi-person-gear"></i>
                            <span class="link-title">Account</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">
                            <i class="link-icon bi bi-box-arrow-left"></i>
                            <span class="link-title">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content -->
        <div class="page-wrapper">
            <!-- Navbar -->
            <div class="page-header">
                <h4 class="page-title-brand">
                    <?php if (!empty($appLogo)): ?>
                        <img src="<?= esc($appLogo) ?>" alt="<?= esc($appName) ?> logo" class="page-title-logo">
                    <?php endif; ?>
                    <span><?= $this->renderSection('title') ?></span>
                </h4>
                <div class="d-flex align-items-center gap-3">
                    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                        <i class="bi bi-sun" id="themeIcon"></i>
                        <span id="themeLabel">Light</span>
                    </button>
                    <span style="font-size:0.85rem;">
                        <i class="bi bi-person-circle me-1"></i><?= esc(session()->get('user_name') ?? '') ?>
                    </span>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
                    <i class="bi bi-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="content-wrapper">
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Footer -->
            <div class="footer-wrapper">
                <div class="footer-section">
                    <?= esc($footerText) ?> &mdash; &copy; <?= date('Y') ?> <?= esc($appName) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="/assets/vendors/core/core.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- App JS -->
    <script src="/assets/js/color-modes.js"></script>
    <script src="/assets/js/app.js"></script>

    <script>
        // DataTables initialization for all tables
        $(document).ready(function() {
            $.fn.dataTable.ext.classes.sWrapper = 'dataTables_wrapper';
            $.fn.dataTable.ext.classes.sFilterInput = 'form-control form-control-sm';
            $.fn.dataTable.ext.classes.sLengthSelect = 'form-select form-select-sm';

            $('table.dt-table:not(.dt-server)').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: true,
                searching: true,
                language: {
                    search: '',
                    searchPlaceholder: 'Search...',
                    lengthMenu: 'Show _MENU_ per page',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    emptyTable: 'No data available',
                    paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
                },
                dom: '<"dt-toolbar d-flex justify-content-between align-items-center mb-3"<"dt-length d-flex align-items-center gap-2"l><"dt-search"f>>rt<"dt-footer d-flex justify-content-between align-items-center mt-3"<"dt-info"i><"dt-pagination"p>>'
            });

            // SweetAlert confirm for all delete forms
            $(document).on('submit', 'form[data-confirm]', function(e) {
                e.preventDefault();
                var form = this;
                var message = form.getAttribute('data-confirm') || 'This action cannot be undone.';
                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <script>
        // Theme toggle
        function toggleTheme() {
            var html = document.documentElement;
            var current = html.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
            updateThemeUI(next);
        }

        function updateThemeUI(theme) {
            var icon = document.getElementById('themeIcon');
            var label = document.getElementById('themeLabel');
            if (theme === 'dark') {
                icon.className = 'bi bi-moon';
                label.textContent = 'Dark';
            } else {
                icon.className = 'bi bi-sun';
                label.textContent = 'Light';
            }
        }

        // Init on load
        (function(){
            var t = localStorage.getItem('theme') || 'light';
            updateThemeUI(t);
        })();
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
