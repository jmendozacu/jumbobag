['HttpAuth', 'Composer', 'Magento', 'Modman'].each do |plugin|
	load ".c42/deploy/recipes/occitech/#{plugin}-Recipe.rb"
end

set :app_shared_dirs, (app_shared_dirs | ["/feeds"])
set :app_shared_files, (app_shared_files | ["/sitemap.xml", "/sitemap_en.xml", "/sitemap_de.xml", "/sitemap_it.xml"])
set :stages, %w(production preprod)
set :default_stage, "preprod"

server "occitech3.alwaysdata.net", :app, :web, :db, :primary => true

task :preprod do
	set :deploy_to, "/home/jbag/deployment/preprod"
	set :branch, "develop"
	set :webhost, "https://dev.jumbobag.fr"
		
	set :http_auth_users, [["demo", "demo2017"]]
	after "deploy:finalize_update", "httpAuth:protect"
	after "jumbobag:finalize_update" do
		run "rm -rf #{latest_release}/htdocs/app/etc/modules/LMB_EDI.xml #{latest_release}/htdocs/app/code/local/LMB"
	end
end

task :production do
	set :deploy_to, "/home/jbag/deployment/prod"
	set :branch, "master"
	set :webhost, "https://www.jumbobag.fr"
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
set :repository,  "git@github.com:commit42/jumbobag.git"

set :scm, :git
set :git_enable_submodules, 1
set :deploy_via, :remote_cache
set :ssh_options, { :forward_agent => true }

set :use_sudo, false
set :keep_releases, 3
set :user, "jbag"

set :app_path, "/htdocs/"
set :http_auth_path, app_path

set :composer_options, "--verbose --prefer-dist --no-dev"

before "deploy:create_symlink" do
	run "touch #{latest_release}#{app_path}maintenance.flag"
end

before "mage:finalize_update", "jumbobag:finalize_update"

after "deploy", "mage:enable"
after "deploy", "deploy:cleanup"
after "deploy", "mage:cc"

namespace :jumbobag do
	desc 'Update content in the shared directory from the repository'
	task :finalize_update do
		composer.install
		modman.deploy_all
		run "cp #{File.join(latest_release, app_path, ".htaccess-dist")} #{File.join(latest_release, app_path, ".htaccess")}"
	end
	
	task :restart_process do
		run "pkill php-cgi"
		run "curl -s -o /dev/null -w \"%{http_code}\" #{webhost}; true"
	end
	
	after "deploy", "jumbobag:restart_process"
end  

after "deploy" do
	run "echo #{latest_revision} > #{File.join(latest_release, app_path, 'rev.txt')}"
end
