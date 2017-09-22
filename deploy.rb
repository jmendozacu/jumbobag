###
# TODO :
#		- Ajouter la configuration automatique du .htaccess en fin de fichier: multiboutique + options spécifiques au serveur?
#
set :stages, %w(production wip)
set :default_stage, "wip"

task :production do
	server "occitech-jbag", :app, :web, :db, :primary => true
	set :deploy_to, "/home/jbag/deployment/prod"
	set :branch, "master"

	before "deploy:create_symlink" do
		run "touch #{latest_release}#{app_path}maintenance.flag"
	end
	after "deploy:finalize_update" do
	end
	after "deploy", "mage:enable"
end

task :wip do
	server "occitech-jbag", :app, :web, :db, :primary => true
	set :deploy_to, "/home/jbag/deployment/wip"
	set :branch, "develop"

	after "deploy:finalize_update", "magento:password_protect"
end

# see https://github.com/capistrano/capistrano/blob/master/lib/capistrano/ext/multistage.rb#L22
on :load do
	if stages.include?(ARGV.first)
		find_and_execute_task(ARGV.first) if ARGV.any?{ |option| option =~ /-T|--tasks|-e|--explain/ }
	else
		find_and_execute_task(default_stage) if exists?(:default_stage)
	end
end

set :application, "jumbobag"
set :repository,  "git@github.com:Commit42/jumbobag.git"

set :scm, :git
set :git_enable_submodules, 1
set :deploy_via, :remote_cache
set :ssh_options, {:forward_agent => true}

set :use_sudo, false
set :keep_releases, 2
set :user, "jbag"

set :app_path, "/htdocs/"
set :http_auth_path, app_path
set :app_symlinks, app_symlinks | []
set :app_shared_files, ["/app/etc/local.xml"]

set :composer_options, "--verbose --prefer-dist --no-dev"

namespace :magento do
	desc 'Update content in the shared directory from the repository'
	task :finalize_update do
		run [
			"mkdir -p #{latest_release}#{app_path}media/page",
			"rm -Rf #{shared_path}#{app_path}media/page",
			"cp -r #{latest_release}#{app_path}media/page #{shared_path}#{app_path}media/"
		].join(' && ')
	end

	task :password_protect do
		run "sed -i 's/Order allow,deny/#Order allow,deny/' #{latest_release}#{http_auth_path}/.htaccess"
		run "sed -i 's/Allow from all/#Allow from all/' #{latest_release}#{http_auth_path}/.htaccess"

		set :http_auth_users, [["demo", "magento2017"]]
		httpAuth.protect

		run "sed -i 's/Require valid-user//' #{latest_release}#{http_auth_path}/.htaccess"
		# 88.161.227.195 # Pierre @Home
		# 109.190.45.252 # Occitech
		# 195.101.99.76 # Paybox 1
		# 194.2.122.158 # Paybox 2
		# 195.25.7.166 # Paybox 3
		[
			'<LimitExcept POST>',
			'Require valid-user',
			'# IspecificPS Testeurs',
			'Deny From All',
			'Allow from 88.161.227.195 109.190.45.252 195.101.99.76 194.2.122.158 195.25.7.166',
			'Satisfy Any',
			'</LimitExcept>'
		].each { |line|
			run "echo '#{line}' >> #{current_release}#{http_auth_path}/.htaccess"
		}
  end

  task :restart_process do
    run "curl -s https://www.jumbobag.fr > /dev/null"
    run "pkill php-cgi"
  end
end

before "mage:finalize_update", "composer:install", "modman:deploy_all", "magento:finalize_update"

after "deploy", "deploy:cleanup"
after "deploy", "mage:cc"
after "deploy", "magento:restart_process"
