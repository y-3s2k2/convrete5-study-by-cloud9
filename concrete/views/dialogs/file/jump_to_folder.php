<?php defined('C5_EXECUTE') or die("Access Denied."); ?>


    <div class="ccm-ui">
        <div data-select="file-manager-navigation">
        </div>
    </div>

<script type="text/javascript">
    $(function() {
        $('[data-select=file-manager-navigation]').concreteTree({
            ajaxData: {
                displayOnly: 'file_folder'
            },
            treeNodeParentID: <?php echo $rootTreeNodeID?>,
            onClick : function(node) {
                jQuery.fn.dialog.closeTop();
                ConcreteEvent.publish('FileManagerJumpToFolder', {'node': node});

            },
        });
    });
</script>
