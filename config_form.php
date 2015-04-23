<div class="field">
    <div id="poll_show_public_label" class="two columns alpha">
        <label for="poll_show_public"><?php echo __('Show the poll in public view?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo get_view()->formCheckbox('poll_show_public', true, 
        array('checked'=>(boolean)get_option('poll_show_public'))); ?>
        <p class="explanation"><?php echo __('If checked, the poll will be shown in the public section of the website.'); ?></p>
    </div>
</div>
