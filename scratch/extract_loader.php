<?php
$transcriptPath = 'C:/Users/ss487/.gemini/antigravity-ide/brain/e51dae96-fe58-4088-8ca0-ab2fe245e817/.system_generated/logs/transcript_full.jsonl';
$handle = fopen($transcriptPath, 'r');
if (!$handle) {
    die("Cannot open transcript");
}

$loaderCSS = '';
$loaderHTML = '';
$loaderJS = '';

while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'page-switch-loader') !== false || strpos($line, 'Oceanic & Frozen Seafood') !== false) {
        $json = json_decode($line, true);
        if ($json) {
            $content = $json['content'] ?? '';
            if (isset($json['tool_calls'])) {
                foreach ($json['tool_calls'] as $tc) {
                    $args = json_encode($tc['args'] ?? []);
                    if (strpos($args, 'page-switch-loader') !== false) {
                        file_put_contents('scratch/loader_tool_call_' . ($json['step_index'] ?? rand()) . '.txt', $args);
                    }
                }
            }
            if (strpos($content, 'page-switch-loader') !== false && strlen($content) > 200) {
                file_put_contents('scratch/loader_content_' . ($json['step_index'] ?? rand()) . '.txt', $content);
            }
        }
    }
}
fclose($handle);
echo "Extraction completed.\n";
