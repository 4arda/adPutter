<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
        $comments = $em->getRepository('AppBundle:Comment')->findBy([
            'createDate' => true
        ]);

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('example@example.com')
            ->setBody('body body body alwyas body');
        /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $mailer = $this->getContainer()->get('mailer');
        $mailer->send($message);
    }
}
