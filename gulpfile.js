const { series } = require('gulp');
const fs = require('fs');
const path = require('path');
const exec = require('child_process').exec;

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

// Default task to run all tasks in sequence
exports.default = series(createJwtDir, generatePrivateKey, generatePublicKey, setPermissions);
