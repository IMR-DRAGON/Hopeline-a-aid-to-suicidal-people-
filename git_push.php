<?php
header('Content-Type: text/plain');

$gitPath = '"f:\git bash\Git\cmd\git.exe"';
$commands = [
    "$gitPath status",
    "$gitPath add .",
    "$gitPath commit -m \"Update admin dashboard and security features\"",
    "$gitPath push origin main"
];

foreach ($commands as $cmd) {
    echo "Executing: $cmd\n";
    $output = shell_exec($cmd . ' 2>&1');
    echo $output . "\n";
    echo str_repeat("-", 40) . "\n";
}
?>
