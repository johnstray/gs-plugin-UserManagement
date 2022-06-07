<?php $all_groups = $GSUsers->getAllGroups(); ob_start(); ?>
<h3 class="floated" style="float:left;"><?php i18n(USRMGMT.'/GROUP_MANAGEMENT'); ?></h3>

<div class="edit-nav">
  <p class="text 1">
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=group-editor"><?php i18n(USRMGMT.'/BTN_CREATE_GROUP'); ?></a>
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=view-users"><?php i18n(USRMGMT.'/BTN_MANAGE_USERS'); ?></a>
    <a href="load.php?id=<?php echo USRMGMT; ?>&action=settings"><?php i18n(USRMGMT.'/BTN_SETTINGS'); ?></a>
  </p>
  <div class="clear"></div>
</div>

<table class="highlight">
  
  <thead>
    <tr>
      <th><?php i18n(USRMGMT.'/GROUP_NAME'); ?></th>
      <th><i class="fa fa-trash-o"></i></th>
    </tr>
  </thead>
  
  <tbody>
    <?php foreach ($all_groups as $groupslug => $groupname) { ?>
    <tr>
      <td><a href="load.php?id=<?php echo USRMGMT; ?>&action=group-editor&group=<?php echo $groupslug; ?>"><?php echo $groupname ?></a></td>
      <td class="delete noajax"><a href="load.php?id=<?php echo USRMGMT; ?>&action=delete-group&group=<?php echo $groupslug; ?>"><i class="fa fa-trash-o"></i></a></td>
    </tr>
    <?php } ?>
  </tbody>
  
</table>

<?php exec_filter('checkPermission',array('users-viewgroup',ob_get_clean())); ?>
