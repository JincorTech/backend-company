pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml build'
      }
    }
    stage('Test API') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml exec workspace /var/www/companies/test.api.sh'
      }
    }
    stage('Deploy') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml push'
      }
    }
  }
}