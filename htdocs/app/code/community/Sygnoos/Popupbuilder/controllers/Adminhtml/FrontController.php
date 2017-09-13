<?php
require_once(Mage::getModuleDir('', 'Sygnoos_Popupbuilder').DS.'public'.DS.'boot.php');
class Sygnoos_Popupbuilder_Adminhtml_FrontController extends Mage_Core_Controller_Front_Action
{
    public function subsAjaxAction()
    {
        $model = Mage::getModel("popupbuilder/Subscribers");
        parse_str($_POST['serialize'], $subsribers);
        $email     = $subsribers['subs-email-name'];
        $firstName = $subsribers['subs-first-name'];
        $lastName  = $subsribers['subs-last-name'];
        $title     = $subsribers['subs-popup-title'];
        echo $email;
        $data       = $model->getCollection();
        $matchEmail = $data->addFieldToFilter("email", array(
            'eq' => "$email"
        ))->getData();
        var_dump($matchEmail);
        if (!empty($matchEmail)) {
            return;
        }
        $data = array(
            'id' => $id,
            "firstName" => $firstName,
            "lastName" => $lastName,
            'email' => $email,
            'subscriptionType' => $title
        );
        $model->setData($data);
        $query = $model->save();
        die();
    }
    public function contactFormAjaxAction()
    {
        parse_str($_POST['serialize'], $params);
        $name        = $params['contact-name'];
        $subject     = $params['contact-subject'];
        $userMessage = $params['content-message'];
        $mail        = $params['contact-email'];
        $popupId = $params['popup-id'];

        $model = Mage::getModel("popupbuilder/Sgcontactformpopup");
        $arr   = $model->load($popupId)->getData();
        $options = json_decode($arr['options'], true);

        $resiveEmail = $options['contact-resive-email'];
   
        $message     = '';
        $message .= '<b>Name</b>: ' . $name . "<br>";
        $message .= '<b>E-mail</b>: ' . $mail . "<br>";
        $message .= '<b>Subject</b>: ' . $subject . "<br>";
        $message .= '<b>Message</b>: ' . $userMessage . "<br>";
        $mailObj = Mage::getModel('core/email');
        $mailObj->setToName("$name");
        $mailObj->setToEmail($resiveEmail);
        $mailObj->setBody($message);
        $mailObj->setSubject("$subject");
        $mailObj->setFromEmail($mail);
        $mailObj->setFromName($resiveEmail);
        $mailObj->setType('html'); // You can use 'html' or 'text'
    
        try {
            $mailObj->send();
            echo true;
            die();
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
        }
        catch (Exception $e) {
            echo 'Unable to send.';
            die();
            Mage::getSingleton('core/session')->addError('Unable to send.');
        }
    }
}