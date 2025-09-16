<?php
// index.php
// Simple MD5 "PIN cracker" — tries all 0000..9999 PINs.
// Educational/demo use only. Respect your course honor code.

function try_crack($md5_target) {
    $start = microtime(true);

    // Try numeric PINs 0000 through 9999
    for ($i = 0; $i <= 9999; $i++) {
        // Format as 4-digit PIN (0000, 0001, ... 9999)
        $pin = str_pad($i, 4, '0', STR_PAD_LEFT);
        if (md5($pin) === $md5_target) {
            $elapsed = microtime(true) - $start;
            return [
                'found' => true,
                'pin'   => $pin,
                'tries' => $i + 1,
                'time'  => $elapsed
            ];
        }
    }

    $elapsed = microtime(true) - $start;
    return [
        'found' => false,
        'tries' => 10000,
        'time'  => $elapsed
    ];
}

// Main page logic
$md5 = isset($_GET['md5']) ? trim($_GET['md5']) : '';

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>MD5 PIN Cracker (demo)</title>
  <style>
    body { font-family: Arial, sans-serif; max-width:760px; margin:2rem auto; }
    pre { background:#f6f6f6; padding:1rem; border-radius:6px; }
    input[type="text"] { width:70%; padding:0.4rem; }
    input[type="submit"] { padding:0.4rem 0.6rem; }
  </style>
</head>
<body>
  <h1>MD5 PIN Cracker — Demo</h1>

  <form method="get">
    <label>MD5: <input type="text" name="md5" value="<?php echo htmlentities($md5); ?>" /></label>
    <input type="submit" value="Crack MD5" />
  </form>

<?php if ($md5 !== ''): ?>
  <h2>Result</h2>
  <pre>
GET parameter: <?php echo htmlentities($_SERVER['REQUEST_URI']); ?>

<?php
// Basic validation: length 32 and hex chars
if (!preg_match('/^[0-9a-f]{32}$/i', $md5)) {
    echo "Error: MD5 should be a 32-character hexadecimal string.\n";
} else {
    $result = try_crack(strtolower($md5));
    if ($result['found']) {
        echo "PIN FOUND: " . $result['pin'] . "\n";
        echo "Tries: " . $result['tries'] . "\n";
        echo "Elapsed time: " . number_format($result['time'], 4) . " seconds\n";
    } else {
        echo "PIN NOT FOUND (tried all 0000..9999)\n";
        echo "Tries: " . $result['tries'] . "\n";
        echo "Elapsed time: " . number_format($result['time'], 4) . " seconds\n";
    }
}
?>
  </pre>
<?php endif; ?>

  <p>Notes:
    <ul>
      <li>This brute-force checks only 4-digit numeric PINs (0000–9999). If your assignment uses different charset/length, update the search accordingly.</li>
      <li>For GitHub: add this file to a repo, push, and then deploy (or test locally with PHP built-in server: <code>php -S localhost:8000</code> in the folder).</li>
      <li>Do not use this code to attack systems — for coursework only.</li>
    </ul>
  </p>

</body>
</html>
