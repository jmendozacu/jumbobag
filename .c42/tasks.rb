MAGENTO_DB = "jumbobag"
MAGENTO_BO_PATH = "admin123"
MAGENTO_URL = "jumbobag.test"
REMOTE_HOST = "occitech-jbag"
REMOTE_MEDIA = "deployment/prod/current/htdocs/media"
REMOTE_DUMP = "jbag_mage.sql.gz"

SHELL = "/bin/bash"
MAGENTO_DIR = "/var/www/htdocs"
MAGERUN = "docker-compose run --rm web n98-magerun"
COMPOSER = "docker-compose run --rm composer --ignore-platform-reqs"

desc "mysql:console", "Lance la console mysql"
shell_task "mysql:console", "docker-compose up -d db && docker exec -i $(docker-compose ps -q db | sed -n 1p) /bin/bash -c 'mysql -uroot -proot jumbobag'"

desc "docker:run", "Lance docker-compose up"
shell_task "docker:run", "docker-compose up -d"

desc "composer", "Lance composer"
shell_task "composer", COMPOSER

desc "modman:deploy", "Lance modman"
shell_task "modman:deploy", "vendor/colinmollenhour/modman/modman deploy-all --force"

desc "dump:get DATE", "Recupère le dump de la bdd en fonction de la date"
task "dump:get" do |date|
  run("rsync -avz #{REMOTE_HOST}:admin/backup/#{date}/mysql/#{REMOTE_DUMP} .c42/tmp/dump.sql.gz")
end

package :mage do
	desc "run", "Run Magerun"
	shell_task "run", MAGERUN

	desc "sbase_url LOCAL_URL", "Set l'URL de Magento"
	task "base_url" do |url|
		url = check_url(url)
		fatal("L'URL n'a pas au bon format") unless url

		%x{
			#{MAGERUN} config:set web/unsecure/base_url #{url}/; \
			#{MAGERUN} config:set web/secure/base_url #{url}/
		}
	end

	desc "local_xml", "Reconstruit le local.xml"
	task "local_xml" do
		remove_file("htdocs/app/etc/local.xml")
		%x{
			#{MAGERUN} local-config:generate db root root jumbobag files soadmin a0365f41e5bf39ab158608c5fef8d270;
		}
	end

	desc "cache:clear", "Vide cache magento"
	shell_task "cache:clear", "#{MAGERUN} cache:flush; #{MAGERUN} cache:clean"

	desc "cache:disable", "Vide cache magento"
	shell_task "cache:disable", "#{MAGERUN} cache:disable"

	desc "symlinks", "Active les symlinks dans tout magento"
    shell_task "symlinks", "#{MAGERUN} dev:symlinks --global --on"

	desc "demo_notice", "Demo notice"
	shell_task "demo_notice", "#{MAGERUN} design:demo-notice --global"

	desc "flush_alerts", "Vide alerte de prix magento"
	shell_task "flush_alerts", %{#{MAGERUN} db:query "TRUNCATE product_alert_price; TRUNCATE product_alert_stock;"}

	desc "disable_smtp", "Disable SMTP"
	shell_task "disable_smtp", %{
		#{MAGERUN} config:delete --all system/smtp/disable
		#{MAGERUN} config:delete --all smtppro/general/option
		#{MAGERUN} config:set system/smtp/disable 1
		#{MAGERUN} config:set smtppro/general/option disabled
	}
end

desc "install URL_LOCAL", "Installe le projet sur URL_LOCAL"
task :install do
	url = "jumbobag.test"

  info("Invoking dump:get")
  invoke "dump:get", [Time.now.strftime("%Y-%m-%d")]

  sql_cat_cmd = "cat .c42/tmp/dump.sql" if File.exists?(".c42/tmp/dump.sql")
  sql_cat_cmd = "zcat .c42/tmp/dump.sql.gz" if File.exists?(".c42/tmp/dump.sql.gz")
  fatal("Could not find .c42/tmp/dump.sql[.gz]") unless defined?(sql_cat_cmd) && !sql_cat_cmd.nil?

  unless File.exists?(".c42/docker-compose.yml") && File.exists?("docker-compose.yml")
    info("copying docker-compose.yml")
    copy_file("docker-compose.yml.dist", ".c42/docker-compose.yml")
    create_link("docker-compose.yml", ".c42/docker-compose.yml")
    if yes?("Do you want to edit docker-compose.yml? [y/N]")
      system(%{"${EDITOR:-vim}" docker-compose.yml })
    end
  end

  unless File.exists?(".c42/docker/entrypoint.sh")
    info("copying docker-compose.yml")
    copy_file("docker/entrypoint.sh.dist", ".c42/docker/entrypoint.sh")
    if yes?("Do you want to edit .c42/docker/entrypoint.sh? [y/N]")
      system(%{"${EDITOR:-vim}" .c42/docker/entrypoint.sh })
    end
  end

  info("Chmoding docker/entrypoint.sh")
  chmod(".c42/docker/entrypoint.sh", 0755)

  info("Invoking composer install")
  invoke "composer", ["install"]

  info("Invoking docker:run")
  invoke "docker:run", []

#  info("Piping sql dump into mysql:console")
#  run("#{sql_cat_cmd} | c42 mysql:console")

  info("Invoking modman:deploy")
  invoke "modman:deploy", []

  info("Invoking mage:local_xml")
	invoke "mage:local_xml", []

	info("Invoking mage:base_url")
	invoke "mage:base_url", [url]

	info("Invoking mage:cache:clear")
	invoke "mage:cache:clear", []

	info("Invoking mage:cache:disable")
	invoke "mage:cache:disable", []

	info("Enabling symlinks")
    invoke "mage:symlinks", []

	info("Invoking mage:demo_notice")
	invoke "mage:demo_notice", []

	info("Invoking mage:disable_smtp")
	invoke "mage:disable_smtp", []
end

desc "pull", "rsync from prod server in case someone modified something there"
task :pull do
  exec("rsync -avzP --exclude=media --exclude=var/session --exclude=var/cache --exclude=var/log occitech-jbag:jumbobag/ htdocs/")
end

package :deploy do
	desc "preprod", "deploy to preprod"
	task :preprod do
	  # exec remplace le process actuel
	  exec("bundle exec cap preprod deploy")
	end

	desc "prod", "deploy (really) to prod"
	task :prod do
	  exec("bundle exec cap production deploy")
	end
end

desc "lmb", "rsync from prod server in case someone modified LMB there"
task :lmb do
  exec("rsync -avzP occitech-jbag:deployment/prod/current/htdocs/app/code/local/LMB/ htdocs/app/code/local/LMB/");
end


private

require "uri"
def check_url(url)
	url = "https://#{url}" unless url.respond_to?(:match) && url.match(/https?:\/\//i)
	uri = URI.parse(url) rescue nil
	if uri.nil? || uri.hostname.nil?
		nil
	else
		uri.to_s.gsub(/\/$/, "")
	end
end
