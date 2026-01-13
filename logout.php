<?php
include("funcs.php");
session_start_check();
session_destroy();
redirect("login.php");
