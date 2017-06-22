pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml build'
      }
    }
    stage('Test') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml up -d'
        sh 'docker-compose -f docker-compose.test.yml exec workspace ./test.api.sh'
        sh 'docker-compose -f docker-compose.test.yml exec workspace ./test.unit.sh'
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