<?php
require 'sessao.php';
 
session_destroy();
 
header('Location: login.php');
exit;
 