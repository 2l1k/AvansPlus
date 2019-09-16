<?php

namespace Admin\Policies;

use Admin\Http\Sections\LoanHistoryEvents;
use Admin\Http\Sections\Users;
use App\Model\LoanHistoryEvent;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanHistoryEventsSectionModelPolicy
{

    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     * @param Users $section
     * @param User $item
     *
     * @return bool
     */
    public function before(User $user, $ability, LoanHistoryEvents $section, LoanHistoryEvent $item = null)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Users $section
     * @param User $item
     *
     * @return bool
     */
    public function display(User $user, LoanHistoryEvents $section, LoanHistoryEvent $item)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Users $section
     * @param User $item
     *
     * @return bool
     */
    public function edit(User $user, LoanHistoryEvents $section, LoanHistoryEvent $item)
    {
        return false;
    }

    /**
     * @param User $user
     * @param Users $section
     * @param User $item
     *
     * @return bool
     */
    public function delete(User $user, LoanHistoryEvents $section, LoanHistoryEvent $item)
    {
        return true;
    }
}
