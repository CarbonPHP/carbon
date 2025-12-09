import { defineConfig } from 'vitepress';
import { withSidebar } from 'vitepress-sidebar';
import type { VitePressSidebarOptions } from 'vitepress-sidebar/types';

// https://vitepress.dev/reference/site-config
const vitePressOptions = {
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
		manualSortFileNameByPriority: ['getting-started', 'core-api'],
	},
	{
		...vitePressSidebarOptions,
		scanStartPath: 'reference',
		basePath: '/reference/',
		resolvePath: '/reference',
	},
];

export default defineConfig(withSidebar(vitePressOptions, sidebars));