/* eslint-env node */
module.exports = {
    env: {
        'browser': true,
        'commonjs': true,
        'es2021': true
    },
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:@typescript-eslint/recommended-requiring-type-checking',
        'prettier'
    ],
    parser: '@typescript-eslint/parser',
    parserOptions: {
        project: './tsconfig.json',
        'ecmaVersion': 13
    },
    ignorePatterns: ["/*.*"],
    plugins: [
        '@typescript-eslint'
    ],
    rules: {
        semi: [
            'error',
            'always'
        ],
        indent: [
            'error',
            4,
            {
                'SwitchCase': 1,
                'FunctionDeclaration': {
                    'body': 1,
                    'parameters': 2
                }
            }
        ],
        '@typescript-eslint/no-unsafe-assignment': 'warn',
        '@typescript-eslint/no-unsafe-return': 'warn',
        '@typescript-eslint/no-floating-promises': 'warn',
        '@typescript-eslint/no-unsafe-member-access': 'warn',
        '@typescript-eslint/no-unsafe-call': 'warn',
        '@typescript-eslint/no-unnecessary-type-assertion': 'off'
    },
    root: true,
};