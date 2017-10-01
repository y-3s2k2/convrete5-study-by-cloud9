<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Core\Block\Block $b */

$bt = $b->getBlockTypeObject();

if (!isset($btHandle)) {
    $btHandle = $b->getBlockTypeHandle();
}

?>
<a name="_edit<?php echo $b->getBlockID() ?>"></a>

<script type="text/javascript">
<?php
$ci = Core::make('helper/concrete/urls');
$url = (string) $ci->getBlockTypeJavaScriptURL($bt);
if ($url !== '') {
    ?>ccm_addHeaderItem(<?php echo json_encode($url) ?>, 'JAVASCRIPT');<?php 
}
if (isset($headerItems) && is_array($headerItems)) {
    $identifier = 'BLOCK_CONTROLLER_' . strtoupper($btHandle);
    if (isset($headerItems[$identifier]) && is_array($headerItems[$identifier])) {
        foreach ($headerItems[$identifier] as $item) {
            if ($item instanceof CSSOutputObject) {
                $type = 'CSS';
            } else {
                $type = 'JAVASCRIPT';
            }
            ?>ccm_addHeaderItem(<?php echo json_encode((string) $item->file) ?>, <?php echo json_encode($type) ?>);<?php
        }
    }
}
?>
$(function() {
	$('#ccm-block-form').concreteAjaxBlockForm({
		task: 'edit',
		bID: <?php echo is_object($b->getProxyBlock()) ? $b->getProxyBlock()->getBlockID() : $b->getBlockID() ?>,
        btSupportsInlineEdit: <?php echo $bt->supportsInlineEdit() ? 'true' : 'false' ?>
	});
});
</script>
<?php
if ($b->getBlockTypeHandle() === BLOCK_HANDLE_SCRAPBOOK_PROXY) {
    $bx = Block::getByID($b->getController()->getOriginalBlockID());
    $cont = $bx->getController();
} else {
    $cont = $bt->getController();
}

$hih = Core::make('help/block_type');
$message = $hih->getMessage($bt->getBlockTypeHandle());

if (!$message && $cont->getBlockTypeHelp()) {
    $message = new Concrete\Core\Application\Service\UserInterface\Help\Message();
    $message->setIdentifier($bt->getBlockTypeHandle());
    $message->setMessageContent($cont->getBlockTypeHelp());
}

if (is_object($message) && !$bt->supportsInlineEdit()) {
    ?>
	<div class="dialog-help" id="ccm-menu-help-content"><?php echo $message->getContent() ?></div>
<?php 
} ?>

<div <?php if (!$bt->supportsInlineEdit()) {
    ?>ccm-ui"<?php 
} else {
    ?>data-container="inline-toolbar"<?php 
}
?>>
    <?php
    $method = 'submit';
    ?>
    <form method="post" id="ccm-block-form" class="validate" action="<?php echo $dialogController->action($method) ?>" enctype="multipart/form-data">
        <?php
        foreach ($this->controller->getJavaScriptStrings() as $key => $val) {
            ?><input type="hidden" name="ccm-string-<?php echo $key ?>" value="<?php echo h($val) ?>" /><?php 
        }
        if ($bt->supportsInlineEdit()) {
            $css = $b->getCustomStyle();
            ?>
            <div<?php if (is_object($css) && $b->getBlockTypeHandle() != BLOCK_HANDLE_LAYOUT_PROXY) {
                ?> class="<?php echo $css->getContainerClass() ?>"<?php 
            }
            ?>><?php
        } else {
            ?><div id="ccm-block-fields"><?php
        }
        
