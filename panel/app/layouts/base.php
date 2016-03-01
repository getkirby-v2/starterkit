<!DOCTYPE html>
<html lang="en">
<head>

  <?php echo $meta ?>

  <title><?php __($title) ?></title>

  <?php echo $css ?>

  <?php if($stylesheet = kirby()->option('panel.stylesheet')): ?>
  <?php echo css($stylesheet) ?>
  <?php endif ?>
  
  <?php echo $js ?>

</head>
<body class="app <?php __($direction) ?> <?php __($bodyclass) ?>">
  <?php echo $content ?>
</body>
</html>