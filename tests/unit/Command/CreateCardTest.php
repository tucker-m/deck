<?php
/**
 * @copyright Copyright (c) 2018 Julius Härtl <jus@bitgrid.net>
 *
 * @author Julius Härtl <jus@bitgrid.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Deck\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OCA\Deck\Service\CardService;
use OCA\Deck\Service\BoardService;
use OCA\Deck\Service\CardMapper;
use OCA\Deck\Db\Card;
use OCA\Deck\Db\Stack;

class CreateCardTest extends \Test\TestCase {
	protected $appManager;

	public function setUp(): void {
		parent::setUp();
		$this->boardService = $this->createMock(BoardService::class);
		$this->cardService = $this->createMock(CardService::class);
		$this->createCard = new CreateCard($this->cardService, $this->boardService);

		$this->cardService->expects($this->any())
        		->method('create')
        		->willReturn($this->getCard(1));
	}

	public function getStack($id) {
		$stack = new Stack();
		$stack->setId($id);
		$stack->setTitle('Stack ' . $id);
		return $stack;
	}
	public function getCard($id) {
		$card = new Card();
		$card->setId($id);
		$card->setTitle('Card ' . $id);
		return $card;
	}

	public function testExecute() {
        	$stack = $this->getStack(1);
		$input = $this->createMock(InputInterface::class);
		$input->expects($this->exactly(3))
        		->method('getArgument')
        		->willReturnCallback(function($arg): string {
                		if ($arg == 'userId') {
                        		return 'admin';
                		}
                		if ($arg == 'stackId') {
                        		return '1';
                		}
                		else {
                        		return "some new card";
                		}
        		});
                		
		$output = $this->createMock(OutputInterface::class);

                $this->cardService->expects($this->once())
                  ->method('create')
                  ->with('some new card', 1, 'plain', 999, 'admin', '', null);

		$result = $this->invokePrivate($this->createCard, 'execute', [$input, $output]);
	}
}
