import TurndownService from 'turndown';
import turndownPluginGfm from 'turndown-plugin-gfm';
const  tables = turndownPluginGfm.tables;
const turndownService = new TurndownService({
  codeBlockStyle: 'fenced',
  headingStyle: 'atx'
}).use(tables);

turndownService.addRule('fencedCodeBlock', {
  filter: function (node, options) {
    return (
      options.codeBlockStyle === 'fenced' &&
      node.nodeName === 'PRE' &&
      node.firstChild &&
      node.firstChild.nodeName === 'CODE'
    )
  },

  replacement: function (content, node, options) {
    var className = node.firstChild.getAttribute('class') || '';

    return (
      '\n\n' + options.fence + className + '\n' +
      node.firstChild.textContent +
      '\n' + options.fence + '\n\n'
    )
  }
});

turndownService.addRule('inlineCode', {
  filter: function (node, options) {
    return (
      node.nodeName === 'CODE' &&
      node.firstChild &&
      node.firstChild.nodeName === 'CODE'
    )
  },

  replacement: function (content, node, options) {
    var className = node.firstChild.getAttribute('class') || '';

    return (
      '\n\n' + options.fence + className + '\n' +
      node.firstChild.textContent +
      '\n' + options.fence + '\n\n'
    )
  }
})

import { readFileSync, writeFileSync } from 'fs';
const fileToTranslate = '_docs/index.src.html';
const data = turndownService.turndown(
	readFileSync(fileToTranslate, 'utf8'),
);

const output = fileToTranslate.replace('.src.html', '.md');
writeFileSync(output, data, 'utf8');
console.log(`Converted ${fileToTranslate} to ${output}`);