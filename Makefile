.PHONY=dev; elastic-fresh; elastic-new;

dev:
	tmux \
		new-session "php artisan serve" \;\
		split-window -v "yarn hot" \;\
		split-window -h "maildev --ip 127.0.0.1" \;\
		split-window -v "laravel-echo-server start" \;\

elastic-fresh:
	php artisan elastic:drop-index "App\UserIndexConfigurator"
	php artisan elastic:drop-index "App\JobIndexConfigurator"
	php artisan elastic:create-index "App\UserIndexConfigurator"
	php artisan elastic:create-index "App\JobIndexConfigurator"
	php artisan elastic:update-mapping "App\User"
	php artisan elastic:update-mapping "App\Job"
	php artisan scout:import "App\User"
	php artisan scout:import "App\Job"

elastic-new:
	php artisan elastic:create-index "App\UserIndexConfigurator"
	php artisan elastic:create-index "App\JobIndexConfigurator"
	php artisan elastic:update-mapping "App\User"
	php artisan elastic:update-mapping "App\Job"
	php artisan scout:import "App\User"
	php artisan scout:import "App\Job"
