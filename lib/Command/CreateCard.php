<?php

namespace OCA\Deck\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OCA\Deck\DB\Card;
use OCA\Deck\Service\CardService;
use OCA\Deck\Service\BoardService;

final class CreateCard extends Command {

  public function __construct(CardService $cardService, BoardService $boardService) {

    $this->cardService = $cardService;
    $this->boardService = $boardService;
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

  protected function createCard(string $userId, int $stackId, string $cardTitle): Card {
    $card = $this->cardService->create($cardTitle, $stackId, 'plain', 999, $userId, '', null);

    return $card;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $stackId = $input->getArgument('stackId');
    $title = $input->getArgument('cardTitle');
    $this->userId = $input->getArgument('userId');
    $this->boardService->setUserId($this->userId);

    $card = $this->createCard($this->userId, $stackId, $title);

    $output->writeln(json_encode($card));
    return 0;
  }
}
