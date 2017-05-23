<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SendEmailCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:send-emails')
            ->setDescription('Sends email to User about new comments to their offers')
            ->setHelp('Look at description');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Emails will be send.',
        ]);

        $em = $this->getContainer()->get('doctrine')->getManager();

        $comments = $em->getRepository('AppBundle:Comment')->findNotSend();

        $mailer = $this->getContainer()->get('mailer');

        $sendEmailsCount = 0;
        $sendEmailsErrorsCount = 0;

        foreach ($comments as $comment) {

            $offer = $comment->getOffer();
            $offerOwner = $offer->getUser();

            $message = \Swift_Message::newInstance()
                ->setSubject('New Comment Offer: ' . $offer->getId())
                ->setFrom('adPutter.team@adputter.com')
                ->setTo($offerOwner->getEmail())
                ->setBody($comment->getText());/*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'Emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */;
            try {

                $mailer->send($message);
                $comment->setEmailSend(true);
                $em->flush();
                $sendEmailsCount ++;

            } catch (Exception $e) {
                $sendEmailsErrorsCount++;
            }
        }

        $output->writeln([
            'Emails send: ' . $sendEmailsCount,
            'Problems with sending: ' . $sendEmailsErrorsCount
        ]);
    }
}
