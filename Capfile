require 'rubygems'
require 'railsless-deploy'

['HttpAuth', 'Composer', 'Magento', 'Modman'].each do |plugin|
  load "vendor/occitech/capistrano-recipes/#{plugin}-Recipe.rb"
end

load 'deploy' # remove this line to skip loading any of the default tasks