<?php if (!defined('THINK_PATH')) exit();?><meta charset="utf-8">
<div class="pageCenter">
      <ul class="pagination">
           <li><a href="<?php echo ($url); ?>/pnum/<?php echo ($pnum); ?>/p/1<?php echo ($param); ?>">&laquo;</a></li>
                  <?php if(is_array($pagelist["page_num"])): $i = 0; $__LIST__ = $pagelist["page_num"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$num): $mod = ($i % 2 );++$i; if($page["page_current"] == $num): ?><li class="active"><a href="javascript:void(0)"><?php echo ($num); ?></a></li>
                            <?php else: ?>
                           <li><a href="<?php echo ($url); ?>/pnum/<?php echo ($pnum); ?>/p/<?php echo ($num); echo ($param); ?>"><?php echo ($num); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
           <li><a href="<?php echo ($url); ?>/pnum/<?php echo ($pnum); ?>/p/<?php echo ($page['page_total']); echo ($param); ?>">&raquo;</a></li>
     </ul>
                
</div>
<div class="pageRight">
显示行数：
<select id="pageRig" class="w80 form-control">
  <?php if($pnum==10){ ?>
      <option value="10" selected="selected">10</option>
  <?php }else{ ?>
      <option value="10">10</option>
  <?php } ?>
  <?php if($pnum==20){ ?>
      <option value="20" selected="selected">20</option>
  <?php }else{ ?>
      <option value="20">20</option>
  <?php } ?>
  <?php if($pnum==50){ ?>
      <option value="50" selected="selected">50</option>
  <?php }else{ ?>
      <option value="50">50</option>
  <?php } ?>
  <?php if($pnum==100){ ?>
      <option value="100" selected="selected">100</option>
  <?php }else{ ?>
      <option value="100">100</option>
  <?php } ?> 
</select>
</div>
<script>
  $("#pageRig").change(function(){
	  var pagenum=$(this).val();
	  location.href="<?php echo ($url); ?>/pnum/"+pagenum+"<?php echo ($param); ?>";
  });
</script>