<?php
namespace Controller;

use Core\Action;
use Core\Message;
use Model\EmailData;
use Service\ContactService;

class Contact extends Action
{

    public function show($params = array())
    {
        $request = $this->controller->getRequest();
        // Si on a une demande d'envoi d'email
        if ($request->isParam('sendEmail')) {
            $contactService = new ContactService();
            $emailData = new EmailData($request->getParams());
            try {
                // Email valide
                if ($emailData->isValid()) {
                    // Email envoyé
                    if ($contactService->envoyerEmail($emailData)) {
                        $message = new Message('Votre message a été envoyé avec succès, merci !', OK);
                        $this->controller->getResponse()->setFlash($message->toString());
                        $this->controller->redirect('contact.html');
                    }
                }
                $emailData->prepareToPrint();
            } catch (\Exception $e) {
                $emailData->prepareToPrintForm();
                $message = new Message($e->getMessage(), ERREUR);
                logThatException($e);
                $this->controller->getResponse()->addVar('Message', $message->toString());
            }
        }
        
        $this->controller->getResponse()->addVar('metaTitle', 'Nous contacter, nous trouver');
        $this->controller->getResponse()->addVar('metaDesc', 'Si vous avez une question à propos du CEP Saint-Maur ou que vous désirez en savoir plus : n\'hésitez pas à nous rendre visite ou à nous contacter par email !');
        $this->controller->getResponse()->addVar('metaKw', 'contact, plan, email');
        
        // -- Create params
        $tplPparams = array(
            'urlCourant' => getUrlCourant('contact.html'),
            'EmailContact' => EmailContact,
            'UrlImgEmailContact' => getUrlImgEmail(EmailContact),
            'EmailFlambeaux' => EmailFlambeaux,
            'UrlImgEmailFlambeaux' => getUrlImgEmail(EmailFlambeaux),
            'EmailGDJ' => EmailGDJ,
            'UrlImgEmailGDJ' => getUrlImgEmail(EmailGDJ),
            'email' => @$emailData
        );
        
        // -- Fill the body and print the page
        $this->controller->render('contact/layout-contact.tpl', $tplPparams);
        $this->printOut();
    }
}
?>
