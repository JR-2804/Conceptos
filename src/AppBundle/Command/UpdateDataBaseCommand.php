<?php


namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDataBaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('app:db:update')
            ->setDescription('Command to update APP database from data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Configuration')->find(1);
        if ($config->getLastProductUpdate() > $config->getLastDatabaseExport()) {
            $this->getContainer()->get('export_service')->export($this->getContainer()->getParameter("kernel.root_dir"));
        }
    }


}