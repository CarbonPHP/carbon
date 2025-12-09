import { defineConfig } from 'vitepress';
import type { UserConfig, MarkdownItAsync } from 'vitepress';
import { withSidebar } from 'vitepress-sidebar';
import type { VitePressSidebarOptions } from 'vitepress-sidebar/types';
import { execSync } from 'node:child_process';
import { writeFileSync } from 'node:fs';

const extractLang = function(info: string): string {
	return (
		/^[\w-]+/
			.exec(info)?.[0]
			.replace(/-vue$/, '') // remove -vue suffix
			.replace(/^vue-html$/, 'template')
			.replace(/^ansi$/, '') || ''
	);
};

const executePhp = async (md: MarkdownItAsync) => {
	const fence = md.renderer.rules.fence!;
	md.renderer.rules.fence = (...arguments_) => {
		const [tokens, index] = arguments_;
		const token = tokens[index];

		const lang = extractLang(token.info);
		if (lang !== 'php') {
			return fence(...arguments_);
		}

		// execute php code and get output
		const { content } = token;

		// write to a temporary file and execute it
		writeFileSync(`${__dirname}/temp.php`, content, { encoding: 'utf8' });
		const process = execSync(`php ${__dirname}/compile.php`, {
			encoding: 'utf8',
		});
		const output = process.toString();
		console.log('PHP Execution Output:', output);
		token.content = output;

		return fence(...arguments_);
	};
};

// https://vitepress.dev/reference/site-config
const vitePressOptions: UserConfig = {
	title: 'Carbon',
	description: 'A simple PHP API extension for DateTime.',
	themeConfig: {
		// https://vitepress.dev/reference/default-theme-config
		nav: [
			{
				text: 'Home',
				link: '/',
			},
			{
				text: 'Examples',
				link: '/markdown-examples',
			},
		],

		socialLinks: [
			{
				icon: 'github',
				link: 'https://github.com/vuejs/vitepress',
			},
		],
	},
	markdown: {
		config(md) {
			md.use(executePhp);
		},
	},
};

const vitePressSidebarOptions: VitePressSidebarOptions = {
	documentRootPath: '/docs',
	collapsed: false,
	capitalizeEachWords: true,
	useFolderLinkFromSameNameSubFile: true,
	useTitleFromFileHeading: true,
	useTitleFromFrontmatter: true,
	hyphenToSpace: true,
	underscoreToSpace: true,
	sortMenusByFrontmatterOrder: true,
};

const sidebars: VitePressSidebarOptions[] = [
	{
		...vitePressSidebarOptions,
		scanStartPath: 'guide',
		basePath: '/guide/',
		resolvePath: '/guide',
		manualSortFileNameByPriority: ['getting-started', 'core-api', 'date-time-manipulation', 'advanced-features'],
	},
	{
		...vitePressSidebarOptions,
		scanStartPath: 'reference',
		basePath: '/reference/',
		resolvePath: '/reference',
	},
];

export default defineConfig(withSidebar(vitePressOptions, sidebars));