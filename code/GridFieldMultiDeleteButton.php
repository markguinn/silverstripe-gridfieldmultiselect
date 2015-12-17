<?php
/**
 * Button to delete every checked row. The only confirmation
 * would be via javascript.
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 12.22.2014
 * @package gridfieldmultiselect
 */
class GridFieldMultiDeleteButton extends GridFieldApplyToMultipleRows
{
    /**
     * Shortcut to create a button that deletes all selected entries
     */
    public function __construct($targetFragment = 'after')
    {
        parent::__construct('deleteselected', _t('GridFieldMultiDeleteButton.ButtonText', 'Delete Selected'), array($this, 'deleteRecord'), $targetFragment, array(
            'icon'    => 'delete',
            'class'   => 'deleteSelected',
            'confirm' => _t('GridFieldMultiDeleteButton.Confirm', 'Are you sure you want to delete all selected items?'),
        ));
    }


    /**
     * @param DataObject $record
     * @param int $index
     */
    public function deleteRecord($record, $index)
    {
        if ($record->hasExtension('Versioned')) {
            $record->deleteFromStage('Stage');
            $record->deleteFromStage('Live');
        } else {
            $record->delete();
        }
    }
}
