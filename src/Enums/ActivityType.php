<?php
declare(strict_types = 1);

namespace Sourcetoad\Logger\Enums;

final class ActivityType
{
    const FAILED_LOGIN = 0;

    const LOGOUT = 1;

    const SUCCESSFUL_LOGIN = 2;

    const LOCKED_OUT = 3;

    const PASSWORD_CHANGE = 4;

    const GET_DATA = 5;

    const MODIFY_DATA = 6;
}
