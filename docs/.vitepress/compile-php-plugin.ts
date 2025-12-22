import { execSync } from 'node:child_process';
import { writeFileSync } from 'node:fs';
import { dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import type { MarkdownItAsync } from 'markdown-it-async';

const currentDirectory = dirname(fileURLToPath(import.meta.url));

const extractLang = function(info: string): string {
	return (
		/^[\w-]+/u
			.exec(info)?.[0]
			.replace(/-vue$/u, '')
			.replace(/^vue-html$/u, 'template')
			.replace(/^ansi$/u, '') || ''
	);
};

const shouldRenderBlock = function(info: string): boolean {
	// check againt {no-render} flag
	return !/\{no-render\}/u.test(info);
};

const shouldRenderInline = function(attributes: Array<[string, string]>): boolean {
	for (const [name, value] of attributes) {
		if (name === 'render') {
			return true;
		}
	}

	return false;
};

const render = function(content: string, type: 'block' | 'inline' | 'blade' = 'block'): string {
	// write to a temporary file and execute it
	writeFileSync(`${currentDirectory}/temp.php`, content, { encoding: 'utf8' });
	const process = execSync(`php ${currentDirectory}/compile.php ${type}`, {
		encoding: 'utf8',
	});
	return process.toString();
};

const compileCode = (md: MarkdownItAsync) => {
	const fence = md.renderer.rules.fence!;
	md.renderer.rules.fence = (...fenceArguments) => {
		const [tokens, index] = fenceArguments;
		const token = tokens[index];
		const lang = extractLang(token.info);
		const shouldRender = shouldRenderBlock(token.info);

		if (lang === 'php' && shouldRender){
			token.content = render(token.content);
			return fence(...fenceArguments);
		}

		if (lang === 'blade') {
			return render(token.content, 'blade');
		}

		return fence(...fenceArguments);
	};

	const codeRender = md.renderer.rules.code_inline!;
	md.renderer.rules.code_inline = (...codeInlineArguments) => {
		const [tokens, index, options] = codeInlineArguments;

		const token = tokens[index];
		if (!token.attrs || shouldRenderInline(token.attrs) === false) {
			return codeRender(...codeInlineArguments);
		}

		return render(token.content, 'inline');
	};
};

export { compileCode };