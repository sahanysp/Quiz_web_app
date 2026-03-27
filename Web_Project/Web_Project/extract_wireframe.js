const fs = require('fs');
const pdf = require('pdf-parse');

async function extractText() {
  const dataBuffer = fs.readFileSync('ICT2204_ProjectProposal_Phase01-8-12.pdf');
  const data = await pdf(dataBuffer);
  fs.writeFileSync('wireframe_extracted.txt', data.text, 'utf8');
  console.log('Done.');
}
extractText().catch(console.error);
