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
 * Store Location EAV file field renderer helper
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Helper_File
    extends Varien_Data_Form_Element_Abstract {
    /**
     * constructor
     * @access public
     * @param array $data
     * @author Milan Simek
     */
    public function __construct($data){
        parent::__construct($data);
        $this->setType('file');
    }
    /**
     * get element html
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getElementHtml(){
        $html = '';
        $this->addClass('input-file');
        $html.= parent::getElementHtml();
        if ($this->getValue()) {
            $url = $this->_getUrl();
            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url = Mage::helper('plugincompany_storelocator/storelocationeav')->getFileBaseUrl() . $url;
            }
            $html .= '<br /><a href="'.$url.'">'.$this->_getUrl().'</a> ';
        }
        $html.= $this->_getDeleteCheckbox();
        return $html;
    }
    /**
     * get the delete checkbox HTML
     * @access protected
     * @return string
     * @author Milan Simek
     */
    protected function _getDeleteCheckbox(){
        $html = '';
        if ($this->getValue()) {
            $label = Mage::helper('plugincompany_storelocator')->__('Delete File');
            $html .= '<span class="delete-image">';
            $html .= '<input type="checkbox" name="'.parent::getName().'[delete]" value="1" class="checkbox" id="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' disabled="disabled"': '').'/>';
            $html .= '<label for="'.$this->getHtmlId().'_delete"'.($this->getDisabled() ? ' class="disabled"' : '').'> '.$label.'</label>';
            $html .= $this->_getHiddenInput();
            $html .= '</span>';
        }
        return $html;
    }
    /**
     * get the hidden input
     * @access protected
     * @return string
     * @author Milan Simek
     */
    protected function _getHiddenInput(){
        return '<input type="hidden" name="'.parent::getName().'[value]" value="'.$this->getValue().'" />';
    }
    /**
     * get the file url
     * @access protected
     * @return string
     * @author Milan Simek
     */
    protected function _getUrl(){
        return $this->getValue();
    }
    /**
     * get the name
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getName(){
        return $this->getData('name');
    }
}
