<?php
$head = array('bodyclass' => 'primary',
              'title' => html_escape(__('Poll'))
		);
echo head($head);
?>


<a class="add-page button small green" href="<?php echo html_escape(url('poll/index/download')); ?>"><?php echo __('Download Results'); ?></a>
		
<?php echo foot(); ?>
