<div class="row">
  <div class="span3">
  
  <ul class="nav nav-list">
    <li class="nav-header">List header</li>
    <li class="active"><a href="#">Home</a></li>
    <li><a href="#">Library</a></li>
    <li><a href="#">Applications</a></li>
    <li class="nav-header">Another list header</li>
    <li><a href="#">Profile</a></li>
    <li><a href="#">Settings</a></li>
    <li class="divider"></li>
    <li><a href="#">Help</a></li>
  </ul>
              
  </div>
  <div class="span9">
  
    <div class="btn-toolbar">
    
      <div class="btn-group">
        <button class="btn btn-small"><i class="icon-check"></i> Выделенные</button>
        <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Скрыть</a></li>
          <li><a href="#">Показать</a></li>
          <li><a href="#">Переместить</a></li>
          <li class="divider"></li>
          <li><a href="#">Удалить</a></li>
        </ul>
      </div>
    
      <div class="btn-group">
        <a class="btn btn-small">1</a>
        <a class="btn btn-small">1</a>
        <a class="btn btn-small">1</a>
      </div>
      
      <? if ($pages_count> 1) { ?>
      <div class="pagination pagination-small pull-right" style="margin: 0;">
        <ul>
          <li><a title="В начало" href="prod_list/">««</a></li>
          <li><a title="Назад" href="prod_list/<?= ($current_page == 1) ? '' : $current_page - 1 ?>">«</a></li>
          <?
          
          $start_page = $current_page - 3;
          if ($start_page < 1) $start_page = 1;
          
          $stop_page  = $current_page + 3;
          if ($stop_page > $pages_count) $stop_page = $pages_count;
           
          for ($page = $start_page; $page <= $stop_page; $page++) { 
              if ($page == $current_page) { ?> 
              
          <li class="active"><a href="prod_list/<?= $page ?>"><?= $page ?></a></li>
          
          <? } else { ?>
           
          <li><a href="prod_list/<?= $page ?>"><?= $page ?></a></li>
          
          <? }} ?>
          <li<?= ($current_page >= $pages_count) ? ' class="active"' : '' ?>><a title="Вперед" href="prod_list/<?= ($current_page == $pages_count) ? $pages_count : $current_page + 1 ?>">»</a></li>
          <li<?= ($current_page >= $pages_count) ? ' class="active"' : '' ?>><a title="В конец" href="prod_list/<?= $pages_count ?>">»»</a></li>
        </ul>
      </div>
      <? } ?>
    </div>
  
    <table class="table table-striped table-bordered" id="main-product-list">
      <thead>
        <tr>
          <th>ID</th>
          <th>Наименование</th>
          <th>Цена, руб.</th>
        </tr>
      </thead>
      <tbody>
      <?
      if (isset($products) AND count($products) > 0) {
          foreach ($products AS $product) {
      ?>
        <tr>
          <td><?= $product->id ?></td>
          <td><i class="icon-camera" data-image="<?= $product->picture ?>"></i> <?= $product->name ?></td>
          <td style="text-align: right;"><?= $product->price() ?></td>
        </tr>
      <?
          }
      } else {
      ?>
        <tr>
          <td style="text-align: center;" colspan="3">Список пуст</td>
        </tr>
      <?
      }
      ?>

      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function(){
    
    $('#main-product-list .icon-camera').on('mouseout', function(){
        $('#main-product-list .image-popup').remove();
    }).on('mouseover', function(){
        $(this).after('<div class="image-popup"><img src="'+$(this).attr('data-image')+'" alt=""></div>');
    });    
});
</script>