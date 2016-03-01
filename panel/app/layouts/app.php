<!DOCTYPE html>
<html lang="en">
<head>

  <?php echo $meta ?>

  <title><?php __($title) ?></title>

  <?php echo $css ?>
  <?php echo $formcss ?>
  <?php echo $pluginscss ?>

  <?php if($stylesheet = kirby()->option('panel.stylesheet')): ?>
  <?php echo css($stylesheet) ?>
  <?php endif ?>

  <?php echo $js ?>
  <?php echo $appjs ?>
  <?php echo $formjs ?>
  <?php echo $pluginsjs ?>

</head>
<body class="app <?php __($direction) ?>" data-csrf="<?php __($csrf) ?>">

  <div class="main">
    <?php if(isset($topbar))  echo $topbar ?>
    <?php if(isset($content)) echo $content ?>
  </div>

</body>
</html>