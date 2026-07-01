<style>
    :root {
        --inv-brand: #1b3a5c;
        --inv-brand-dark: #0f2539;
        --inv-brand-gold: #c9a84c;
        --inv-ink: #1a1a2e;
        --inv-muted: #555;
        --inv-bg: #f5f6fa;
        --inv-bg-soft: #f9fafb;
        --inv-border: #e5e7eb;
        --inv-draft-bg: #e8ecf4;
        --inv-draft-fg: var(--inv-brand);
        --inv-sent-bg: #eff6ff;
        --inv-sent-fg: #2563eb;
        --inv-paid-bg: #f0fdf4;
        --inv-paid-fg: #16a34a;
        --inv-unpaid-bg: #fefce8;
        --inv-unpaid-fg: #ca8a04;
        --inv-cancelled-bg: #f3f4f6;
        --inv-cancelled-fg: #6b7280;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        color: var(--inv-ink);
        background: var(--inv-bg);
        padding: 40px;
    }

    .invoice-box {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 50px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--inv-brand);
    }

    .company-info h2 {
        font-size: 1.5rem;
        color: var(--inv-ink);
        margin-bottom: 5px;
    }

    .company-info p {
        color: var(--inv-muted);
        font-size: 0.9rem;
        margin: 2px 0;
    }

    .invoice-title { text-align: right; }

    .invoice-title h1 {
        font-size: 2rem;
        color: var(--inv-brand);
        margin: 0;
    }

    .invoice-title .inv-number {
        font-size: 1rem;
        color: var(--inv-muted);
        margin-top: 5px;
    }

    .invoice-title .inv-date {
        font-size: 0.85rem;
        color: #777;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-draft    { background: var(--inv-draft-bg);    color: var(--inv-draft-fg); }
    .status-sent     { background: var(--inv-sent-bg);     color: var(--inv-sent-fg); }
    .status-paid     { background: var(--inv-paid-bg);     color: var(--inv-paid-fg); }
    .status-unpaid   { background: var(--inv-unpaid-bg);   color: var(--inv-unpaid-fg); }
    .status-cancelled{ background: var(--inv-cancelled-bg);color: var(--inv-cancelled-fg); }

    .parties {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .party-box { width: 48%; }

    .party-box h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--inv-brand);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .party-box p {
        font-size: 0.9rem;
        color: #333;
        margin: 2px 0;
    }

    .party-box .name { font-weight: 600; font-size: 1rem; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    thead th {
        background: var(--inv-brand);
        color: #fff;
        padding: 10px 12px;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    thead th:first-child { border-radius: 6px 0 0 0; }
    thead th:last-child  { border-radius: 0 6px 0 0; text-align: right; }

    tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid var(--inv-border);
        font-size: 0.9rem;
    }

    tbody td:last-child { text-align: right; font-weight: 500; }
    tbody tr:hover { background: #fafafa; }

    .col-num   { width: 5%; }
    .col-desc  { width: 50%; }
    .col-qty   { width: 12%; text-align: center; }
    .col-price { width: 15%; text-align: right; }
    .col-total { width: 18%; text-align: right; }

    .totals { display: flex; justify-content: flex-end; margin-bottom: 30px; }

    .totals-box { width: 300px; }

    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 0.9rem;
    }

    .totals-row.total {
        border-top: 2px solid var(--inv-ink);
        padding-top: 10px;
        margin-top: 5px;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .notes {
        background: var(--inv-bg-soft);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 30px;
    }

    .notes h5 {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: var(--inv-brand);
        margin-bottom: 5px;
    }

    .notes p { color: var(--inv-muted); font-size: 0.9rem; }

    .footer {
        text-align: center;
        color: #aaa;
        font-size: 0.75rem;
        padding-top: 20px;
        border-top: 1px solid var(--inv-border);
    }

    .top-toolbar { text-align: right; margin-bottom: 16px; }

    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dc2626;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-pdf:hover { background: #b91c1c; color: #fff; }

    @media print {
        body { background: #fff; padding: 0; }
        .invoice-box { box-shadow: none; border-radius: 0; padding: 20px; }
    }

    @media (max-width: 600px) {
        body { padding: 15px; }
        .invoice-box { padding: 25px; }
        .header { flex-direction: column; gap: 20px; }
        .invoice-title { text-align: left; }
        .parties { flex-direction: column; gap: 20px; }
        .party-box { width: 100%; }
    }
</style>
