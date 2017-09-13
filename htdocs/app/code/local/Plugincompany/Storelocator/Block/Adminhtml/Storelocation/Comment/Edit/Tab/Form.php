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
 * Store location comment edit form tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Storelocator_Storelocation_Block_Adminhtml_Storelocation_Comment_Edit_Tab_Form
     * @author Milan Simek
     */
    protected function _prepareForm(){
        $storelocation = Mage::registry('current_storelocation');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset('comment_form', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Comment')));
        $fieldset->addField('storelocation_id', 'hidden', array(
            'name'  => 'storelocation_id',
            'after_element_html' => '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/storelocator_storelocation/edit', array('id'=>$storelocation->getId())).'" target="_blank">'.Mage::helper('plugincompany_storelocator')->__('Store Location').' : '.$storelocation->getLocname().'</a>'
        ));
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Comment'),
            'name'  => 'comment',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('rating', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Rating'),
            'name'  => 'rating',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Status'),
            'name'  => 'status',
            'required'  => true,
            'class' => 'required-entry',
            'values'=> array(
                array(
                    'value' => Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_PENDING,
                    'label' => Mage::helper('plugincompany_storelocator')->__('Pending'),
                ),
                array(
                    'value' => Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_APPROVED,
                    'label' => Mage::helper('plugincompany_storelocator')->__('Approved'),
                ),
                array(
                    'value' => Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_REJECTED,
                    'label' => Mage::helper('plugincompany_storelocator')->__('Rejected'),
                ),
            ),
        ));
        $configuration = array(
             'label' => Mage::helper('plugincompany_storelocator')->__('Submitter name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit', array('id'=>$comment->getCustomerId())).'" target="_blank">'.Mage::helper('plugincompany_storelocator')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Submitter e-mail address'),
            'name'  => 'email',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('customer_id', 'hidden', array(
            'name'  => 'customer_id',
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }
    /**
     * get the current comment
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation_Comment
     */
    public function getComment(){
        return Mage::registry('current_comment');
    }
}