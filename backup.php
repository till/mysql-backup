#!/usr/bin/env php
<?php
/**
 * Poor man's mysql backup script!
 *
 * Needs php(-cli), mysqldump, gzip, mutt and a local MTA.
 *
 * @category Backup
 * @package  backup
 * @author   Till Klampaeckel <till@php.net>
 * @license  New BSD License
 * @version  0.1.0
 * @link     http://github.com/till/mysql-backup
 */

/**
 * Database configuration.
 */
$user   = 'root';
$pass   = '';
$server = 'localhost';

/**
 * Email configuration
 */
$email = 'email@example.org';
$cc    = '';

if (!extension_loaded('mysql')) {
    echo "Backup script needs mysql.so to run.";
    exit(1);
}

$exec = array('mysqldump', 'gzip', 'mutt');

foreach ($exec as $exe) {
    if (!file_exists($exe)) {
        echo "Backup script needs {$exe} to run.";
        exit(1);
    }
    if (!is_executable($exe)) {
        echo "Cannot run {$exe}.";
        exit(1);
    }
}

$conn = mysql_connect($server, $user, $pass);
if (!$conn) {
    echo 'Could not connect.';
    exit(1);
}

$result = mysql_query('SHOW DATABASES');

$today = date('Y-m-d');
$store = "./backup_{$today}";
@mkdir($store);

$attachments = array();

while ($row = mysql_fetch_array($result)) {
    $db = $row['Database'];

    echo "Backup: {$db}\n";
    exec("mysqldump -h {$server} -u {$user} -p{$pass} {$db} > {$store}/{$db}.sql");
    exec("gzip {$store}/{$db}.sql");

    $attachments[] = "-a '{$store}/{$db}.sql.gz'";
}

mysql_close($conn);

$body    = "Backup: $today";
$subject = '[backup] Backup';

if (empty($email) || $email == 'email@example.org') {
    echo "No email set.";
    exit(1);
}

echo "Sending all backups to: {$email}\n";

$cmd = "echo '{$body}' | mutt -s '{$subject}' " . implode(' ', $attachments);
if (!empty($cc)) {
    $cmd .= " -c {$cc}";
}
$cmd .=  " '{$email}'";
exec($cmd);

exit;