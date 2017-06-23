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
        sh 'docker-compose -f docker-compose.test.yml run -v /root/jenkins-ci/blueocean_home/workspace/"${PWD##*/}":/var/www/companies --rm workspace ./test.unit.sh'
        sh 'docker-compose -f docker-compose.test.yml run -v /root/jenkins-ci/blueocean_home/workspace/"${PWD##*/}":/var/www/companies --rm workspace ./test.api.sh'
      }
    }
    stage('Deploy') {
      steps {
        sh 'docker-compose -f docker-compose.test.yml push'
      }
    }
  }
}