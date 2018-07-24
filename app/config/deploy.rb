set :stage, fetch(:stage)

# Application name
set :application, 'Wonderly'

# Deploy configuration
set :symfony_env,                 'prod'
set :symfony_directory_structure, 3
set :sensio_distribution_version, 5
set :app_path,                    'app'
set :web_path,                    'web'
set :var_path,                    'var'
set :bin_path,                    'bin'

set :app_config_path, "#{fetch :app_path}/config"
set :log_path,        "#{fetch :var_path}/logs"
set :cache_path,      "#{fetch :var_path}/cache"

set :symfony_console_path,  "#{fetch :bin_path}/console"
set :symfony_console_flags, '--no-debug'

set :controllers_to_clear, [ 'app_dev.php', 'config.php' ]

# GIT config
set :repo_url, 'git@git.sibers.com:sibers/Wonderly.git'

set :linked_files, [ "#{fetch :app_config_path}/parameters.yml" ]
set :linked_dirs,  [ "#{fetch :var_path}/logs", "#{fetch :web_path}/uploads" ]

set :keep_releases, 3

set :permission_method,       :acl
set :file_permissions_groups, [ ]
set :file_permissions_paths,  [ ]

# Composer
set :composer_install_flags, '--no-interaction --optimize-autoloader'

namespace :deploy do
    task :touch_params, roles => :app do
        on roles(:app) do
            linked_files(shared_path).each do |file|
                unless test "[ -f #{file} ]"
                    execute(:touch, "#{file}")
                end
            end
        end
    end
    task :rewrite_params, roles => :app do
        stage = fetch(:stage)
        config_path = fetch(:app_config_path)
        on roles(:app) do
            within release_path do
                execute(:cp, "#{config_path}/parameters.yml.#{stage}","#{config_path}/parameters.yml")
            end
        end
    end
    task :create_symlink_to_web, roles => :app do
        on roles(:app) do
            execute(:ln, "-sf", "#{current_path}/web", "#{deploy_to}/web")
        end
    end
    task :migrate, roles => :app do
        on roles(:app) do
            invoke "symfony:console", "doctrine:migrations:migrate", "--no-interaction"
        end
    end
    task :assets, roles => :app do
        on roles(:app) do
            invoke "symfony:console", "assetic:dump", "--no-interaction"
        end
    end
end

before "deploy:check:linked_files", "deploy:touch_params"
before "composer:run",              "deploy:rewrite_params"
before "deploy:cleanup",            "deploy:create_symlink_to_web"
before "symfony:cache:warmup",      "deploy:migrate"
before "symfony:cache:warmup",      "deploy:assets"

