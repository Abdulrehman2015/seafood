<?php
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);
$html = @file_get_contents('http://127.0.0.1:8000', false, $context);
$headers = $http_response_header ?? [];
echo "Status line: " . ($headers[0] ?? 'No response') . "\n";
echo "HTML Length: " . strlen($html) . " bytes\n";
if (str_contains($html, 'MST Import and Export') || str_contains($html, 'Products')) {
    echo "Page rendered successfully!\n";
}
