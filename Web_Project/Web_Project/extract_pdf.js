const fs = require('fs');
const { PDFParse } = require('./node_modules/pdf-parse');

async function extractText() {
  const dataBuffer = fs.readFileSync('ICT2204_ProjectProposal_Phase01-8-12.pdf');
  const parser = new PDFParse();
  const data = await parser.parse(dataBuffer);
  fs.writeFileSync('pdf_extracted.txt', data.text, 'utf8');
  console.log('Pages:', data.numpages);
  console.log('Done. Text length:', data.text.length);
}

extractText().catch(err => {
  console.error('Error:', err.message);
  console.error(err.stack);
});
