pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml -p companies up --build -d'
      }
    }
    stage('Test') {
      steps {
        sh 'docker exec companies_workspace_1 /var/www/companies/test.api.sh'
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