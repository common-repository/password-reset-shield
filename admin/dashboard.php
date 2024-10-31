<div class="wrap" id="wps-prs-settings">
  <h1><img src="<?php echo WPS_PRS_URI; ?>assets/icon.png" />Password Reset Shield</h1>

    <form method="POST" action="<?php echo admin_url('admin.php?page=wps_prs_dashboard'); ?>">

      <?php wp_nonce_field('save_dashboard', 'wps_prs_dashboard'); ?>
      <?php include WPS_PRS_DIR . 'admin/parts/options.php'; ?>

    </form>

</div>