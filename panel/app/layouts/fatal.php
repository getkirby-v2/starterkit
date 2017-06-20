<!DOCTYPE html>
<html lang="en">
<head>
  <title>Kirby Panel</title>
  <link rel="stylesheet" href="<?php echo $css ?>">

  <style>
    body {
      padding: 3em;
      text-align: center;
      background: #efefef;
      line-height: 1.5em;
    }
    a {
      color: #8dae28;
    }
    p {
      margin-bottom: 1.5em;
    }
  </style>

</head>
<body>
  <h1>Panel Error:</h1>
  <p>
    <?php echo $content ?>
  </p>
  <p>
    <em>Find more info on: <a href="http://getkirby.com">getkirby.com</a></em>
  </p>
</body>
</html>