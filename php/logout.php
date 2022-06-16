<?php

require_once "../config.php";

// logout
session_start();
session_destroy();
header("Location: ../forms/login.html");
