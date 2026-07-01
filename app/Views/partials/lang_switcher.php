<?php
    $labels = \App\Filters\LocaleFilter::LOCALE_LABELS;
    $flags  = [
        'en' => 'gb',
        'id' => 'id',
        'ms' => 'my',
        'zh' => 'cn',
        'vi' => 'vn',
        'ar' => 'sa',
        'es' => 'es',
        'fr' => 'fr',
        'hi' => 'in',
    ];
    $current = service('request')->getLocale();
    if (!isset($labels[$current])) $current = 'en';
?>
<span class="lang-label"><?= esc(lang('Common.language')) ?></span>
<div class="lang-dropdown">
    <button class="lang-dropdown-btn" type="button" onclick="this.parentElement.classList.toggle('open')">
        <img src="https://flagcdn.com/24x18/<?= $flags[$current] ?>.png"
             srcset="https://flagcdn.com/48x36/<?= $flags[$current] ?>.png 2x"
             width="24" height="18" alt="" class="lang-flag">
        <?= esc($labels[$current]) ?>
    </button>
    <div class="lang-dropdown-menu">
        <?php foreach ($labels as $code => $label): ?>
            <a class="lang-dropdown-item <?= $code === $current ? 'active' : '' ?>"
               href="<?= site_url('language/' . $code . '?r=' . urlencode(uri_string())) ?>">
                <img src="https://flagcdn.com/24x18/<?= $flags[$code] ?>.png"
                     srcset="https://flagcdn.com/48x36/<?= $flags[$code] ?>.png 2x"
                     width="24" height="18" alt="" class="lang-flag">
                <?= esc($label) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
