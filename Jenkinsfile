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
        sh 'docker-compose -f docker-compose.test.yml exec workspace ./test.init.sh'
      }
    }
  }
}