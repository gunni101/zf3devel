<?php
namespace GSMail\Service;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class GSMailManager extends AbstractActionController
{
    private $mailConfig;
    
    public function __construct($mailConfig)
    {
        $this->mailConfig = $mailConfig;
    }
    
    
    public function gsMail(array $data = null, $template = null, $bodyData = null)
    {
        if($this->mailConfig['type'] == 'smtp') {
            $transport = new SmtpTransport(new SmtpOptions($this->mailConfig['smtp_server']));
        } elseif ($this->mailConfig['type'] == 'sendmail') {
            $transport = new SendmailTransport();
        } else {
            // fallback transport if nothing is defined 
            $transport = new SendmailTransport();
        }
            
        if(!is_array($data))
            return false;
        
        if(is_array($template)){
            $this->setTemplate($template, $bodyData);
            
            $mail = new Message();
            $mail->setBody($this->setMimeBody());
            
        } else {
            $mail = new Message();
            $mail->setBody($data['body']);
        }
        
        $mail->setEncoding($this->mailConfig['charset']);
        
        foreach ($this->mailConfig['from'] as $email => $name) 
        {
            $mail->setFrom($email, $name);
            break;
        }
        
        foreach ($this->mailConfig['reply_to'] as $email => $name)
        {
            $mail->setReplyTo($email, $name);
        }
        
        foreach ($data['to'] as $email => $name) {
            $mail->addTo($email, $name);
        }

        $mail->setSubject($data['subject']);
        
        $transport->send($mail);
    }
    
    public function setTemplate($template, $bodyData = null) {
        
        $resolver   = new TemplateMapResolver();
        $resolver->setMap($template);
        
        $this->view   = new PhpRenderer();
        $this->view->setResolver($resolver);

        $this->viewModel = new ViewModel(['data' => $bodyData]);
        $this->viewModel->setTemplate(key($template));
    }
    
    public function setMimeBody()
    {
        $bodyMessage = new MimePart($this->view->render($this->viewModel));
        $bodyMessage->type = 'text/html; charset=utf-8';
        
        $body = new MimeMessage();
        $body->setParts(array($bodyMessage));

        return $body;
    }
}