<?php
require_once "Mage/Customer/controllers/AccountController.php";

class Jumbobag_Customer_AccountController extends Mage_Customer_AccountController
{
    const REDIRECT_FORGOTPASSWORD_SENT = '*/*/forgotpasswordsent';

    /**
     * Forgot customer password action
     */
    public function forgotPasswordPostAction()
    {
        $email = (string) $this->getRequest()->getPost('email');
        if ($email) {
            /**
             * @var $flowPassword Mage_Customer_Model_Flowpassword
             */
            $flowPassword = $this->_getModel('customer/flowpassword');
            $flowPassword->setEmail($email)->save();

            if (!$flowPassword->checkCustomerForgotPasswordFlowEmail($email)) {
                $this->_getSession()
                    ->addError($this->__('You have exceeded requests to times per 24 hours from 1 e-mail.'));
                $this->_redirect('*/*/forgotpassword');
                return;
            }

            if (!$flowPassword->checkCustomerForgotPasswordFlowIp()) {
                $this->_getSession()->addError($this->__('You have exceeded requests to times per hour from 1 IP.'));
                $this->_redirect('*/*/forgotpassword');
                return;
            }

            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getSession()->setForgottenEmail($email);
                $this->_getSession()->addError($this->__('Invalid email address.'));
                $this->_redirect('*/*/forgotpassword');
                return;
            }

            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken =  $this->_getHelper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
                } catch (Exception $exception) {
                    $this->_getSession()->addError($exception->getMessage());
                    $this->_redirect('*/*/forgotpassword');
                    return;
                }
            }
             $this->_getSession()->setForgotPasswordEmail($email);
            $this->_redirect(self::REDIRECT_FORGOTPASSWORD_SENT);
            return;
        } else {
            $this->_getSession()->addError($this->__('Please enter your email.'));
            $this->_redirect('*/*/forgotpassword');
            return;
        }
    }

    public function forgotpasswordsentAction()
    {
        if (!$this->_getSession()->getForgotPasswordEmail()) {
            $this->_redirect('*/*/forgotpassword');
            return;
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('forgotPasswordSent')->setEmailValue(
            $this->_getSession()->getForgotPasswordEmail()
        );

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

}
