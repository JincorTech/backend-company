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
        sh 'docker-compose -f docker-compose.test.yml exec workspace bash'
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