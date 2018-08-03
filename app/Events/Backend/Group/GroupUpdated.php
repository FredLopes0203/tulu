<?php

namespace App\Events\Backend\Group;

use Illuminate\Queue\SerializesModels;

/**
 * Class UserCreated.
 */
class GroupUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $group;

    /**
     * @param $user
     */
    public function __construct($group)
    {
        $this->group = $group;
    }
}
