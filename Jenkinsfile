pipeline {
  agent any
  options {
      buildDiscarder(logRotator(numToKeepStr: '20'))
  }
  stages {
    stage('Init') {
      steps {
        echo "Init $BRANCH_NAME on $JENKINS_URL ..."
        sh '''
          cp .c42/docker-compose.yml.dist docker-compose.yml
        '''
      }
    }
    stage('Build') {
      steps {
        echo "Building $BRANCH_NAME on $JENKINS_URL ..."
        sh '''
          docker run -t --rm \
          	-v `pwd`:/app \
              -e BUNDLE_APP_CONFIG=/app/.bundle \
              -w /app \
              ruby \
              bundle install --clean --path=vendors/bundle
        '''
      }
    }
    stage('Deploy') {
      parallel {
        stage('Staging') {
          when {
            anyOf {
              branch 'develop'
            }
          }
          steps {
            echo "Deploying $BRANCH_NAME from $JENKINS_URL ..."
            sshagent(['67d7d1aa-02cd-4ea0-acea-b19ec38d4366']) {
              sh '''
                docker run --rm \
                	-v `pwd`:/app \
                    -v "${SSH_AUTH_SOCK}:/run/ssh_agent" \
                    -v "${JENKINS_HOME}/.ssh/known_hosts:/root/.ssh/known_hosts:ro" \
                    -e SSH_AUTH_SOCK=/run/ssh_agent \
                    -e BUNDLE_APP_CONFIG=/app/.bundle \
                    -w /app \
                    ruby bash -c \
                    'bundle exec c42 deploy:preprod'
              '''
            }
          }
        }
        stage('Production') {
          when {
            anyOf {
              branch 'master'
            }
          }
          steps {
            input(message: "Are you sure you want to deploy on production?")
            echo "Deploying $BRANCH_NAME from $JENKINS_URL ..."
            sshagent(['67d7d1aa-02cd-4ea0-acea-b19ec38d4366']) {
              sh '''
                docker run --rm \
                	-v `pwd`:/app \
                    -v "${SSH_AUTH_SOCK}:/run/ssh_agent" \
                    -v "${JENKINS_HOME}/.ssh/known_hosts:/root/.ssh/known_hosts:ro" \
                    -e SSH_AUTH_SOCK=/run/ssh_agent \
                    -e BUNDLE_APP_CONFIG=/app/.bundle \
                    -w /app \
                    ruby bash -c \
                    'bundle exec c42 deploy:production'
              '''
            }
          }
        }
      }
    }
  }
  post {
      always {
          sh '''
            docker-compose down
            sudo chown -R $(id -u):$(id -g) ./
            '''
          deleteDir()
      }
  }
}
