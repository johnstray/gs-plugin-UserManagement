<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**
 * Plugin Name: GetSimple User Management
 * Description: Enables advanced user management functions for GetSimple CMS with support for custom permissions from plugins
 * File Action: 
 */

if(isset($_GET['user'])) {$existing = $GSUsers->getUserProfile($_GET['user']);}

$USR = (isset($existing['USR'])) ? strtolower($existing['USR']) : '';
$NAME = (isset($existing['NAME'])) ? $existing['NAME'] : '';
$PASSWD = '';
$EMAIL = (isset($existing['EMAIL'])) ? $existing['EMAIL'] : '';
$TIMEZONE = (isset($existing['TIMEZONE'])) ? var_out($existing['TIMEZONE']) : '';
$LANG = (isset($existing['LANG'])) ? var_out($existing['LANG']) : '';
$USERGROUP = (isset($existing['USERGROUP'])) ? $existing['USERGROUP'] : '';
$LANDINGPAGE = (isset($existing['LANDINGPAGE'])) ? $existing['LANDINGPAGE'] : '';
$AVATAR = (isset($existing['AVATAR'])) ? $existing['AVATAR'] : '';
$PROFILE = (isset($existing['PROFILE'])) ? $existing['PROFILE'] : '';
$all_groups = $GSUsers->getAllGroups();
?>

<h3><?php i18n(USRMGMT.'/USER_MANAGEMENT'); ?></h3>

