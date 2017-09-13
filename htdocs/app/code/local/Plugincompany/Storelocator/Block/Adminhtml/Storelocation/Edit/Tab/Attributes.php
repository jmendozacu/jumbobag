<?php
 /*
 * Created by:  Milan Simek
 * Company:     Plugin Company
 *
 * LICENSE: http://plugin.company/docs/magento-extensions/magento-extension-license-agreement
 *
 * YOU WILL ALSO FIND A PDF COPY OF THE LICENSE IN THE DOWNLOADED ZIP FILE
 *
 * FOR QUESTIONS AND SUPPORT
 * PLEASE DON'T HESITATE TO CONTACT US AT:
 *
 * SUPPORT@PLUGIN.COMPANY
 */
/**
 * Storelocationeav admin edit tab attributes block
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
*/
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Attributes
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the attributes for the form
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     * @author Milan Simek
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('storelocation_');
        $form->setFieldNameSuffix('storelocation');
        $form->setDataObject(Mage::registry('current_storelocation'));
        $fieldset = $form->addFieldset('attributes',
            array(
                'legend'=>Mage::helper('plugincompany_storelocator')->__('Custom Store Attributes'),
//                 'class'=>'fieldset-wide',
            )
        );

        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute){
            $attribute->setEntity(Mage::getResourceModel('plugincompany_storelocator/storelocationeav'));
        }
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::registry('current_storelocation')->getData();
        if (!Mage::registry('current_storelocation')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        $form->addValues($formValues);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /**
     * prepare layout
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareLayout()
     * @author Milan Simek
     */
    protected function _prepareLayout() {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocator_renderer_fieldset_element')
        );
        return parent::_prepareLayout();
    }
    /**
     * get the additional element types for form
     * @access protected
     * @return array()
     * @see Mage_Adminhtml_Block_Widget_Form::_getAdditionalElementTypes()
     * @author Milan Simek
     */
    protected function _getAdditionalElementTypes(){
        $elements = array(
            'file'    => Mage::getConfig()->getBlockClassName('plugincompany_storelocator/adminhtml_storelocationeav_helper_file'),
            'image' => Mage::getConfig()->getBlockClassName('plugincompany_storelocator/adminhtml_storelocationeav_helper_image'),
            'textarea' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_helper_form_wysiwyg'),
            'boolean' => 'Varien_Data_Form_Element_Select'
        );
        return $elements;
    }
    /**
     * get current entity
     * @access protected
     * @return Plugincompany_Storelocator_Model_Storelocationeav
     * @author Milan Simek
     */
    public function getStorelocationeav() {
        return Mage::registry('current_storelocation');
    }
    /**
     * get after element html
     * @access protected
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * @author Milan Simek
     */
    protected function _getAdditionalElementHtml($element) {
        if ($element->getName() == 'storelocation_id') {
            $html = '<a href="{#url}" id="storelocation_id_link" target="_blank"></a>';
            $html .= '<script type="text/javascript">
            function changeStorelocationIdLink(){
                if ($(\'storelocation_id\').value == \'\') {
                    $(\'storelocation_id_link\').hide();
                }
                else {
                    $(\'storelocation_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/storelocator_storelocation/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'storelocation_id\').value);
                    $(\'storelocation_id_link\').href = realUrl;
                    $(\'storelocation_id_link\').innerHTML = text.replace(\'{#name}\', $(\'storelocation_id\').options[$(\'storelocation_id\').selectedIndex].innerHTML);
                }
            }
            $(\'storelocation_id\').observe(\'change\', changeStorelocationIdLink);
            changeStorelocationIdLink();
            </script>';
            return $html;
        }
        return '';
    }

    /**
     * Set Fieldset to Form
     *
     * @param array $attributes attributes that are to be added
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array $exclude attributes that should be skipped
     */
    protected function _setFieldset($attributes, $fieldset, $exclude=array())
    {
        $this->_addElementTypes($fieldset);
        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            if (!$attribute || ($attribute->hasIsVisible() && !$attribute->getIsVisible())) {
                continue;
            }
            if ( ($inputType = $attribute->getFrontend()->getInputType())
                && !in_array($attribute->getAttributeCode(), $exclude)
                && ('media_image' != $inputType)
            ) {

                $fieldType      = $inputType;
                $rendererClass  = $attribute->getFrontend()->getInputRendererClass();
                if (!empty($rendererClass)) {
                    $fieldType  = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }

                $element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,
                    array(
                        'name'      => $attribute->getAttributeCode(),
                        'label'     => $attribute->getFrontend()->getLabel(),
                        'class'     => $attribute->getFrontend()->getClass(),
                        'required'  => $attribute->getIsRequired(),
                        'note'      => $attribute->getNote(),
                    )
                )
                    ->setEntityAttribute($attribute);

                $element->setAfterElementHtml($this->_getAdditionalElementHtml($element));

                if ($inputType == 'select' || $inputType == 'boolean') {
                    $element->setValues($attribute->getSource()->getAllOptions(true, true));
                } else if ($inputType == 'multiselect') {
                    $element->setValues($attribute->getSource()->getAllOptions(false, true));
                    $element->setCanBeEmpty(true);
                } else if ($inputType == 'date') {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormatWithLongYear());
                } else if ($inputType == 'datetime') {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setTime(true);
                    $element->setStyle('width:50%;');
                    $element->setFormat(
                        Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
                    );
                } else if ($inputType == 'multiline') {
                    $element->setLineCount($attribute->getMultilineCount());
                }
            }
        }
    }
}
