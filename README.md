## Composer Proxy


### Installation
--------------

1. Clone it.

    $ git clone https://github.com/HyanCat/composer-proxy

2. Resolve dependencies with composer

    $ composer install

3. Copy `config.example.php` and rename to `config.php`, then change configuration for application

4. Change permission for cache directories if needed

    $ chmod 777 cache

### Console Usage
--------------

Register the script to crontab

    php /path/to/app/console.php cache:clear-old-file

`cache:clear-old-file` command can delete old cache file. `packages.json` (the root file to define packages) is deleted every 5 minutes (default) by this command. Other file TTL is 6 months.

- --ttl (-t) : TTL of `packages.json`. default is 300 seconds.
- --dry-run : Show the action without real remove operations.
- --without-hashed-file : You can ignore to delete package definition file.
- --hashed-file-ttl : TTL of package definition file. default is 15,552,000 seconds. (6 months)


You can change TTL by options of this command.

If you need to delete all of the cache information, you can delete by following command.

    php /path/to/app/console.php cache:clear-all

