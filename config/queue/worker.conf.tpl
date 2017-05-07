[supervisord]
nodaemon=true

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel/artisan queue:work %%QUEUE_CONNECTION%% --queue=%%QUEUE_NAME%%
autostart=true
autorestart=true
numprocs=2
startretries=100
stdout_events_enabled=1
