<?php

namespace Admin\Policies;

use Admin\Http\Sections\BorrowerLoansHistory;
use Admin\Http\Sections\Users;
use App\Model\LoansByBorrower;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowerLoansHistorySectionModelPolicy
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
    public function before(User $user, $ability, BorrowerLoansHistory $section, LoansByBorrower $item = null)
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
    public function display(User $user, BorrowerLoansHistory $section, LoansByBorrower $item)
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
    public function edit(User $user, BorrowerLoansHistory $section, LoansByBorrower $item)
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
    public function delete(User $user, BorrowerLoansHistory $section, LoansByBorrower $item)
    {
        return false;
    }
}
