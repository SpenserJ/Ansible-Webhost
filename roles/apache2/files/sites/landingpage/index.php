<?php
if (strpos($_SERVER['REMOTE_ADDR'], '10.0.0') !== 0) {
  header('HTTP/1.0 404 Not Found');
  echo file_get_contents('./404.html');
  return;
}
?>
<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Simple Simple Staging</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="http://landingpage.<?php echo $_SERVER['HTTP_HOST']; ?>/css/normalize.min.css">
    <link rel="stylesheet" href="http://landingpage.<?php echo $_SERVER['HTTP_HOST']; ?>/css/main.css">

    <script src="http://landingpage.<?php echo $_SERVER['HTTP_HOST']; ?>/js/vendor/modernizr-2.6.2.min.js"></script>
  </head>
  <body>
    <h1>Hello Simple Simple!</h1>

    <ul>
<?php
$dir = '/var/www/shared/';
$sites = array();
if (is_dir($dir)) {
  if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
      if (filetype($dir . $file) === 'dir' && $file !== '.' && $file !== '..') {
        $sites[$file] = "      <li><a href='http://$file.{$_SERVER['HTTP_HOST']}/'>$file</a></li>";
      }
    }
    closedir($dh);
  }
}
ksort($sites);
echo implode("\n", array_values($sites));
?>
    </ul>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="http://landingpage.<?php echo $_SERVER['HTTP_HOST']; ?>/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
    <script src="http://landingpage.<?php echo $_SERVER['HTTP_HOST']; ?>/js/main.js"></script>
  </body>
</html>
