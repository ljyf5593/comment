<?php defined('SYSPATH') or die('No direct script access.');?>
<form action="<?php echo URL::site('/comment/create');?>" method="post">
    <label>留言(至少<?php echo Model_Comment::content_min;?>个字符)：</label>
    <textarea name="content" id="content" class="span8" rows="6"></textarea>
	<input type="hidden" name="targettype" value="<?php echo $model;?>">
	<input type="hidden" name="targetid" value="<?php echo $id;?>">
    <button type="submit" class="btn btn-primary">提&nbsp;&nbsp;&nbsp;交</button>
</form>