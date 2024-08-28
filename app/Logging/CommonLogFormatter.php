<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class CommonLogFormatter extends LineFormatter
{
    public function __construct()
    {
        // Define a basic format string that will be manually replaced
        $format = "%remote_addr% - %user% (%role%) [%datetime%] \"%method% %url% HTTP/%http_version%\" %status% %response_size%\n";
        parent::__construct(null, null, false, true);
        $this->format = $format;
    }

    public function format(LogRecord $record): string
    {
        // Extract the context values and set defaults if not present
        $remoteAddr = $record->context['remote_addr'] ?? '-';
        $user = $record->context['user'] ?? '-';
        $role = $record->context['role'] ?? '-';
        $method = $record->context['method'] ?? '-';
        $url = $record->context['url'] ?? '-';
        $httpVersion = $record->context['http_version'] ?? '-';
        $status = $record->context['status'] ?? '-';
        $responseSize = $record->context['response_size'] ?? '-';

        // Manually replace the placeholders with actual values
        $output = str_replace(
            ['%remote_addr%', '%user%', '%role%', '%method%', '%url%', '%http_version%', '%status%', '%response_size%'],
            [$remoteAddr, $user, $role, $method, $url, $httpVersion, $status, $responseSize],
            $this->format
        );

        // Replace the datetime in the format string
        $output = str_replace('%datetime%', $record->datetime->format($this->dateFormat), $output);

        return $output;
    }
}
