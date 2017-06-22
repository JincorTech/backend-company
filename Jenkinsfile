pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml up --build -d'
      }
    }
    stage('Test') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml exec -T workspace /var/www/companies/test.api.sh'
        sh 'docker-compose -f docker-compose.test.yml down'
      }
    }
    stage('Deploy') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml push'
      }
    }
  }
}