import { defineConfig } from 'vitepress';
import type { UserConfig } from 'vitepress';
import { withSidebar } from 'vitepress-sidebar';
import type { VitePressSidebarOptions } from 'vitepress-sidebar/types';
import { compileCode } from './compile-php-plugin';

// https://vitepress.dev/reference/site-config
const vitePressOptions: UserConfig = {
	title: 'Carbon',
	description: 'A simple PHP API extension for DateTime.',
	themeConfig: {
		// https://vitepress.dev/reference/default-theme-config
		logo: '/logo.png',
		siteTitle: '',
		search: {
			provider: 'local',
		},
		nav: [
			{
				text: 'Guide',
				link: '/guide/getting-started/introduction',
			},
			{
				text: 'Reference',
				link: '/reference',
			},
		],

		socialLinks: [
			{
				icon: 'github',
				link: 'https://github.com/vuejs/vitepress',
			},
		],
		outline: {
			level: [2, 4],
		},
	},
	markdown: {
		config(md) {
			md.use(compileCode);
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