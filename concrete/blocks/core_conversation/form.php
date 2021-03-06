<?php defined('C5_EXECUTE') or die('Access Denied.');

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$helperFile = $app->make('helper/concrete/file');

if ($controller->getTask() == 'add') {
    $enablePosting = 1;
    $paginate = 1;
    $itemsPerPage = 50;
    $displayMode = 'threaded';
    $enableOrdering = 1;
    $enableCommentRating = 1;
    $enableTopCommentReviews = 0;
    $displayPostingForm = 'top';
    $addMessageLabel = t('Add Message');
    $attachmentOverridesEnabled = 0;
    $attachmentsEnabled = 1;
    $fileAccessFileTypes = Config::get('conversations.files.allowed_types');
    //is nothing's been defined, display the constant value
    if (!$fileAccessFileTypes) {
        $fileAccessFileTypes = $helperFile->unserializeUploadFileExtensions(Config::get('concrete.upload.extensions'));
    } else {
        $fileAccessFileTypes = $helperFile->unserializeUploadFileExtensions($fileAccessFileTypes);
    }
    $maxFileSizeGuest = Config::get('conversations.files.guest.max_size');
    $maxFileSizeRegistered = Config::get('conversations.files.registered.max_size');
    $maxFilesGuest = Config::get('conversations.files.guest.max');
    $maxFilesRegistered = Config::get('conversations.files.registered.max');
    $fileExtensions = implode(',', $fileAccessFileTypes);
    $attachmentsEnabled = intval(Config::get('conversations.attachments_enabled'));
    $notificationUsers = Conversation::getDefaultSubscribedUsers();
    $subscriptionEnabled = intval(Config::get('conversations.subscription_enabled'));
}

if (!$dateFormat) {
    $dateFormat = 'default';
}
?>

