<?php
namespace Controller;

use Core\Action;
use Model\EmailData;

class Contact extends Action
{

    public function show($values = array())
    {
        $content = $this->content->getByUrl('contact');
        $this->response->addVar('metaTitle', $content['title']);
        $this->response->addVar('metaDesc', $content['abstract']);
        $this->response->addVar('metaKw', $content['keywords']);
        
        $params = array(
            'page' => $content,
            'EmailContact' => EmailContact,
            'UrlImgEmailContact' => getUrlImgEmail(EmailContact),
            'EmailFlambeaux' => EmailFlambeaux,
            'UrlImgEmailFlambeaux' => getUrlImgEmail(EmailFlambeaux),
            'EmailGDJ' => EmailGDJ,
            'UrlImgEmailGDJ' => getUrlImgEmail(EmailGDJ),
            'email' => $values
        );
        
        return $this->response->render('contact/show', $params);
    }

    public function send($params = array())
    {
        if ($this->request->isPost()) {
            $params = new EmailData($this->request->getValues());
            try {
                if ($params->isValid() && $this->mailer->sendEmail($params)) {
                    $this->response->addFlash('Votre message a été envoyé avec succès, merci !', OK);
                    return $this->response->redirect('contact.html#email');
                }
            } catch (\Exception $e) {
                $params->prepareToPrintForm();
                logThatException($e);
                $this->response->addFlash($e->getMessage(), ERREUR);
            }
        }
        else {
            $this->response->addFlash('L\'email ne peut être vide', ERREUR);
        }
        return $this->show($params);
    }
}
