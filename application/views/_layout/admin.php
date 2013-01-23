<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Админка</title>

  <base href="/admin/">
  
  <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css">
  
  <link rel="stylesheet" href="/skin/admin/css/style.css">
  
  <script src="/vendor/jquery/jquery-1.9.0.min.js"></script>
  
</head>
<body>
  
  <?= View::factory('admin/_topmenu') ?>

  <div class="container" style="margin-top: 50px;">
  <?= $content ?>
  </div>
  
  <script src="/vendor/bootstrap/js/bootstrap.js"></script>
  
</body>
</html>
