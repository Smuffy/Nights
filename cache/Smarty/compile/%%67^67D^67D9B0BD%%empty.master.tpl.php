<?php /* Smarty version 2.6.25, created on 2010-03-31 16:02:09
         compiled from /nfs/c01/h15/mnt/36834/domains/nightsed.com/html/applications/garden/views/empty.master.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'asset', '/nfs/c01/h15/mnt/36834/domains/nightsed.com/html/applications/garden/views/empty.master.tpl', 4, false),array('modifier', 'escape', '/nfs/c01/h15/mnt/36834/domains/nightsed.com/html/applications/garden/views/empty.master.tpl', 6, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
	<?php echo smarty_function_asset(array('name' => 'Head'), $this);?>

</head>
<body id="<?php echo ((is_array($_tmp=$this->_tpl_vars['BodyIdentifier'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="<?php echo ((is_array($_tmp=$this->_tpl_vars['CssClass'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<?php echo smarty_function_asset(array('name' => 'Content'), $this);?>

</body>
</html>