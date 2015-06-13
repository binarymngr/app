# binarymngr

> A web app to manage self-compiled binaries.

## Installing the application

After having cloned the repository, the commands below need to be run in order to use the application.

### Customize the Configuration(s)

```bash
nano config/app.php       # replace the 'key'
nano config/database.php  # set the MySQL credentials
nano config/session.php   # optionally set 'domain' and 'secure'
```

> You can also set an alternative queue backend in `config/queue.php`.

### Migrate/Seed the Database

```bash
php artisan migrate:install  # setup the Lumen migration table
php artisan migrate --seed   # setup the app migrations + seeds
```

### Create an Admin User

```bash
php artisan binarymngr:create-admin-user
```

### Process Queued Work

```bash
php artisan queue:listen --daemon  # without --daemon to run in foreground
```

> Right now, the only thing put into queue is a job to create messages if a newer binary version is created.

### Start the Scheduler

```bash
* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1  # e.g. in /etc/crontab
```

> The scheduler will create end-of-life reached messages once a day for installed binary versions.