pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'docker stop $(docker ps -aq)'
        sh 'docker-compose -f docker-compose.test.yml build'
      }
    }
    stage('Test') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml run --rm workspace /var/www/companies/test.init.sh'
      }
    }
    stage('Deploy') {
        steps {
          sh 'docker-compose -f docker-compose.test.yml push registry.jincor.com'
        }
    }
  }
}