<?php
namespace YABA;

function logged_in() {
  return isset($_SESSION['user_id']);
}