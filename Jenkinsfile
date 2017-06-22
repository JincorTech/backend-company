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
        sh 'docker exec ${COMPOSE_PROJECT_NAME}_workspace_1 /var/www/companies/test.api.sh'
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