<form class="largeform" action="load.php?id=<?php echo USRMGMT; ?>&action=save-user" method="post" accept-charset="utf-8">
  
  <input type="hidden" name="nonce" value="<?php echo get_nonce("users_saveuser"); ?>" />
  
  <h4><?php i18n(USRMGMT.'/USER_INFORMATION'); ?></h4>
  
  <div class="leftsec">
    <p><!-- # Username # -->
      <label for="username"><?php i18n(USRMGMT.'/USER_INFO_USERNAME'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_USERNAME_HINT'); ?></span>
      <input class="text" type="text" name="username" value="<?php echo $USR; ?>" />
    </p>
    <p><!-- # User Group # -->
      <label for="usergroup"><?php i18n(USRMGMT.'/USER_INFO_USERGROUP'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_USERGROUP_HINT'); ?></span>
      <select class="text" name="usergroup">
<?php foreach ($all_groups as $groupslug => $groupname) {
    $thisGroup = $GSUsers->getGroupDetails($groupslug);
    var_dump($thisGroup);
    if ($thisGroup['GROUPSLUG'] == $USERGROUP) {$selected = ' selected="selected"';} else {$selected='';}
    echo '<option value="'.$thisGroup['GROUPSLUG'].'"'.$selected.'>'.$thisGroup['GROUPNAME'].'</option>';
} ?>

      </select>
    </p>
    <p><!-- # Timezone # -->
      <label for="timezone"><?php i18n(USRMGMT.'/USER_INFO_TIMEZONE'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_TIMEZONE_HINT'); ?></span>
      <select class="text" name="timezone">
        <?php if ($TIMEZONE == '') { echo '<option value="" selected="selected" >-- '.i18n_r('NONE').' --</option>'; }
        else { echo '<option selected="selected"  value="'. $TIMEZONE .'">Current: '. $TIMEZONE .'</option>'; } ?>
        <?php include(GSADMININCPATH.'timezone_options.txt'); ?>
      </select>
    </p>
  </div>
  
  <div class="rightsec">
    <p><!-- # Password # -->
      <label for="password"><?php i18n(USRMGMT.'/USER_INFO_PASSWORD'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_PASSWORD_HINT'); ?></span>
      <input class="text" type="password" name="password" value="" />
    </p>
    <p><!-- # Landing Page # -->
      <label for="landingpage"><?php i18n(USRMGMT.'/USER_INFO_LANDING'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_LANDING_HINT'); ?></span>
      <select class="text" name="landingpage">
        <optgroup label="Defaults">
            <option value="group-default">Group Default: Group Option</option>
            <option value="global-default">Global Default: Global Option</option>
        </optgroup>
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
    <p><!-- # Language # -->
      <label for="lang"><?php i18n('LANGUAGE');?>: <span class="right"><a href="http://get-simple.info/docs/languages" target="_blank" ><?php i18n('MORE');?></a></span></label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_INFO_LANG_HINT'); ?></span>
      <select class="text" name="lang">
        <?php 
          # get all available language files
          if ($LANG == ''){ $LANG = 'en_US'; }
          $lang_array = getFiles(GSLANGPATH);
          if (count($lang_array) != 0) {
            sort($lang_array);
            $sel = ''; $langs = '';
            foreach ($lang_array as $lfile){
              $lfile = basename($lfile,".php");
              if ($LANG == $lfile)	{ $sel="selected"; }
              $langs .= '<option '.$sel.' value="'.$lfile.'" >'.$lfile.'</option>';
              $sel = '';
            }
          } else {
            $langs = '<option value="" selected="selected" >-- '.i18n_r('NONE').' --</option>';
          }
          echo $langs;
        ?>
      </select>
    </p>
  </div>
  
  <div class="clear"></div>
  
  <h4><?php i18n(USRMGMT.'/USER_PROFILE'); ?></h4>
  
  <div class="leftsec">
    <div class="permgroup">
      <h6><?php i18n(USRMGMT.'/USER_PROFILE_AVATAR'); ?></h6>
      <div id="uploadbox" onClick="singleupload_input.click();" class="singleupload">
        <?php if(!empty($AVATAR)) { ?>
          <img src="../data/uploads/<?php echo $AVATAR; ?>" />
        <?php } ?>
        <div>Click here to Upload Avatar</div>
        <script>$('img').error(function(){$(this).attr('src', '/plugins/<?php echo USRMGMT; ?>/resources/missing.png');});</script>
      </div>
      <input type="file" id="singleupload_input" style="display:none;" name="avatar-img" value=""/>
      <p style="margin-left:114px;">
        <label for="avatar-file"><?php i18n(USRMGMT.'/USER_PROFILE_AVATAR_FILE'); ?>:</label>
        <input class="text" type="text" id="avatar-file" name="avatar-file" value="<?php echo $AVATAR; ?>" />
      </p>
      <div style="margin-left:114px;"><a class="button" id="avatar-select"><?php i18n(USRMGMT.'/SELECT_FILE'); ?></a></div>
      <div class="clear"></div>
    </div>
  </div>
  <div class="rightsec">
    <p><!-- # Display Name # -->
      <label for="name"><?php i18n(USRMGMT.'/USER_PROFILE_DNAME'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_PROFILE_DNAME_HINT'); ?></span>
      <input class="text" type="text" name="name" value="<?php echo $NAME; ?>" />
    </p>
    <p><!-- # Email Address # -->
      <label for="email"><?php i18n(USRMGMT.'/USER_PROFILE_EMAIL'); ?>:</label>
      <span class="info-hint"><?php i18n(USRMGMT.'/USER_PROFILE_EMAIL_HINT'); ?></span>
      <input class="text" type="email" name="email" value="<?php echo $EMAIL; ?>" />
    </p>
  </div>
  <div class="clear"></div>
  
  <p>
    <label for="profile-content" style="display:none;"><?php i18n(USRMGMT.'/USER_PROFILE_CONTENT'); ?></label>
    <textarea id="profile-content" name="profile-content"><?php echo $PROFILE; ?></textarea>
  </p>
  
  <div style="margin-top:20px;">
    <input class="submit" type="submit" value="<?php i18n(USRMGMT.'/SAVE_USER'); ?>" />
    &nbsp;&nbsp;<?php i18n(USRMGMT.'/OR'); ?>&nbsp;&nbsp;
    <a href="load.php?id=<?php echo USRMGMT; ?>" class="cancel"><?php i18n(USRMGMT.'/CANCEL'); ?></a>
  </div>
</form>

<?php 
  /* # CKEditor Initialisation # */
  GLOBAL $SITEURL, $GSADMIN, $EDLANG, $TEMPLATE, $EDHEIGHT, $EDTOOL, $EDOPTIONS;
  if(isset($EDTOOL)) {$EDTOOL = returnJsArray($EDTOOL);}
  if(isset($toolbar)) {$toolbar = returnJsArray($toolbar);} // handle plugins that corrupt this
  else if(strpos(trim($EDTOOL),'[[')!==0 && strpos(trim($EDTOOL),'[')===0){ $EDTOOL = "[$EDTOOL]"; }

  if(isset($toolbar) && strpos(trim($toolbar),'[[')!==0 && strpos($toolbar,'[')===0){ $toolbar = "[$toolbar]"; }
  $toolbar = isset($EDTOOL) ? ",toolbar: ".trim($EDTOOL,",") : '';
  $options = isset($EDOPTIONS) ? ','.trim($EDOPTIONS,",") : '';
?>
<script src="<?php echo $SITEURL.$GSADMIN.'/template/js/ckeditor/ckeditor.js'; ?>"></script>
<script>
  var editor = CKEDITOR.replace( 'profile-content', {
    skin : 'getsimple',
    forcePasteAsPlainText : true,
    language : '<?php echo $EDLANG; ?>',
    defaultLanguage : 'en',
    <?php if(file_exists(GSTHEMESPATH.$TEMPLATE."/editor.css")) {$fullpath = suggest_site_path(); ?>
      contentsCss : '<?php echo $fullpath.'theme/'.$TEMPLATE.'/editor.css'; ?>',
    <?php } ?>
    entities : false,
    height: '300',
    baseHref : '<?php echo $SITEURL; ?>',
    tabSpaces:10,
    filebrowserBrowseUrl : 'filebrowser.php?type=all',
    filebrowserImageBrowseUrl : 'filebrowser.php?type=images',
    filebrowserWindowWidth : '730',
    filebrowserWindowHeight : '500'
    <?php echo $toolbar; ?>
    <?php echo $options; ?>		
  });
  
  CKEDITOR.instances["profile-content"].on("instanceReady", InstanceReadyEvent);

  function InstanceReadyEvent(ev) {
    _this = this;

    this.document.on("keyup", function () {
      $('#editform #profile-content').trigger('change');
      _this.resetDirty();
    });

      this.timer = setInterval(function(){trackChanges(_this)},500);
  }		

  /**
   * keep track of changes for editor
   * until cke 4.2 is released with onchange event
   */
  function trackChanges(editor) {
    // console.log('check changes');
    if ( editor.checkDirty() ) {
      $('#editform #profile-content').trigger('change');
      editor.resetDirty();			
    }
  };
  $(function() {
      $('#uploadbox').singleupload({
          action: '../plugins/gs-users/upload_avatar.php', //action: 'do_upload.php'
          sessionHash: '<?php echo $SESSIONHASH; ?>',
          filePath: 'avatars',
          baseImgPath: '../data/uploads/',
          inputId: 'singleupload_input',
          onError: function(code) {
              console.warning('error code '+res.code);
          },
          onSuccess: function(url, data) {
            $('input[name="avatar-file"').prop('value', url);
          }
          /*,onProgress: function(loaded, total) {} */
      });
  });
  
  function triggerChange(id) {
    $('#'+id).trigger('change');
  }
  
  $('#avatar-select').on('click',function() {
    window.open('filebrowser.php?path=avatars&returnid=avatar-file&func=triggerChange&type=image&CKEditorFuncNum=0','_blank','scrollbars=1,resizable=1,height=300,width=450');
  });
  $('#avatar-file').on('change',function() {
    $(this).val($(this).val().replace('<?php echo suggest_site_path(); ?>data/uploads/',''));
  });
</script>
