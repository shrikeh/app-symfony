<?php
// phpcs:ignoreFile
declare(strict_types=1);

use App\Kernel;

defined('PROJECT_ROOT_DIR') || define('PROJECT_ROOT_DIR', 3);

require_once dirname(__DIR__, PROJECT_ROOT_DIR) . '/vendor/autoload_runtime.php';

return function (array $context): Kernel {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
