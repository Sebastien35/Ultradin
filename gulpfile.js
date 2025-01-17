const { series, task } = require('gulp');
const fs = require('fs');
const path = require('path');
const exec = require('child_process').exec;
const { spawn } = require('child_process');

// Path to the JWT directory and files
const jwtDir = path.join(__dirname, 'ultradinapp', 'config', 'jwt');
const privateKeyPath = path.join(jwtDir, 'private.pem');
const publicKeyPath = path.join(jwtDir, 'public.pem');

// Task to create the directory for the keys
function createJwtDir(cb) {
  if (!fs.existsSync(jwtDir)) {
    fs.mkdirSync(jwtDir, { recursive: true });
    console.log(`Created directory: ${jwtDir}`);
  } else {
    console.log(`Directory already exists: ${jwtDir}`);
  }
  cb();
}

// Task to generate the private key
function generatePrivateKey(cb) {
  if (fs.existsSync(privateKeyPath)) {
    console.log(`Private key already exists: ${privateKeyPath}`);
    cb();
  } else {
    exec(`openssl genrsa -out ${privateKeyPath} 4096`, (err, stdout, stderr) => {
      console.log(stdout);
      console.error(stderr);
      cb(err);
    });
  }
}

// Task to generate the public key
function generatePublicKey(cb) {
  if (fs.existsSync(publicKeyPath)) {
    console.log(`Public key already exists: ${publicKeyPath}`);
    cb();
  } else {
    exec(`openssl rsa -pubout -in ${privateKeyPath} -out ${publicKeyPath}`, (err, stdout, stderr) => {
      console.log(stdout);
      console.error(stderr);
      cb(err);
    });
  }
}

// Task to set the appropriate permissions (optional on Windows)
function setPermissions(cb) {
  if (process.platform === 'win32') {
    console.log('Skipping chmod on Windows.');
    cb();
  } else {
    exec(`chmod 600 ${privateKeyPath} ${publicKeyPath}`, (err, stdout, stderr) => {
      console.log(stdout);
      console.error(stderr);
      cb(err);
    });
  }
}


task('keygen', series(createJwtDir, generatePrivateKey, generatePublicKey, setPermissions));


const ultradinAppDir = path.join(__dirname, 'ultradinapp');
const ultradinFrontDir = path.join(__dirname, 'ultradinfront')

//Task to install composer dependencies

function composerInstall(cb) {
  exec(`cd ${ultradinAppDir}  && composer install`, (err, stdout, stderr) => {
    if (stdout) console.log(stdout);
    if (stderr) console.error(stderr);
    cb(err);
  });
}

//Task to start docker server
function dockerComposeUp(cb) {
  console.log('Starting docker database container');

  exec('docker compose up -d', (err, stdout, stderr) => {
      if (err) {
          console.error('Error starting container:', err);
      }
      if (stdout) {
          console.log(stdout);
      }
      if (stderr) {
          console.error(stderr);
      }
      // Signal task completion
      cb();
  });
}

// Task to navigate to the Ultradin app directory and run migrations
function runMigrations(cb) {
  exec(`cd ${ultradinAppDir} && php bin/console doctrine:migration:migrate --no-interaction`, (err, stdout, stderr) => {
    console.log(stdout);
    console.error(stderr);

    if (stdout.includes('No migrations to execute.') || stdout.includes('Migrations are up to date')) {
      console.log('Migrations are up to date.');
      cb();
    } else if (stderr.includes('SQLSTATE[42S01]')) {
      console.warn('Migration error: Table already exists. Skipping migration.');
      cb(); // Consider this non-critical and continue
    } else {
      cb(err);
    }
  });
}

// Task to load fixtures
function loadFixtures(cb) {
  exec(`cd ${ultradinAppDir} && php bin/console doctrine:fixtures:load --append --no-interaction`, (err, stdout, stderr) => {
    console.log(stdout);
    console.error(stderr);
    cb(err);
  });
}


// Task to start symfony server 
function startSymfony(cb) {
  // Utilise 'cmd.exe' pour ouvrir un nouveau terminal et exécuter la commande
  const child = spawn('cmd.exe', [
    '/c', 
    'start',         // Ouvre une nouvelle fenêtre
    'cmd.exe', 
    '/k', 
    'symfony server:start'  // Commande à exécuter dans le nouveau terminal
  ], {
    cwd: ultradinAppDir, // Répertoire de travail
    detached: true       // Détache le processus pour qu'il ne dépende pas du processus parent
  });

  child.on('error', error => {
    console.error(`Erreur lors de l'ouverture du terminal : ${error}`);
    cb(error);
  });

  // Signale la fin de la tâche immédiatement après le lancement du terminal
  cb();
}

// Task to start symfony server 
function startExpoServer(cb) {
  const child = spawn('cmd.exe', [
    '/c',
    'start',               // Ouvre une nouvelle fenêtre
    'cmd.exe', 
    '/k', 
    'npx expo start'       // Commande à exécuter dans le nouveau terminal
  ], {
    cwd: ultradinFrontDir, // Répertoire de travail
    detached: true         // Détache le processus pour l'exécution indépendante
  });

  child.on('error', error => {
    console.error(`Erreur lors du lancement du serveur Expo: ${error}`);
    cb(error);
  });

  // Signale la fin de la tâche immédiatement après le lancement du terminal
  cb();
}



// Define the `migrate-fixtures` task
task('up', series(dockerComposeUp,  runMigrations, loadFixtures, startSymfony, startExpoServer));

exports.default = series(createJwtDir, generatePrivateKey, generatePublicKey, setPermissions);
