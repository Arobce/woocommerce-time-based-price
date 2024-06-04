const fs = require('fs');
const path = require('path');
const archiver = require('archiver');
const glob = require('glob');

const pluginDir = path.resolve(__dirname); 
const outputDir = path.resolve(pluginDir, '../'); 
const outputZip = path.join(outputDir, 'time-based-product-pricing.zip');

const includePatterns = [
  '**/*.php',
  '**/*.css',
  '**/*.js',
  '**/*.html',
  '**/*.txt',
  'readme.txt',
  '!node_modules/**',
  '!archiver.js',
  '!**/*.log',
  '!**/*.DS_Store',
  '!**/__MACOSX/**',
  '!**/tests/**',
  '!**/docs/**',
  '!**/*.md',
  '!**/.git/**'
];

function createZip() {
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  const output = fs.createWriteStream(outputZip);
  const archive = archiver('zip', {
    zlib: { level: 9 }
  });

  output.on('close', function () {
    console.log(`${archive.pointer()} total bytes`);
    console.log('Plugin zip has been created successfully.');
  });

  archive.on('error', function (err) {
    throw err;
  });

  archive.pipe(output);

  // Collect all files matching the patterns
  const files = includePatterns.reduce((acc, pattern) => {
    return acc.concat(glob.sync(pattern, { cwd: pluginDir, dot: true }));
  }, []);

  // Add files to the archive
  files.forEach(file => {
    if (file !== 'archiver.js' && !file.includes('node_modules') && !file.match(/readme.txt$/i)) {
      const filePath = path.join(pluginDir, file);
      archive.file(filePath, { name: file });
    }
  });

  // Ensure root readme.txt is included if it exists
  if (fs.existsSync(path.join(pluginDir, 'readme.txt'))) {
    archive.file(path.join(pluginDir, 'readme.txt'), { name: 'readme.txt' });
  }

  archive.finalize();
}

createZip();
