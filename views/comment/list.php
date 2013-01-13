<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="comment-list">
	<?php
	foreach($list as $item):
    $author_id = $item->author_id;
	?>
    <dl id="comment_<?php echo $item->pk();?>">
        <dt>
			<span>
            <?php
            if(intval($author_id) > 0){
                echo HTML::anchor('/jiaolian/user/'.$author_id, $item->author);
            } else {
                echo $item->author;
            }
            ?>
            </span><?php echo date('Y-m-d H:i',	$item->dateline);?>
			<?php if($user_id AND $user_id == $author_id):?>
			<span class="pull-right"><a href="<?php echo URL::site('/comment/delete/'.$item->pk())?>">删除</a></span>
			<?php endif;?>
		</dt>
        <dd><?php echo $item->content;?></dd>
    </dl>
	<?php endforeach;?>
</div>