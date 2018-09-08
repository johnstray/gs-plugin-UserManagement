<?php $all_users = $GSUsers->getAllUsers(); ob_start(); ?>
<h3 class="floated" style="float:left;"><?php i18n(USRMGMT.'/USERS_MANAGEMENT'); ?></h3>

<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo USRMGMT; ?>&action=user-editor"><?php i18n(USRMGMT.'/BTN_CREATE_USER'); ?></a>
        <a href="load.php?id=<?php echo USRMGMT; ?>&action=view-groups"><?php i18n(USRMGMT.'/BTN_MANAGE_GROUPS'); ?></a>
        <a href="load.php?id=<?php echo USRMGMT; ?>&action=settings"><?php i18n(USRMGMT.'/BTN_SETTINGS'); ?></a>
    </p>
    <div class="clear"></div>
</div>

<table class="highlight">
    <tbody>
        <?php
            $allGroups = $GSUsers->getAllGroups();
            foreach ($all_users as $username) {
                $user = $GSUsers->getUserProfile($username);
                $USR = (empty($user['USR'])) ? '' : strtolower($user['USR']);
                $NAME = (empty($user['NAME'])) ? '' : $user['NAME'];
                $EMAIL = (empty($user['EMAIL'])) ? '-- '.i18n_r('NONE').' --' : $user['EMAIL'];
                $AVATAR = (empty($user['AVATAR'])) ? '' : $user['AVATAR'];
                $USERGROUP = (empty($user['USERGROUP'])) ? null : $user['USERGROUP'];
                if ( $USERGROUP !== null ) {
                    if ( $GSUsers->getGroupDetails($USERGROUP) !== false ) {
                        $USERGROUP = $allGroups[$USERGROUP];
                    } else { $USERGROUP = '-- '.i18n_r('DELETED_GROUP').' --'; }
                } else { $USERGROUP = '-- '.i18n_r('NONE').' --'; }
        ?>
        <tr>
            <td style="padding:10px;">
                <img src="../data/uploads/<?php echo $AVATAR; ?>" style="background:#fff;height:37px;width:37px;float:left;margin:-5px 10px -5px -5px;box-shadow:rgba(0,0,0,0.5) 0 0 5px;overflow:hidden;" />
                <a title="" href="load.php?id=<?php echo USRMGMT; ?>&action=user-editor&user=<?php echo $USR; ?>" style="text-decoration:none;display:block;line-height:15px;"><?php echo $NAME; ?></a>
                <div style="position:relative;font-size:10px;line-height:12px;">
                    <div style="float:left;width:28%;">Username: <span style="font-size:inherit"><?php echo $USR; ?></span></div>
                    <div style="float:left;width:28%;">Group: <span style="font-size:inherit"><?php echo $USERGROUP; ?></span></div>
                    <div style="float:left;;">Email: <span style="font-size:inherit"><?php echo $EMAIL; ?></span></div>
                    <div class="clearfix"></div>
                </div>
            </td>
            <td class="delete">
                <a class="delconfirm noajax" href="load.php?id=<?php echo USRMGMT; ?>&action=delete-user&user=<?php echo $USR; ?>" title="Delete User: <?php echo $USR; ?>" ><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    $('img').error(function(){
        $(this).attr('src', '/plugins/<?php echo USRMGMT; ?>/resources/missing.png');
    });
</script>
<?php exec_filter('checkPermission',array('users-view',ob_get_clean())); ?>
