# The server-based syntax can be used to override options:
# ------------------------------------
server '10.1.1.57',
  user: 'jenkins',
  roles: %w{web app},
  ssh_options: {
    forward_agent: false,
    auth_methods: %w(publickey password)
  }

set :deploy_to, '/var/www/html/wonderly/demo'
set :branch, 'development'

namespace :deploy do
    task :script, roles => :app do
        on roles(:app) do
            execute(:sh, "#{current_path}/update.sh")
        end
    end
end

after "deploy:create_symlink_to_web", "deploy:script"