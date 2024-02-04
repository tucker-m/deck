<?php

namespace OCA\Deck\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OCA\Deck\Service\CardService;
use OCA\Deck\Service\BoardService;
use OCA\Deck\Service\PermissionService;

final class CreateCard extends Command {

  public function __construct(CardService $cardService, BoardService $boardService, PermissionService $permissionService, ?string $userId) {

    $this->cardService = $cardService;
    $this->boardService = $boardService;
    $this->permissionService = $permissionService;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('deck:create-card')
      ->setDescription('Create a card on a board')
      ->addArgument(
        'stackId',
        InputArgument::REQUIRED,
        'Stack Id'
      )
      ->addArgument(
        'cardTitle',
        InputArgument::REQUIRED,
        'Card title'
      )
      ->addArgument(
        'userId',
        InputArgument::REQUIRED,
        'User Id'
      )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $stackId = $input->getArgument('stackId');
    $title = $input->getArgument('cardTitle');
    $this->userId = $input->getArgument('userId');
    $this->boardService->setUserId($this->userId);

    $this->permissionService->setUserId($this->userId);

    $card = $this->cardService->create($title, $stackId, 'plain', 999, $this->userId, '', null);

    return 0;
  }
}
