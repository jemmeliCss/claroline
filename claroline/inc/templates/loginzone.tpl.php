<!-- $Id$ -->

<?php if ( count( get_included_files() ) == 1 ) die( basename(__FILE__) ); ?>

<div id="loginBox">
<div class="header"><?php echo get_lang('Authentication'); ?></div>

<?php if ( get_conf('claro_CasEnabled') ) : ?>
<!-- CAS login hyperlink -->
<div align="center">
<a href="<?php echo get_path('clarolineRepositoryWeb'); ?>auth/login.php?authModeReq=CAS">
<?php echo get_conf('claro_CasLoginString'); ?>
</a>
</div>
<?php endif; ?>


<?php if( get_conf('claro_displayLocalAuthForm') ) : ?>
<script type="text/javascript">
<!--
$(document).ready( function(){
    $("#login").focus();
});
//-->
</script>
<?php if( get_conf('claro_secureLogin', false) ) : ?>
 <!-- Authentication Form -->
<form class="claroLoginForm" action="<?php echo 'https://'.$_SERVER['HTTP_HOST'] . get_path('clarolineRepositoryWeb'); ?>auth/login.php" method="post">
<?php else: ?>
<form class="claroLoginForm" action="<?php echo get_path('clarolineRepositoryWeb'); ?>auth/login.php" method="post">
<?php endif; ?>
<fieldset style="border: 0; margin: 10px 0 15px 0; padding: 5px;">
<label for="login"><?php echo get_lang('Username'); ?></label><br />
<input type="text" name="login" id="login" size="12" tabindex="1" /><br />
<br />
<label for="password"><?php echo get_lang('Password'); ?></label><br />
<input type="password" name="password" id="password" size="12" tabindex="2" /><br />
<br />
<input type="submit" value="<?php echo get_lang('Enter'); ?>" name="submitAuth" tabindex="3" />
</fieldset>
</form>

<p style="padding: 5px;">
<?php   if( get_conf('claro_displayLostPasswordLink', true) ) : ?>
<!-- "Lost Password" -->
<a href="<?php echo get_path('clarolineRepositoryWeb'); ?>auth/lostPassword.php"><?php echo get_lang('Lost password'); ?></a><br />
<?php   endif; ?>

<?php   if( get_conf('allowSelfReg') ) : ?>
<!-- "Create user Account" -->
<a href="<?php echo get_path('clarolineRepositoryWeb'); ?>auth/inscription.php"><?php echo get_lang('Create user account'); ?></a><br />
<?php   endif; ?>
</p>

<?php endif; ?>

</div>