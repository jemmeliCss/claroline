<?php
$langFile='help';

include('../inc/claro_init_global.inc.php');

echo "<html><head>";
echo "<title>$langHFor</title>";
?>

</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
  <td align="left" valign="top">

    <?php echo "<h4>$langHFor</h4>"; ?>

  </td>
  <td align="right" valign="top">
    <a href="javascript:window.close();"><?php echo $langClose; ?></a>
  </td>
</tr>
<tr>
  <td colspan="2">

    <?php echo $langForContent; ?>

  </td>
</tr>
<tr>
  <td colspan="2">
    <br>
    <center><a href="javascript:window.close();"><?php echo $langClose; ?></a></center>
  </td>
</tr>
</table>
</body>
</html>
