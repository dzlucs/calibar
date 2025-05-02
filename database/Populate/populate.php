<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;

Database::migrate();
