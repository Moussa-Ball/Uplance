.PHONY=dev; elastic-fresh; elastic-new; add-stripe-currency;

dev:
	tmux \
		new-session "php artisan serve" \;\
		split-window -v "npm run hot" \;\
		split-window -h "maildev --ip 127.0.0.1" \;\
		split-window -v "laravel-echo-server start" \;\

elastic-fresh:
	php artisan elastic:drop-index "App\UserIndexConfigurator"
	php artisan elastic:drop-index "App\JobIndexConfigurator"
	php artisan elastic:drop-index "App\ArticleIndexConfigurator"
	php artisan elastic:create-index "App\UserIndexConfigurator"
	php artisan elastic:create-index "App\JobIndexConfigurator"
	php artisan elastic:create-index "App\ArticleIndexConfigurator"
	php artisan elastic:update-mapping "App\User"
	php artisan elastic:update-mapping "App\Job"
	php artisan elastic:update-mapping "App\Article"
	php artisan scout:import "App\User"
	php artisan scout:import "App\Job"
	php artisan scout:import "App\Article"

elastic-new:
	php artisan elastic:create-index "App\UserIndexConfigurator"
	php artisan elastic:create-index "App\JobIndexConfigurator"
	php artisan elastic:create-index "App\ArticleIndexConfigurator"
	php artisan elastic:update-mapping "App\User"
	php artisan elastic:update-mapping "App\Job"
	php artisan elastic:update-mapping "App\Article"
	php artisan scout:import "App\User"
	php artisan scout:import "App\Job"
	php artisan scout:import "App\Article"


add-stripe-currency:
	php artisan currency:manage add AUD,BRL,GBP,CAD,CZK,DKK,EUR,HKD,HUF,ILS,JPY,MYR,MXN,TWD,NZD,NOK,PHP,PLN,RUB,SGD,SEK,CHF,THB,USD
