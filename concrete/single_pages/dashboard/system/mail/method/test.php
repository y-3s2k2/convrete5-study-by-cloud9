<?php
use Concrete\Core\User\UserInfoRepository;

defined('C5_EXECUTE') or die('Access Denied.');

// Arguments
/* @var bool $emailEnabled */
/* @var string $mailRecipient [optional] */
/* @var int $numEmails [optional] */

if (!isset($mailRecipient)) {
    $mailRecipient = '';
    $me = new User();
    if ($me->isRegistered()) {
        $myInfo = Core::make(UserInfoRepository::class)->getByID($me->getUserID());
        if ($myInfo !== null) {
            $mailRecipient = $myInfo->getUserEmail();
        }
    }
}
if (!isset($numEmails)) {
    $numEmails = 1;
}
?>

<form method="post" action="<?php echo $view->action('do_test') ?>" id="mail-settings-test-form">
    <?php
    if (!$emailEnabled) {
        ?>
        <div class="alert alert-info">
            <?php echo t(/*i18n: %1$s is a configuration name, %2$s is a configuration value*/'It\'s not possible to test the settings since the mail system is disabled (the setting %1$s is set to %2$s in the configuration).', '<b>concrete.email.enabled</b>', '<b>false</b>') ?>
        </div>
        <?php
    } else {
        ?>
        <?php $token->output('test') ?>
        <div class="form-group">
            <?php echo $form->label('mailRecipient', t('Recipient email address')) ?>
            <?php echo $form->email('mailRecipient', $mailRecipient, ['required' => 'required']) ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('numEmails', t('Number of messages to send')) ?>
            <?php echo $form->number('numEmails', $numEmails, ['required' => 'required', 'min' => 1]) ?>
        </div>
        <?php
    }
    ?>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?php echo URL::to('/dashboard/system/mail/method') ?>" class="btn btn-default pull-left"><?php echo t('Change Settings') ?></a>
            <?php
            if ($emailEnabled) {
                echo $interface->submit(t('Send'), 'mail-settings-test-form', 'right', 'btn-primary');
            }
            ?>
        </div>
    </div>
</form>
