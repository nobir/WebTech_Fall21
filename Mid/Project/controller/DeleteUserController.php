<?php

/**
 * Can access direcly by URL
 */

define("_DIRECT_ACCESS", true);

require_once dirname(dirname(__FILE__)) . "/helper/functions.php";

if (!_get_is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

if (_get_session_val('utype') != "admin") {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_POST['deleteuser'])) {
    // _var_dump($_POST);
    // return;

    $has_err = false;
    $messages = [
        "errors" => [],
        "success" => [],
        "data" => []
    ];

    $email = "";

    // Email
    if (empty($_POST['email'])) {
        $messages["errors"][] = "Email is required";
        $has_err = true;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $messages["errors"][] = "Email is not valid";
        $has_err = true;
    } else {
        $email = validate_input($_POST['email']);
        $messages['data']['email'] = $email;
    }

    if (!$has_err) {
        require_once _ROOT_DIR . "model/UserModel.php";

        $is_deleted = _delete_user($messages, $email);
        // return;
        if ($is_deleted) {
            $messages["success"][] = "Successfully Deleted";
            _set_session_val("messages", $messages);
            header("Location: ../user/admin/delete-user.php");
        } else {
            $messages["errors"][] = "Unsuccessfull to delete the user";
            _set_session_val("messages", $messages);
            header("Location: ../user/admin/delete-user.php");
        }
    } else {
        _set_session_val("messages", $messages);
        header("Location: ../user/admin/delete-user.php");
        exit();
    }
} else {
    header("Location: ../user/admin/delete-user.php");
    exit();
}
