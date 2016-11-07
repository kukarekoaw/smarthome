<?php
/**
 * Created by PhpStorm.
 * User: kukareko
 * Date: 20.10.16
 * Time: 9:46
 */

namespace Api\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;


class MailController
{
    public function notify(Application $app, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('[SmartHome] Mail')
            ->setFrom(array('smarthome@aiiya.ru'), "SmartHomeAIIYA")
            ->setTo(array('kukarekoaw@gmail.com'));
            //->setBody($body);
            //->addPart("<q>$body</q>", 'text/html');

        $cid = $message->embed(\Swift_Image::fromPath('http://192.168.1.13:8181/images/smarthome.png'));

        $message->setBody(
            '<html>' .
            ' <head></head>' .
            ' <body>' .
            '  <center> <img src="' . $cid . '" alt="Image" /></center>' .
            "<q>  $body</q>" .
            ' </body>' .
            '</html>',
            'text/html' // Mark the content-type as HTML
        );

        $app['mailer']->send($message);
        return new Response('Thank you for your feedback!', 201);

    }

}