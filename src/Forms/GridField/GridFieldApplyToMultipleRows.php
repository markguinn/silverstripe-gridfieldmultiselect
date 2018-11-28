<?php
/**
 * 
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 12.22.2014
 * @package gridfieldmultiselect
 */
class GridFieldApplyToMultipleRows implements GridField_HTMLProvider, GridField_ActionProvider, GridField_URLHandler
{
    /** @var callable - this will be called for every row */
    protected $rowHandler;

    /** @var string */
    protected $targetFragment;

    /** @var string */
    protected $buttonText;

    /** @var string */
    protected $actionName;

    /** @var array */
    protected $buttonConfig;


    /**
     * @param string $actionName
     * @param string $buttonText
     * @param callable $rowHandler
     * @param string $targetFragment
     * @param array $buttonConfig - icon, class, possibly others
     */
    public function __construct($actionName, $buttonText, $rowHandler, $targetFragment = 'after', $buttonConfig = array())
    {
        $this->actionName = $actionName;
        $this->buttonText = $buttonText;
        $this->rowHandler = $rowHandler;
        $this->targetFragment = $targetFragment;
        $this->buttonConfig = $buttonConfig;
    }


    /**
     * Returns a map where the keys are fragment names and the values are
     * pieces of HTML to add to these fragments.
     *
     * Here are 4 built-in fragments: 'header', 'footer', 'before', and
     * 'after', but components may also specify fragments of their own.
     *
     * To specify a new fragment, specify a new fragment by including the
     * text "$DefineFragment(fragmentname)" in the HTML that you return.
     *
     * Fragment names should only contain alphanumerics, -, and _.
     *
     * If you attempt to return HTML for a fragment that doesn't exist, an
     * exception will be thrown when the {@link GridField} is rendered.
     *
     * @param GridField $gridField
     * @return array
     */
    public function getHTMLFragments($gridField)
    {
        $button = new GridField_FormAction($gridField, $this->actionName, $this->buttonText, $this->actionName, null);
        $button->addExtraClass('multiselect-button');

        if (!empty($this->buttonConfig['icon'])) {
            $button->setAttribute('data-icon', $this->buttonConfig['icon']);
        }

        if (!empty($this->buttonConfig['class'])) {
            $button->addExtraClass($this->buttonConfig['class']);
        }

        if (!empty($this->buttonConfig['confirm'])) {
            $button->setAttribute('data-confirm', $this->buttonConfig['confirm']);
        }

        return array(
            $this->targetFragment => $button->Field(),
        );
    }


    /**
     * Return a list of the actions handled by this action provider.
     *
     * Used to identify the action later on through the $actionName parameter
     * in {@link handleAction}.
     *
     * There is no namespacing on these actions, so you need to ensure that
     * they don't conflict with other components.
     *
     * @param GridField
     * @return Array with action identifier strings.
     */
    public function getActions($gridField)
    {
        return array($this->actionName);
    }


    /**
     * Handle an action on the given {@link GridField}.
     *
     * Calls ALL components for every action handled, so the component needs
     * to ensure it only accepts actions it is actually supposed to handle.
     *
     * @param GridField
     * @param String Action identifier, see {@link getActions()}.
     * @param Array  Arguments relevant for this
     * @param Array  All form data
     * @return array
     */
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName === $this->actionName) {
            return $this->handleIt($gridField, $data);
        }
    }


    /**
     * Return URLs to be handled by this grid field, in an array the same form
     * as $url_handlers.
     *
     * Handler methods will be called on the component, rather than the
     * {@link GridField}.
     */
    public function getURLHandlers($gridField)
    {
        return array(
            $this->actionName => 'handleIt'
        );
    }


    /**
     * @param GridField $gridField
     * @param array|SS_HTTPRequest $data
     * @return array
     */
    public function handleIt($gridField, $data = array())
    {
        if ($data instanceof SS_HTTPRequest) {
            $data = $data->requestVars();
        }

        // Separate out the ID list from the checkboxes
        $fieldName = GridFieldCheckboxSelectComponent::CHECKBOX_COLUMN;
        $ids = isset($data[$fieldName]) && is_array($data[$fieldName]) ? $data[$fieldName] : array();
        $class = $gridField->getModelClass();
        if (!$class) {
            user_error('No model class is defined!');
        }

        $response = array();

        // Hook for subclasses
        $this->onBeforeList($gridField, $data, $ids);

        if (empty($ids)) {
            $this->onEmptyList($gridField, $response, $data, $ids);
            $records = new ArrayList();
        } else {
            $records = DataObject::get($class)->filter('ID', $ids);
            foreach ($records as $index => $record) {
                call_user_func($this->rowHandler, $record, $index);
            }
        }

        $this->onAfterList($gridField, $response, $records, $data, $ids);
        return $response;
    }


    /**
     * Hook for subclasses
     * @param GridField $gridField
     * @param array $data
     * @param array $idList
     */
    protected function onBeforeList($gridField, $data, $idList)
    {
    }


    /**
     * This allows subclasses to have a hook at the end of running through
     * all the items. Response will usually be an array on the way in
     * but it can be changed to whatever and will be returned as is.
     * @param GridField $gridField
     * @param array|SS_HTTPResponse $response
     * @param SS_List $records
     * @param array $data
     * @param array $idList
     */
    protected function onAfterList($gridField, &$response, $records, $data, $idList)
    {
    }


    /**
     * @param GridField $gridField
     * @param array|SS_HTTPResponse $response
     * @param array $data
     * @param array $idList
     */
    protected function onEmptyList($gridField, &$response, $data, $idList)
    {
    }
}
