<div class="elementor-templates-modal__header">
    <div class="elementor-templates-modal__header__logo-area">

        <div class="elementor-templates-modal__header__logo">
            <span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
                <!-- <i class="eicon-elementor-circle"></i> -->
                <img src="<?php echo ZEYNA_PLUGIN_URL . 'assets/img/zeyna_logo_white.svg'; ?>">
            </span>
            <span
                class="elementor-templates-modal__header__logo__title"><?php echo esc_html('Template Library', 'pe-core') ?></span>
        </div>

    </div>
    <div class="elementor-templates-modal__header__menu-area">

        <div id="elementor-template-library-header-menu">

            <div class="elementor-component-tab pe--library--switch elementor-template-library-menu-item elementor-active"
                data-fetch="block"><?php echo esc_html('Blocks', 'pe-core') ?></div>
            <div class="elementor-component-tab pe--library--switch elementor-template-library-menu-item" data-fetch="page">
                <?php echo esc_html('Pages', 'pe-core') ?>
            </div>

        </div>
    </div>
    
    <div class="elementor-templates-modal__header__items-area">

        <div
            class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">

            <i class="eicon-close" aria-hidden="true"></i>
            <span class="elementor-screen-only">Close</span>
        </div>

        <div id="elementor-template-library-header-tools">
            <div id="elementor-template-library-header-actions">

                <span class="ptl--version"><?php echo esc_html('Version:', 'pe-core') ?>1.0.0</span>
                <span class="ptl--updated"><?php echo esc_html('Last Update:', 'pe-core') ?>20/01/2026</span>

            </div>
        </div>
    </div>
</div>