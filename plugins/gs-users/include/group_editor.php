<?php if(isset($_GET['group'])) {$group_details = ($GSUsers->getGroupDetails($_GET['group']) ?: array());}
$GROUPNAME = @($group_details['GROUPNAME'] ?: '');
$GROUPSLUG = @($group_details['GROUPSLUG'] ?: '');
$LANDINGPAGE = @($group_details['LANDINGPAGE'] ?: '');
?>

<h3>Usergroup Editor</h3>

<form class="largeform" action="load.php?id=<?php echo USRMGMT; ?>&action=save-group" method="post">
  
  <h4>Group Information</h4>
  
  <!-- # Username # -->
  <div class="leftsec">
    <p>
      <label for="groupname">Group Name:</label>
      <span class="info-hint">Insert hint information here...</span>
      <input class="text" type="text" name="groupname" value="<?php echo ucwords(str_replace('-',' ',$GROUPNAME)); ?>" />
    </p>
  </div>
  
  <!-- # Landing Page # -->
  <div class="rightsec">
    <p>
      <label for="landingpage">Admin landing page:</label>
      <span class="info-hint">Insert hint information here...</span>
      <select class="text" name="landingpage">
        <optgroup label="Core Pages">
        <?php foreach($GSUsers->generatePagesList('core') as $value => $name) {
          if($value == $LANDINGPAGE) {$selected = ' selected="selected"';} else {$selected='';}
          echo '<option value="'.$value.'"'.$selected.'>'.$name.'</option>'; } ?>
        </optgroup>
        <optgroup label="Plugins">
        <?php foreach($GSUsers->generatePagesList('plugins') as $value => $name) {
          if($value == $LANDINGPAGE) {$selected = ' selected="selected"';} else {$selected='';}
          echo '<option value="'.$value.'"'.$selected.'>'.$name.'</option>'; } ?>
        </optgroup>
      </select>
    </p>
  </div>
  
  <div class="clear"></div>
  
  <h4>Group Permissions <small>[ Tick to enable ]</small></h4>
  
  <div class="permsec-container">
  
  <?php if(!empty($permissions_array)) {
    if(isset($_GET['group']) && $GSUsers->getGroupDetails($_GET['group'])) {
      $permitted = $GSUsers->getGroupPermissions($_GET['group']);
    } else {$permitted = array();}
    foreach($permissions_array as $plugin_id => $permissions) {
    if($plugin_id == 'getsimple-core') { ?>
    
    <div class="permsec">
      <div class="permgroup">
        <h6>GetSimple Core</h6>
        <p>These are the permissions for the core admin pages and tabs of GetSimple CMS.</p>
        <ul class="permlist">
          
          <?php foreach($permissions as $perm_id => $permission) {
            $checked = (isset($permitted[$perm_id]) && $permitted[$perm_id] == true ? ' checked="checked"' : '');?>
            
          <li>
            <input type="checkbox" name="perm-<?php echo $perm_id; ?>" value="true"<?php echo $checked;?> />
            <label for="perm-<?php echo $perm_id; ?>"><?php echo $permission['name']; ?></label>
            <i class="fa fa-info-circle" title="<?php echo $permission['desc']; ?>"></i>
            <?php if(array_key_exists('child_perms', $permission)) { ?>
            <ul>
              <?php foreach($permission['child_perms'] as $cpermid => $cpermdesc) {
                $cchecked = (isset($permitted[$cpermid]) && $permitted[$cpermid] == true ? 'checked="checked"' : '');?>
              <li>
                <input type="checkbox" name="perm-<?php echo $cpermid; ?>" value="true"<?php echo $cchecked; ?> />
                <label for="perm-<?php echo $cpermid; ?>"><?php echo $cpermdesc; ?></label>
              </li>
              <?php } ?>
            </ul>
            <?php } ?>
          </li>
            
          <?php } ?>
        </ul>
      </div>
    </div>
  
    <?php } else { ?>
    
    <div class="permsec">
      <div class="permgroup">
        <h6><?php echo $plugin_info[$plugin_id]['name']; ?></h6>
        <p><?php echo $plugin_info[$plugin_id]['description']; ?></p>
        <ul class="permlist">
          
          <?php foreach($permissions as $perm_id => $permission) {
            $checked = (isset($permitted[$plugin_id.'-'.$perm_id]) && $permitted[$plugin_id.'-'.$perm_id] == true ? ' checked="checked"' : '');?>
            
          <li>
            <input type="checkbox" name="perm-<?php echo $plugin_id.'-'.$perm_id; ?>" value="true"<?php echo $checked;?> />
            <label for="perm-<?php echo $plugin_id.'-'.$perm_id; ?>"><?php echo $permission['name']; ?></label>
            <i class="fa fa-info-circle" title="<?php echo $permission['desc']; ?>"></i>
            <?php if(array_key_exists('child_perms', $permission)) { ?>
            <ul>
              <?php foreach($permission['child_perms'] as $cpermid => $cpermdesc) {
                $cchecked = (isset($permitted[$plugin_id.'-'.$cpermid]) && $permitted[$plugin_id.'-'.$cpermid] == true ? 'checked="checked"' : '');?>
              <li>
                <input type="checkbox" name="perm-<?php echo $plugin_id.'-'.$cpermid; ?>" value="true"<?php echo $cchecked; ?> />
                <label for="perm-<?php echo $plugin_id.'-'.$cpermid; ?>"><?php echo $cpermdesc; ?></label>
              </li>
              <?php } ?>
            </ul>
            <?php } ?>
          </li>
            
          <?php } ?>
          
        </ul>
      </div>
    </div>
    
    <?php }}} ?>
  
    <div class="clear"></div>
  
  </div>
  
  <h4>Group Members</h4>
  
  <table class="highlight">
    <thead>
      <tr>
        <th>Username</th>
        <th style="width:320px;text-align:left;">Display Name</th>
        <th><i class="fa fa-pencil-square-o"></i></th>
        <th><i class="fa fa-chain-broken"></i></th>
      </tr>
    </thead>
    
    <tbody>
      <tr>
        <td style="padding-left:10px;"><a href="load.php?id=<?php echo USRMGMT; ?>" title="Modify User: admin">admin</a></th>
        <td>Administrator</td>
        <td class="edit"><a href="#" title="Edit User: admin"><i class="fa fa-pencil-square-o"></i></a></td>
        <td class="delete"><a href="load.php?id=<?php echo USRMGMT; ?>" title="Remove user from this group"><i class="fa fa-chain-broken"></i></a></td>
      </tr>
    </tbody>
    
  </table>
  
  <div style="margin-top:20px;">
    <input class="submit" type="submit" value="Save Group" />
    &nbsp;&nbsp;Or&nbsp;&nbsp;
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=view-groups" class="cancel">Cancel</a>
  </div>
</form>

<script>
$(".submit").on("click", function() {
  $('input[type="checkbox"]').each(function(index) {
    if($(this).prop('indeterminate') == true) {
      $(this).prop('checked', true);
    }
  });
});
$(document).ready(function() {
  $('.permsec-container').masonry({
    itemSelector: '.permsec',
    columnWidth: '.permsec'
  });
});
</script>