<fieldset>
    <legend><?php echo t('Message List')?></legend>
    <div class="form-group">
        <label class="control-label"><?php echo t('Display Mode')?></label>
        <div class="radio">
            <label>
            <?php echo $form->radio('displayMode', 'threaded', $displayMode)?>
            <?php echo t('Threaded')?>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('displayMode', 'flat', $displayMode)?>
            <?php echo t('Flat')?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label"><?php echo t('Ordering')?></label>
        <?php echo $form->select('orderBy', array('date_asc' => t('Earliest First'), 'date_desc' => t('Most Recent First'), 'rating' => t('Highest Rated')), $orderBy)?>
        <div class="checkbox">
            <label>
                <?php echo $form->checkbox('enableOrdering', 1, $enableOrdering)?>
                <?php echo t('Display Ordering Option in Page')?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label"><?php echo t('Rating')?></label>
        <div class="checkbox">
            <label>
            <?php echo $form->checkbox('enableCommentRating', 1, $enableCommentRating)?>
            <?php echo t('Enable Comment Rating')?>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <?php echo $form->checkbox('enableTopCommentReviews', 1, $enableTopCommentReviews)?>
                <?php echo t('Turn Top-Level Posts into Reviews')?>
            </label>
        </div>
    </div>
    <?php
    if (isset($reviewAttributeKeys)) { ?>
        <div class="form-group" data-unhide="[name=enableTopCommentReviews]">
            <label class="control-label"><?php echo t('Aggregate Ratings by Attribute') ?></label>
            <?php
            if (count($reviewAttributeKeys) > 0) {
                echo $form->select('reviewAggregateAttributeKey', $reviewAttributeKeys, $reviewAggregateAttributeKey);
            } else { ?>
                <div class="alert alert-info">
                    <?php echo t('Create a page attribute of type "%s" in order to aggregate ratings.', tc('AttributeTypeName', 'Rating')) ?>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
    <div class="form-group">
        <label class="control-label"><?php echo t('Paginate Message List')?></label>
        <div class="radio">
            <label>
            <?php echo $form->radio('paginate', 0, $paginate)?>
            <?php echo t('No, display all messages.')?>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('paginate', 1, $paginate)?>
            <?php echo t('Yes, display only a sub-set of messages at a time.')?>
            </label>
        </div>
    </div>
    <div class="form-group" data-row="itemsPerPage">
        <label class="control-label"><?php echo t('Messages Per Page')?></label>
        <?php echo $form->text('itemsPerPage', $itemsPerPage, array('class' => 'span1'))?>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Posting')?></legend>
    <div class="form-group">
        <?php echo $form->label('addMessageLabel', t('Add Message Label'))?>
        <?php echo $form->text('addMessageLabel', $addMessageLabel)?>
    </div>
    <div class="form-group">
        <label class="control-label"><?php echo t('Enable Posting')?></label>
        <div class="radio">
            <label>
            <?php echo $form->radio('enablePosting', 1, $enablePosting)?>
            <span><?php echo t('Yes, this conversation accepts messages and replies.')?></span>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('enablePosting', 0, $enablePosting)?>
            <span><?php echo t('No, posting is disabled.')?></span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label"><?php echo t('Display Posting Form')?></label>
        <div class="radio">
            <label>
            <?php echo $form->radio('displayPostingForm', 'top', $displayPostingForm)?>
            <span><?php echo t('Top')?></span>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('displayPostingForm', 'bottom', $displayPostingForm)?>
            <span><?php echo t('Bottom')?></span>
            </label>
        </div>
    </div>
</fieldset>

<fieldset>
    <div class="form-group">
        <label class="control-label"><?php echo t('Date Format')?></label>
        <div class="radio">
            <label>
            <?php echo $form->radio('dateFormat', 'default', $dateFormat)?>
            <span><?php echo t('Use Site Default.')?></span>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('dateFormat', 'elapsed', $dateFormat)?>
            <span><?php echo t('Time elapsed since post.')?></span>
            </label>
        </div>
        <div class="radio">
            <label>
            <?php echo $form->radio('dateFormat', 'custom', $dateFormat)?>
            <span><?php echo t('Custom Date Format')?></span>
            </label>
        </div>
        <div class="form-group" data-row="customDateFormat">
            <?php echo $form->text('customDateFormat', $customDateFormat)?>
            <div class="help-block"><?php echo sprintf(t('See the formatting options for date at %s.'), '<a href="http://www.php.net/date" target="_blank">php.net/date</a>'); ?></div>
        </div>
    </div>
</fieldset>

<fieldset>
	<legend><?php echo t('File Attachment Management')?></legend>
	<p class="help-block"><?php echo t('Note: Entering values here will override global conversations file attachment settings for this block if you enable Attachment Overrides for this Conversation.') ?></p>
    <div class="form-group">
        <div class="checkbox">
            <label class="">
            <?php echo $form->checkbox('attachmentOverridesEnabled', 1, $attachmentOverridesEnabled)?><?php echo t('Enable Attachment Overrides')?>
            </label>
        </div>
        <div class="attachment-overrides">
            <div class="checkbox">
                <label class="">
                <?php echo $form->checkbox('attachmentsEnabled', 1, $attachmentsEnabled)?><?php echo t('Enable Attachments')?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group attachment-overrides">
        <label class="control-label"><?php echo t('Max Attachment Size for Guest Users. (MB)')?></label>
        <div class="controls">
            <?php echo $form->text('maxFileSizeGuest', $maxFileSizeGuest > 0 ? $maxFileSizeGuest : '')?>
        </div>
    </div>
    <div class="form-group attachment-overrides">
        <label class="control-label"><?php echo t('Max Attachment Size for Registered Users. (MB)')?></label>
        <div class="controls">
            <?php echo $form->text('maxFileSizeRegistered', $maxFileSizeRegistered > 0 ? $maxFileSizeRegistered : '')?>
        </div>
    </div>
    <div class="form-group attachment-overrides">
        <label class="control-label"><?php echo t('Max Attachments Per Message for Guest Users.')?></label>
        <div class="controls">
            <?php echo $form->text('maxFilesGuest', $maxFilesGuest > 0 ? $maxFilesGuest : '')?>
        </div>
    </div>
    <div class="form-group attachment-overrides">
        <label class="control-label"><?php echo t('Max Attachments Per Message for Registered Users')?></label>
        <div class="controls">
            <?php echo $form->text('maxFilesRegistered', $maxFilesRegistered > 0 ?  $maxFilesRegistered : '')?>
        </div>
    </div>
    <div class="form-group attachment-overrides">
        <label class="control-label"><?php echo t('Allowed File Extensions (Comma separated, no periods).')?></label>
        <div class="controls">
            <?php echo $form->textarea('fileExtensions', $fileExtensions)?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Notification')?></legend>
    <div class="form-group">
        <div class="checkbox">
            <label>
                <?php echo $form->checkbox('notificationOverridesEnabled', 1, $notificationOverridesEnabled)?><?php echo t('Override Global Settings')?>
            </label>
        </div>
    </div>
    <div class="form-group notification-overrides">
        <label class="control-label"><?php echo t('Users To Receive Conversation Notifications')?></label>
        <?php echo $app->make("helper/form/user_selector")->selectMultipleUsers('notificationUsers', $notificationUsers)?>
    </div>
    <div class="form-group notification-overrides">
        <label class="control-label"><?php echo t('Subscribe Option')?></label>
        <div class="checkbox">
            <label><?php echo $form->checkbox('subscriptionEnabled', 1, $subscriptionEnabled)?>
                <?php echo t('Yes, allow registered users to choose to subscribe to conversations.')?>
            </label>
        </div>
    </div>
</fieldset>

<script>
$(function() {
    $('[data-unhide]').each(function() {
        var me = $(this),
            selector = me.data('unhide'),
            watch = $(selector);

        watch.change(function() {
            if (watch.is(':checked')) {
                me.show();
            } else {
                me.hide();
            }
        }).trigger('change');
    });

    $('input[name=paginate]').on('change', function() {
        var pg = $('input[name=paginate]:checked');
        if (pg.val() == 1) {
            $('div[data-row=itemsPerPage]').show();
        } else {
            $('div[data-row=itemsPerPage]').hide();
        }
    }).trigger('change');

    $('input[name=dateFormat]').on('change', function() {
        var dateFormat = $('input[name=dateFormat]:checked');
        if (dateFormat.val() == 'custom') {
            $('div[data-row=customDateFormat]').show();
        } else {
            $('div[data-row=customDateFormat]').hide();
        }
    }).trigger('change');

    $('input[name=attachmentOverridesEnabled]').on('change', function() {
        var ao = $('input[name=attachmentOverridesEnabled]:checked');
        if (ao.val() == 1) {
            $('.attachment-overrides input, .attachment-overrides textarea').prop('disabled', false);
            $('.attachment-overrides label').removeClass('text-muted');
        } else {
            $('.attachment-overrides input, .attachment-overrides textarea').prop('disabled', true);
            $('.attachment-overrides label').addClass('text-muted');
        }
    }).trigger('change');

    $('input[name=notificationOverridesEnabled]').on('change', function() {
        var ao = $('input[name=notificationOverridesEnabled]:checked');
        if (ao.val() == 1) {
            $('.notification-overrides').show();
        } else {
            $('.notification-overrides').hide();
        }
    }).trigger('change');
});
</script>
