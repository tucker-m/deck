<?php

namespace OCA\Deck\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OCA\Deck\Service\AttachmentService;
use OCA\Deck\Service\CardService;
use OCA\Deck\Service\PermissionService;
use OCP\Share\IShare;
use OCA\Deck\Sharing\ShareAPIHelper;
use OCP\Http\Client\IClientService;

final class AttachFile extends Command {

  public function __construct(
    CardService $cardService,
    AttachmentService $attachmentService,
    ShareAPIHelper $shareApiHelper,
    IClientService $clientService
  ) {

    $this->cardService = $cardService;
    $this->attachmentService = $attachmentService;
    $this->shareApiHelper = $shareApiHelper;
    $this->clientService = $clientService;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('deck:attach-file')
      ->setDescription('Attach a file to a card')
      ->addArgument(
        'cardId',
        InputArgument::REQUIRED,
        'Card Id'
      )
      ->addArgument(
        'filePath',
        InputArgument::REQUIRED,
        'Path to file to be shared with the card'
      )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $filePath = $input->getArgument('filePath');
    $shareType = IShare::TYPE_DECK;
    $cardId = $input->getArgument('cardId'); // this is the "shareWith" value

    $client = $this->clientService->newClient();
    

    return 0;
  }
}
