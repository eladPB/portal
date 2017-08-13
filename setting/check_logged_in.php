<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 22/05/2016
 * Time: 19:43
 */

if (isset($_SESSION['user'])):
    echo $lang['USER_IS_NOT_LOGGED_IN'];
else:
    echo $lang['USER_IS_LOGGED_IN'];
endif;