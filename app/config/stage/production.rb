server '159.65.172.240',
user: 'wonderly',
roles: %w{web app},
ssh_options: {
  forward_agent: false,
  auth_methods: %w(publickey password)
}

set :deploy_to, '/var/www/html'
set :branch, 'master'

set :symfony_env, 'prod'

set :controllers_to_clear, [ 'app_dev.php', 'config.php' ]
set :repo_url, 'git@github.com:ENGRTEAM/Wonderly.git'

namespace :deploy do
    task :script, roles => :app do
        on roles(:app) do
            within current_path do
                execute(:sh, "update.sh")
            end
        end
    end
end

after "deploy:create_symlink_to_web", "deploy:script"