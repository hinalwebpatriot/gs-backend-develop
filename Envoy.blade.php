@setup
    $repository = 'git@gitlab.com:lenalltd/gsdiamonds/backend.git';
    $releases_dir = '/var/www/app/releases';
    $path = '/var/www/app';
	$date = ( new DateTime )->format('YmdHis'); //If you want a clear format you can use 'Y-m-d_H:i:s'
    $new_release_dir = $releases_dir .'/'. $date;
    $__container->servers([
    'dev' => 'deployer@18.185.204.28',
    'prod' => 'deployer@13.211.238.114'
    ]);
@endsetup

@task('init', ['on' => $server])
	if [ ! -d {{ $path }}/current ]; then
		cd {{ $path }}
		git clone {{ $repository }} --branch={{ $branch }} --depth=1 -q {{ $new_release_dir }}
		echo "Repository cloned"
		mv {{ $release }}/storage {{ $path }}/storage
		ln -s {{ $path }}/storage {{ $release }}/storage
		ln -s {{ $path }}/storage/public {{ $release }}/public/storage
		echo "Storage directory set up"
		cp {{ $release }}/.env.example {{ $path }}/.env
		ln -s {{ $path }}/.env {{ $release }}/.env
		echo "Environment file set up"
		rm -rf {{ $release }}
		echo "Deployment path initialised. Run 'envoy run deploy' now."
	else
		echo "Deployment path already initialised (current symlink exists)!"
	fi
@endtask

@story('deploy', ['on' => $server])
	deployment_start
	deployment_links
	deployment_composer
	deployment_migrate
	deployment_cache
	deployment_finish
	health_check
	deployment_option_cleanup
@endstory

@story('deploy_cleanup', ['on' => $server])
	deployment_start
	deployment_links
	deployment_composer
	deployment_migrate
	deployment_cache
	deployment_finish
	health_check
	deployment_cleanup
@endstory

@story('rollback', ['on' => $server])
	deployment_rollback
	health_check
@endstory

@task('deployment_start', ['on' => $server])
	echo "Deployment ({{ $date }}) start"
	[ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
	git clone {{ $repository }} --branch={{ $branch }} --depth=1 {{ $new_release_dir }}
	echo "Repository cloned"
	cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('deployment_links', ['on' => $server])
	cd {{ $path }}
	rm -rf {{ $new_release_dir }}/storage
	ln -s {{ $path }}/storage {{ $new_release_dir }}/storage
	ln -s {{ $path }}/storage/app/public {{ $new_release_dir }}/public/storage
	echo "Storage directories set up"
	ln -s {{ $path }}/.env {{ $new_release_dir }}/.env
	echo "Environment file set up"
@endtask

@task('deployment_composer', ['on' => $server])
	echo "Installing composer depencencies..."
	cd {{ $new_release_dir }}
	composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
@endtask

@task('deployment_migrate')
	php {{ $new_release_dir }}/artisan migrate --env={{ $env }} --force --no-interaction
@endtask

@task('deployment_cache', ['on' => $server])
	php {{ $new_release_dir }}/artisan optimize:clear --quiet
	echo "Cache cleared"
@endtask

@task('deployment_finish', ['on' => $server])
	php {{ $new_release_dir }}/artisan queue:restart --quiet
	echo "Queue restarted"
	ln -nfs {{ $new_release_dir }} {{ $path }}/current
	echo "Deployment ({{ $date }}) finished"
@endtask

@task('deployment_cleanup', ['on' => $server])
	cd {{ $path }}
	find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
	echo "Cleaned up old deployments"
@endtask

@task('deployment_option_cleanup', ['on' => $server])
	cd {{ $path }}
	@if ( isset($cleanup) && $cleanup )
		find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
		echo "Cleaned up old deployments"
	@endif
@endtask


@task('health_check', ['on' => $server])
	@if ( ! empty($healthUrl) )
		if [ "$(curl --write-out "%{http_code}\n" --silent --output /dev/null {{ $healthUrl }})" == "200" ]; then
			printf "\033[0;32mHealth check to {{ $healthUrl }} OK\033[0m\n"
		else
			printf "\033[1;31mHealth check to {{ $healthUrl }} FAILED\033[0m\n"
		fi
	@else
		echo "No health check set"
	@endif
@endtask


@task('deployment_rollback', ['on' => $server])
	cd {{ $path }}
	ln -nfs {{ $path }}/$(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1) {{ $path }}/current
	echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1)"
@endtask