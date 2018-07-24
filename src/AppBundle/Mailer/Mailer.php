<?php

namespace AppBundle\Mailer;

use Doctrine\ORM\EntityManagerInterface;
use FrontendBundle\Entity\ContactMessage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Mailer
 *
 * @package AppBundle\Mailer
 */
class Mailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $adminEmail = 'events@gowonderly.com';

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer          $mailer    A mailer instance.
     * @param string                 $fromEmail Sender email address.
     * @param string                 $fromName  Sender name.
     * @param UrlGeneratorInterface  $router    Router.
     * @param EntityManagerInterface $em        EntityManager.
     */
    public function __construct(
        \Swift_Mailer $mailer,
        string $fromEmail,
        string $fromName,
        UrlGeneratorInterface $router,
        EntityManagerInterface $em
    ) {
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @param ContactMessage $contactMessage A ContactMessage Instance.
     *
     * @return void
     */
    public function sendMessageOrganizator(ContactMessage $contactMessage)
    {
        $message = 'Name: ' . $contactMessage->getName()
            . '<br>'
            . 'Email: ' . $contactMessage->getEmail()
            . '<br>'
            . $contactMessage->getMessage();
        $organizer = $this->em->getRepository('UserBundle:Organize')->find($contactMessage->getSetTo());
        $this->sendMessage($organizer->getEmail(), $message);
    }


    /**
     * @param ContactMessage $contactMessage Message.
     * @param string         $slug           Slug.
     * @param integer        $id             Id event.
     *
     * @return void
     */
    public function sendMessageIncorrectEvent(ContactMessage $contactMessage, string $slug, int $id)
    {
        $message = 'Name: ' . $contactMessage->getName()
            . '<br>'
            . 'Email: ' . $contactMessage->getEmail()
            . '<br>'
            . 'Event: ' . $this->router->generate(
                'frontend_event_eventshow',
                array('slug' => $slug, 'id' => $id),
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            . '<br>'
            . $contactMessage->getMessage();

        $this->sendMessage($this->adminEmail, $message, 'Incorrect Event');
    }

    /**
     * @param string $setTo   Email address.
     * @param string $message Message.
     * @param string $subject Subject.
     *
     * @return void
     */
    public function sendMessage(string $setTo, string $message, string $subject = null)
    {
        $subject = ($subject === null) ? 'Wonderly Message' : $subject;

        $swiftMessage = \Swift_Message::newInstance($subject, $message)
            ->setTo($setTo)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setContentType('text/html');

        $this->mailer->send($swiftMessage);
    }
}
