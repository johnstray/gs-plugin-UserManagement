<?php $all_groups = $GSUsers->getAllGroups(); ob_start(); ?>
<h3 class="floated" style="float:left;"><?php i18n(USRMGMT.'/SETTINGS_MANAGEMENT'); ?></h3>

<div class="edit-nav">
  <p class="text 1">
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=view-groups"><?php i18n(USRMGMT.'/BTN_MANAGE_GROUPS'); ?></a>
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=view-users"><?php i18n(USRMGMT.'/BTN_MANAGE_USERS'); ?></a>
  </p>
  <div class="clear"></div>
</div>

<form class="largeform" action="load.php?id=<?php echo USRMGMT; ?>&action=save-settings" method="post" accept-charset="utf-8">
  
  <input type="hidden" name="nonce" value="<?php echo get_nonce("users_savesettings"); ?>" />
  
  <div class="leftsec">
    
    <p><!-- # Administrator Login Only # -->
      <label for="adminonly">Administrator Login Only <span class="right help"><a href="#" target="_blank" title="Click for more information about this setting.">?</a></span></label>
      <span class="info-hint">Restrict login to users in the Administrators group only</span>
      <select class="text" name="adminonly">
        <?php if($GSUsers->getSettings('adminonly') == 'TRUE') { ?>
        <option value="TRUE" selected="selected">Enabled</option>
        <option value="FALSE">Disabled</option>
        <?php } else { ?>
        <option value="TRUE">Enabled</option>
        <option value="FALSE" selected="selected">Disabled</option>
        <?php } ?>
      </select>
    </p>

    <p><!-- # Administrator Login Only # -->
<label for="defaultgroup">Default Group Membership <span class="right help"><a href="#" target="_blank" title="Click for more information about this setting.">?</a></span></label>
        <span class="info-hint">For users that have no group or assigned to deleted group</span>
        <select class="text" name="defaultgroup">
<?php foreach ($all_groups as $groupslug => $groupname) {
    $thisGroup = $GSUsers->getGroupDetails($groupslug);
    var_dump($thisGroup);
    if ($thisGroup['GROUPSLUG'] == $GSUsers->getSettings('defaultgroup')) {$selected = ' selected="selected"';} else {$selected='';}
    echo '<option value="'.$thisGroup['GROUPSLUG'].'"'.$selected.'>'.$thisGroup['GROUPNAME'].'</option>';
} ?>
        </select>
    </p>
    
    <p><!-- # Login Screen Message # -->
      <label for="loginmsg">Login Screen Messaage <span class="right help"><a href="#" target="_blank" title="Click for more information about this setting.">?</a></span></label>
      <span class="info-hint">Type a custom message to display on the login screen</span>
      <textarea class="text" name="loginmsg"><?php echo $GSUsers->getSettings('loginmsg'); ?></textarea>
    </p>
    
  </div>
  
  <div class="rightsec">
    
    <p><!-- # User Profile Page # -->
      <label for="profileurl">User Profile Page <span class="right help"><a href="#" target="_blank" title="Click for more information about this setting.">?</a></span></label>
      <span class="info-hint">Choose a page to attach frontend user profiles to</span>
      <select class="text" name="profileurl">
        <?php
          $all_pages = get_available_pages();
          $all_pages[] = array('slug'=>'','title'=>'----- NONE -----');
          $selected_page = $GSUsers->getSettings('profileurl');
          foreach ($all_pages as $page) {
            $slug = $page['slug'];
            $title = (!empty($page['slug']) ? '['.$page['slug'].'] '.$page['title'] : $page['title']);
            if($slug == $selected_page) {
              echo "<option value=\"$slug\" selected=\"selected\">$title</option>\n";
            } else {
              echo "<option value=\"$slug\">$title</option>\n";
            }
          }
        ?>
      </select>
    </p>
    
    <p><!-- # User Profile Page # -->
      <label for="pageurl">All Users Page <span class="right help"><a href="#" target="_blank" title="Click for more information about this setting.">?</a></span></label>
      <span class="info-hint">Choose a page to use as template for all users</span>
      <select class="text" name="pageurl">
        <?php
          $all_pages = get_available_pages();
          $all_pages[] = array('slug'=>'','title'=>'----- NONE -----');
          $selected_page = $GSUsers->getSettings('pageurl');
          foreach ($all_pages as $page) {
            $slug = $page['slug'];
            $title = (!empty($page['slug']) ? '['.$page['slug'].'] '.$page['title'] : $page['title']);
            if($slug == $selected_page) {
              echo "<option value=\"$slug\" selected=\"selected\">$title</option>\n";
            } else {
              echo "<option value=\"$slug\">$title</option>\n";
            }
          }
        ?>
      </select>
    </p>
    
  </div>
  
  <div class="clear"></div>
  
  <div style="margin-top:20px;">
    <input class="submit" type="submit" value="Save Settings" />
    &nbsp;&nbsp;Or&nbsp;&nbsp;
    <a href="load.php?id=<?php echo USRMGMT; ?>" class="cancel">Cancel</a>
  </div>
  
</form>

<?php exec_filter('checkPermission',array('users-settings',ob_get_clean())); ?>
