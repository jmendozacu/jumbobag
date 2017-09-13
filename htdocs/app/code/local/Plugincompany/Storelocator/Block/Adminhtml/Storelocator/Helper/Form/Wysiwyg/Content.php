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
 * wysiwyg helper
 * @category   Plugincompany
 * @package    Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocator_Helper_Form_Wysiwyg_Content
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * Prepare form.
     * Adding editor field to render
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocator_Helper_Form_Wysiwyg_Content
     * @author Milan Simek
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form(array('id' => 'wysiwyg_edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $config['document_base_url']     = $this->getData('store_media_url');
        $config['store_id']              = $this->getData('store_id');
        $config['add_variables']         = false;
        $config['add_widgets']           = false;
        $config['add_directives']        = true;
        $config['use_container']         = true;
        $config['container_class']       = 'hor-scroll';
		$editorConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig($config);
		$editorConfig->setData('files_browser_window_url', Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'));
        $form->addField($this->getData('editor_element_id'), 'editor', array(
            'name'      => 'content',
            'style'     => 'width:725px;height:460px',
            'required'  => true,
            'force_load' => true,
            'config'    => $editorConfig
        ));
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
