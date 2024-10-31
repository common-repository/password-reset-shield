<table class="form-table">

  <?php
  $option = get_option('wps_prs');
  $enable_reset = esc_attr($option['enable_reset']);
  $gather_details = esc_attr($option['gather_details']);

  $enabled_reset = '';
  $disabled_reset = '';

  if ($enable_reset == 'enabled') {
    $enabled_reset = 'selected="selected"';
  } else {
    $disabled_reset = 'selected="selected"';
  }

  $enabled_gather_details = '';
  $disabled_gather_details = '';

  if ($gather_details == 'enabled') {
    $enabled_gather_details = 'selected="selected"';
  } else {
    $disabled_gather_details = 'selected="selected"';
  }

  ?>

  <tbody>
  <tr>
    <th>Password reset option:</th>
    <td>
      <select name="wps_prs[enable_reset]">
        <option value="enabled" <?php echo $enabled_reset;?>>Enabled</option>
        <option value="disabled" <?php echo $disabled_reset;?>>Disabled</option>
      </select>
    </td>
  </tr>
  <tr>
    <th>Gather password reset request details:</th>
    <td>
      <select name="wps_prs[gather_details]">
        <option value="enabled" <?php echo $enabled_gather_details;?>>Enabled</option>
        <option value="disabled" <?php echo $disabled_gather_details;?>>Disabled</option>
      </select><br/>
      <span class="description">Gathers all important information about password<br/>reset requet such as IP, time, browser...</span>
    </td>
  </tr>
  <tr>
    <th><input type="submit" class="button button-primary" value="Save"/></th>
  </tr>
  </tbody>

</table>