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
        // Si on a une demande d'envoi d'email
        if ($this->request->isParam('sendEmail')) {
            $contactService = new ContactService();
            $emailData = new EmailData($this->request->getValues());
            try {
                // Email valide
                if ($emailData->isValid()) {
                    // Email envoyé
                    if ($contactService->envoyerEmail($emailData)) {
                        $message = new Message('Votre message a été envoyé avec succès, merci !', OK);
                        $this->response->setFlash($message->toString());
                        $this->controller->redirect('contact.html');
                    }
                }
                $emailData->prepareToPrint();
            } catch (\Exception $e) {
                $emailData->prepareToPrintForm();
                $message = new Message($e->getMessage(), ERREUR);
                logThatException($e);
                $this->response->addVar('Message', $message->toString());
            }
        }
        
        $this->response->addVar('metaTitle', 'Nous contacter, nous trouver');
        $this->response->addVar('metaDesc', 'Si vous avez une question à propos du CEP Saint-Maur ou que vous désirez en savoir plus : n\'hésitez pas à nous rendre visite ou à nous contacter par email !');
        $this->response->addVar('metaKw', 'contact, plan, email');
        
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
        $this->response->render('contact/show', $tplPparams);
    }
}
