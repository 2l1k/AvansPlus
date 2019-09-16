<?php

/**
 * @var \SleepingOwl\Admin\Contracts\Navigation\NavigationInterface $navigation
 * @see http://sleepingowladmin.ru/docs/menu_configuration
 */

use SleepingOwl\Admin\Navigation\Page;

$navigation->setFromArray([

    [
        'title' => "Заявки",
        'icon' => 'fa fa-credit-card',
        'priority' =>'5',
        'pages' => [
            (new Page(\App\Model\Borrower::class))
                ->setIcon('fa fa-credit-card')
                ->setPriority(0),
            (new Page(\App\Model\BorrowerLoan::class))
                ->setIcon('fa fa-credit-card')
                ->setPriority(0),
        ]
    ],

    (new Page(\App\Model\Bank::class))
        ->setIcon('fa fa-building')
        ->setPriority(10),

    (new Page(\App\Model\City::class))
        ->setIcon('fa fa-building')
        ->setPriority(10),

    (new Page(\App\Model\LoanHistoryEvent::class))
        ->setIcon('fa fa-bell')
        ->setPriority(20),

    (new Page(\App\Model\BorrowerBlackList::class))
        ->setIcon('fa fa-bell')
        ->setPriority(30),

  [
        'title' => 'Администраторы',
        'icon' => 'fa fa-group',
        'priority' =>'30',
        'pages' => [
            (new Page(\App\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0),
            (new Page(\App\Role::class))
                ->setIcon('fa fa-group')
                ->setPriority(100)
        ]
    ]
]);
