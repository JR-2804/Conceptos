<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProductsStatusCommand extends ContainerAwareCommand
{
  private $daysUntilProductIsNotRecent = 60;

  protected function configure()
  {
    parent::configure();
    $this
      ->setName('app:update_product_status')
      ->setDescription('Command to update the status of products');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $products = $entityManager->getRepository('AppBundle:Product')->findAll();

    foreach($products as $product) {
      $this->UpdateRecentStatus($product);
    }
    $entityManager->flush();
    $output->writeln("Product status updated!");
  }

  private function UpdateRecentStatus($product) {
    $timeFromCreation = $product->getCreationDate()->diff(new \DateTime('now'));
    if ($timeFromCreation->days > $this->daysUntilProductIsNotRecent) {
      $product->setRecent(false);
    }
  }
}
