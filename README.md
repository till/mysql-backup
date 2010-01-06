## What is it?

This is a really simple approach to backing up MySQL. The script will dump all your databases and email them to your mailbox. You can also CC someone on this email.

The frequency of backups depends on how often you crontab it. Remember that mysqldump will attempt to lock your database, so this script should run after hours.

**And of course, all of this is free. :-)**

## How does it work?

 * download the script
 * edit the configuration variables at the top
 * `chmod +x` the script
 * crontab it (e.g. at 2 AM in the morning):

    0 2 * * * cd /home/johndoe && ./backup.php

## Requirements

 * Linux/Unix - maybe MacOSX
 * PHP4+
 * ext/mysql
 * mysqldump
 * mutt (a cli mail client)
 * a local MTA which accepts the mail :-)

## Todo

 * clean up the backup files, when done ;-)

## Feedback?

 * please fork
 * please email me

## Like it?

 * twitter it
 * blog it
 * [my wishlist][0]

[0]: http://www.amazon.de/gp/registry/2RAPQ0AOQL6XX
