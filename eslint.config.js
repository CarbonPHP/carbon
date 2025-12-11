import eslintConfig from '@kalimahapps/eslint-config';
export default [
	...eslintConfig,
	{
		rules: {
			// This rule is causing an error:
			// `Cannot read properties of undefined (reading 'decoration')` error
			// Disable it until it's fixed
			'unicorn/expiring-todo-comments': 'off',
			'jsonc/no-useless-escape': 'off',
		},
	},
	{
		ignores: ['!.vitepress/**', 'dist', 'cache', 'temp'],
	},
];